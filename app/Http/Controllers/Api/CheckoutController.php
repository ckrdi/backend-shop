<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    protected $request;
    protected $snap_token;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->only('store');

        $this->request = $request;

        // Set Merchant Server Key
        Config::$serverKey = \config('services.midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = \config('services.midtrans.isProduction');
        // Set sanitization on (default)
        Config::$isSanitized = \config('services.midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = \config('services.midtrans.is3ds');
    }

    public function store()
    {
        DB::transaction(function () {
            // create invoice no.
            $random = '';
            for ($i = 0; $i < 10; $i++) {
                $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
            }
            $invoiceNo = 'INV-'.Str::upper($random);

            // create invoice
            $invoice = Invoice::create([
                'invoice'       => $invoiceNo,
                'customer_id'   => auth()->user()->id,
                'courier'       => $this->request->courier,
                'service'       => $this->request->service,
                'cost_courier'  => $this->request->cost,
                'weight'        => $this->request->weight,
                'name'          => $this->request->name,
                'phone'         => $this->request->phone,
                'province'      => $this->request->province,
                'city'          => $this->request->city,
                'address'       => $this->request->address,
                'status'        => 'pending',
                'grand_total'   => $this->request->grand_total
            ]);

            // for each cart in the carts table that belong to a customer,
            // make an order for that cart
            foreach (Cart::where('customer_id', auth()->user()->id)->get() as $cart) {
                Order::create([
                    'invoice_id'    => $invoice->id,
                    'invoice'       => $invoiceNo,
                    'product_id'    => $cart->product_id,
                    'product_name'  => $cart->product->title,
                    'image'         => $cart->product->image,
                    'qty'           => $cart->quantity,
                    'price'         => $cart->price
                ]);
            }

            // payload for midtrans snap token
            $payload = [
                'transaction_details' => [
                    'order_id'      => $invoice->invoice,
                    'gross_amount'  => $invoice->grand_total,
                ],
                'customer_details' => [
                    'first_name'       => $invoice->name,
                    'email'            => auth()->user()->email,
                    'phone'            => $invoice->phone,
                    'shipping_address' => $invoice->address
                ]
            ];

            $snapToken = Snap::getSnapToken($payload);
            $invoice->update(['snap_token' => $snapToken]);
            $this->snap_token = $snapToken;
        });

        return response()->json([
            'success' => true,
            'message' => 'Order Successfully created',
            'snap_token' => $this->snap_token
        ]);
    }

    public function notificationHandler(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $validSignatureKey = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . config('services.midtrans.serverKey'));

        if ($notification->signature_key != $validSignatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        $transactionData = Invoice::where('invoice', $orderId)->first();

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $transactionData->update(['status' => 'pending']);
                } else {
                    $transactionData->update(['status' => 'success']);
                }
            }
        } elseif ($transaction == 'settlement') {
            $transactionData->update(['status' => 'success']);
        } elseif ($transaction == 'deny') {
            $transactionData->update(['status' => 'failed']);
        } elseif ($transaction == 'expire') {
            $transactionData->update(['status' => 'expired']);
        } elseif ($transaction == 'cancel') {
            $transactionData->update(['status' => 'failed']);
        }
    }
}
