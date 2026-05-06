<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    protected $signature   = 'orders:cancel-expired';
    protected $description = 'Auto-cancel pesanan yang melewati batas waktu pembayaran';

    public function handle(): void
    {
        $minutes = config('bearindo.order_expiry_minutes', 10);
        $expiredAt = now()->subMinutes($minutes);

        $expired = Order::where('status', 'pending')
            ->whereHas('payment', function($q) {
                $q->where('status', 'pending'); // Belum upload bukti
            })
            ->where('created_at', '<=', $expiredAt)
            ->get();

        if ($expired->isEmpty()) {
            $this->info('Tidak ada pesanan yang expired.');
            return;
        }

        foreach ($expired as $order) {
            $order->update(['status' => 'cancelled']);
            $this->info("Pesanan {$order->order_number} di-cancel (expired).");
        }

        $this->info("Total {$expired->count()} pesanan di-cancel.");
    }
}