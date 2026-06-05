<?php

namespace App\Console\Commands;

use App\Mail\AbandonedCartRecover;
use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoManageCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wooflix:manage-carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recover user carts and purge old ghost data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. RECOVER USER CARTS (Modified between 2 to 4 hours ago, not yet emailed)
        $cutoffStart = Carbon::now()->subHours(4);
        $cutoffEnd = Carbon::now()->subHours(2);

        $abandonedCarts = CartItem::with('user', 'product', 'variant')
            ->whereNotNull('user_id')
            ->whereBetween('updated_at', [$cutoffStart, $cutoffEnd])
            ->get()
            ->groupBy('user_id');

        foreach ($abandonedCarts as $userId => $items) {
            $user = $items->first()->user;
            if ($user && $user->email) {
                Mail::to($user->email)->send(new AbandonedCartRecover($user, $items));

                // Touch the timestamps so they aren't emailed again in the next cycle loop
                CartItem::where('user_id', $userId)->update(['updated_at' => Carbon::now()]);
            }
        }

        // 2. PURGE ANCIENT CARTS (Older than 7 days)
        $expiryDate = Carbon::now()->subDays(7);
        $deletedCount = CartItem::where('updated_at', '<', $expiryDate)->delete();

        $this->info("Emailed " . $abandonedCarts->count() . " customers and purged {$deletedCount} old cart records.");
    }
}
