<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $this->mergeCart();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function mergeCart()
    {
        $userId = auth()->id();
        // It is safer to grab the session ID before any potential session regeneration
        $sessionId = session()->getId(); 

        DB::transaction(function () use ($userId, $sessionId) {
            // 1. Fetch guest items using the session ID
            $guestItems = CartItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();

            if ($guestItems->isEmpty()) {
                return;
            }

            // 2. Fetch existing user items to compare
            $userItems = CartItem::where('user_id', $userId)->get();

            foreach ($guestItems as $guest) {
                // 3. Look for a match in the user's existing cart
                // We use == for variant_id to handle NULL (simple product) vs integer (variant)
                $existing = $userItems->first(function ($item) use ($guest) {
                    return $item->product_id === $guest->product_id &&
                        $item->variant_id == $guest->variant_id;
                });

                if ($existing) {
                    // ✅ Item exists: Update quantity and delete the guest record
                    $existing->increment('quantity', $guest->quantity);
                    $guest->delete();
                } else {
                    // ✅ Item is new to the user: Re-assign the guest record
                    // Optional: We refresh the price here to ensure the user isn't 
                    // getting an outdated price from a days-old guest session.
                    $currentPrice = $guest->variant 
                        ? ($guest->variant->sale_price ?: $guest->variant->price) 
                        : ($guest->product->sale_price ?: $guest->product->base_price);

                    $guest->update([
                        'user_id'    => $userId,
                        'session_id' => null, // Clear the session binding
                        'price'      => $currentPrice, 
                    ]);

                    // Update the local collection so the next loop iteration 
                    // sees this item if there were duplicate guest rows (rare)
                    $userItems->push($guest);
                }
            }
        });
    }
}
