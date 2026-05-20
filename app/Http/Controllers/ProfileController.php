<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function dashboard()
    {
        $orders = Order::with(['items.product:id,slug,name,main_image'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(2)
            ->get();

        $latestReview = Review::with('product:id,slug,name')
            ->where('user_id', auth()->id())
            ->where('is_approved', true)
            ->latest()
            ->first();

        $wishlist = Wishlist::with('product:id,slug,name,main_image')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('orders', 'latestReview', 'wishlist'));
    }

    public function orders()
    {
        $orders = Order::with([
            'items.product:id,slug,name,main_image',
        ])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
