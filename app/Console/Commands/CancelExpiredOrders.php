<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders that have not been paid after 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = \App\Models\Order::where('status', 'waiting_payment')
            ->where('created_at', '<', now()->subMinutes(1439)) // 23 hours 59 minutes
            ->get();

        foreach ($expiredOrders as $order) {
            // Optionally, call Midtrans API to cancel the transaction as well
            try {
                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.sandbox.midtrans.com/v2/' . $order->order_id . '/cancel');
            } catch (\Exception $e) {
                // Log or handle error if needed
            }

            $order->status = 'cancelled';
            $order->cancellation_reason = 'Otomatis dibatalkan karena melewati batas waktu pembayaran';
            $order->cancelled_at = now();
            $order->save();
        }

        $this->info('Expired orders cancelled: ' . $expiredOrders->count());
    }
}
