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
        // 1. Capture the guest session ID while it still exists!
        $guestSessionId = session()->getId();

        $request->authenticate();

        // 2. Laravel regenerates the session here (changing the ID)
        $request->session()->regenerate();

        // 3. Pass the old captured guest ID into the merge routine
        $this->mergeCart($guestSessionId);

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

    protected function mergeCart(string $oldSessionId)
    {
        $userId = auth()->id();

        DB::transaction(function () use ($userId, $oldSessionId) {
            // FIX: Query using the captured guest session ID passed from store()
            $guestItems = CartItem::where('session_id', $oldSessionId)
                ->whereNull('user_id')
                ->get();

            if ($guestItems->isEmpty()) {
                return;
            }

            $userItems = CartItem::where('user_id', $userId)->get();

            foreach ($guestItems as $guest) {
                $existing = $userItems->first(function ($item) use ($guest) {
                    return $item->product_id === $guest->product_id &&
                        $item->variant_id == $guest->variant_id;
                });

                if ($existing) {
                    $existing->increment('quantity', $guest->quantity);
                    $guest->delete();
                } else {
                    // Lazy load relationships safely if your conditional price logic needs them
                    $currentPrice = $guest->variant
                        ? ($guest->variant->sale_price ?: $guest->variant->price)
                        : ($guest->product->sale_price ?? $guest->product->base_price ?? $guest->price);

                    $guest->update([
                        'user_id'    => $userId,
                        'session_id' => null,
                        'price'      => $currentPrice,
                    ]);

                    $userItems->push($guest);
                }
            }
        });
    }
}
