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

        return redirect()->intended(route('dashboard'));

        // return redirect()->intended(route('dashboard', absolute: false));
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
        $sessionId = session()->getId();

        DB::transaction(function () use ($userId, $sessionId) {

            // Get guest cart
            $guestItems = CartItem::where('session_id', $sessionId)->get();

            if ($guestItems->isEmpty()) {
                return;
            }

            // Get user cart once
            $userItems = CartItem::where('user_id', $userId)->get();

            foreach ($guestItems as $guest) {

                // Match existing item (handle NULL variant properly)
                $existing = $userItems->first(function ($item) use ($guest) {
                    return $item->product_id === $guest->product_id &&
                        $item->variant_id == $guest->variant_id; // works for null
                });

                if ($existing) {
                    // ✅ Merge quantity
                    $existing->increment('quantity', $guest->quantity);

                    // Remove guest row
                    $guest->delete();
                } else {
                    // ✅ Assign to user
                    $guest->update([
                        'user_id' => $userId,
                        'session_id' => null,
                    ]);

                    // Keep local collection in sync (important)
                    $userItems->push($guest);
                }
            }
        });
    }
}
