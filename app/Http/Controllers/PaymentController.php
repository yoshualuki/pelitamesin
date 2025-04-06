<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;    
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        return view('customer.checkout');
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'courier' => 'required',
            'service' => 'required',
            'shipping_cost' => 'required|numeric'
        ]);

        $cart = session()->get('cart', []);
        $user = session()->get('user');
        $totalWeight = 0;
        $subtotal = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            $subtotal += $product->price * $details['quantity'];
            $totalWeight += $product->weight * $details['quantity'];
        }

        $total = $subtotal + $request->shipping_cost;
        $orderId = 'ORD-' . time() . '-' . Str::random(4);

        DB::beginTransaction();

        try {
            // 1. Buat order
            $order = Order::create([
                'order_id' => $orderId,
                'user_id' => $user->id,
                'total_amount' => $subtotal,
                'shipping_cost' => $request->shipping_cost,
                'courier' => $request->courier,
                'service' => $request->service,
                'status' => 'waiting_payment',
                'weight' => $totalWeight,
                'final_amount' => $total,
                'recipient_name' => $request->name,
                'recipient_email' => $request->email,
                'recipient_phone' => $request->phone,
                'shipping_address' => $request->address,
                'province' => $request->province,
                'city' => $request->city,
                'estimated_delivery' => $request->estimated_delivery
            ]);

            // 2. Buat order details (TANPA mengurangi stok)
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'quantity' => $details['quantity'],
                    'price' => $product->price,
                    'weight' => $product->weight
                ]);
            }

            // 3. Siapkan payload Midtrans
            $itemDetails = [];
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                $itemDetails[] = [
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $details['quantity'],
                    'name' => $product->name
                ];
            }
            
            // Tambahkan ongkir sebagai item
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $request->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . $request->courier . ' - ' . $request->service . ')'
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'billing_address' => [
                        'address' => $request->address,
                        'city' => $request->city,
                        'postal_code' => '',
                    ],
                    'shipping_address' => [
                        'address' => $request->address,
                        'city' => $request->city,
                        'postal_code' => '',
                    ]
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s T'),
                    'unit' => 'hours',
                    'duration' => 24
                ]
            ];

            // 4. Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            DB::commit();

            session()->remove('cart');
            return response()->json([
                'snapToken' => $snapToken,
                'orderId' => $orderId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Proses pembayaran gagal'], 500);
        }
    }
    

    public function handleWebhook(Request $request)
    {
        app('debugbar')->info('Webhook received', $request->all());
        
        $serverKey = config('midtrans.server_key');
        $data = $request->all();
    
        // Verifikasi signature key
        $signatureKey = hash("sha512",
            $data['order_id'] .
            $data['status_code'] .
            $data['gross_amount'] .
            $serverKey
        );
    
        if ($signatureKey !== $data['signature_key']) {
            app('debugbar')->error('Invalid signature key');
            return response()->json(['message' => 'Invalid signature key'], 403);
        }
    
        // Cari transaksi berdasarkan order_id
        $transaction = Order::where('order_id', $data['order_id'])->first();
    
        // Ambil nomor VA
        $vaNumber = null;
        $bank = null;
        

        if (!$transaction) {
            app('debugbar')->error('Transaction not found: ' . $data['order_id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    
        // handle payment type
        if (isset($data['va_numbers'])) {
            // Format untuk BCA, BNI, BRI, dll
            $vaNumber = $data['va_numbers'][0]['va_number'];
            $bank = $data['va_numbers'][0]['bank'];
            $transaction->payment_method = 'Virtual Akun ' . $bank;
            $transaction->payment_code = $vaNumber;
        } elseif (isset($data['permata_va_number'])) {
            // Format khusus Permata
            $vaNumber = $data['permata_va_number'];
            $bank = 'permata';
            $transaction->payment_code = $vaNumber;
            $transaction->payment_method='Virtual Akun ' . $bank;
        } elseif(isset($data['credit_card'])) {
            $bank = $data['credit_card']['bank'];
            $transaction->payment_method = $data['payment_type'] . ' '. $bank;
        }
        $transaction->payment_date= now();
        // Update status berdasarkan transaction_status
        switch ($data['transaction_status']) {
            case 'capture':
            case 'settlement':
                $transaction->status = 'waiting_confirmation';
                break;
            case 'pending':
                $transaction->status = 'pending';
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
                $transaction->status = 'failed';
                break;
            case 'refund':
            case 'partial_refund':
                $transaction->status = 'refunded';
                break;
            default:
                $transaction->status = $data['transaction_status'];
                break;
        }
    
        // Simpan data tambahan jika diperlukan
        // $transaction->payment_method = $data['payment_type'] ?? null;
        // $transaction->transaction_time = $data['transaction_time'] ?? null;
        $transaction->save();
    
        app('debugbar')->info('Transaction updated', ['order_id' => $transaction->order_id, 'status' => $transaction->status]);
        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
