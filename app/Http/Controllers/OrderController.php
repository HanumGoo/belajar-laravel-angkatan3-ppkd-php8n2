<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::orderBy("created_at", "desc")->get();
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        $products = Product::OrderBy('id')->get();
        return view('order.create', compact('categories', 'products'));
    }

    public function creater()
    {
        $categories = Category::get();
        $products = Product::OrderBy('id')->get();
        return view('order.creater', compact('categories', 'products'));
    }

    public function notification(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        //for cash
        if ($request->payment_method == 'cash') {
            $order->update([
                'status' => 2
            ]);
            return response()->json([
                'message' => 'Notification processed on cash'
            ]);
        }

        if (
            $request->transaction_status === 'settlement' ||
            $request->transaction_status === 'capture'
        ) {

            if ($request->fraud_status === 'challenge') {
                $order->update([
                    'status' => 1
                ]);
            } else {
                $order->update([
                    'status' => 2
                ]);
            }

        } elseif ($request->transaction_status === 'pending') {

            $order->update([
                'status' => 1
            ]);

        } elseif (
            $request->transaction_status === 'deny' ||
            $request->transaction_status === 'expire' ||
            $request->transaction_status === 'cancel'
        ) {

            $order->update([
                'status' => 0
            ]);
        }

        return response()->json([
            'message' => 'Notification processed'
        ]);
    }
    public function store(Request $request)
    {


        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'nullable'
        ]);

        try {
            $snapToken = null;
            $orderId = null;
            $orderCode = null;
            DB::transaction(function () use ($request, &$orderId, &$snapToken, &$orderCode) {
                $itemData = [];
                $items = $request->items;

                $subtotal = 0;
                foreach ($items as $item) {
                    $product = Product::find($item['id']);

                    $itemSubTotal = $product->price * $item['qty'];
                    $subtotal += $itemSubTotal;

                    $itemData[] = [
                        'product' => $product,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $itemSubTotal
                    ];
                }

                $tax = $subtotal * 0.11;
                $total = $subtotal + $tax;
                $orderCode = 'ORD-' . date('ymd') . '-' . rand(1000, 9999);
                $paymentMethod = $request->payment_method ?? 'cash';
                $order = Order::create([
                    'order_code' => $orderCode,
                    'order_amount' => $total,
                    'order_change' => $paymentMethod == 'cash' ? $request->change_amount : 0,
                    'status' => 1
                ]);

                $orderId = $order->id;

                foreach ($itemData as $data) {

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product']->id,
                        'order_qty' => $data['qty'],
                        'order_price' => $data['price'],
                        'order_subtotal' => $data['subtotal'],
                    ]);
                    if ($paymentMethod === 'cash') {
                        $data['product']->decrement('qty', $data['qty']);
                    }
                }
                if ($paymentMethod === 'midtrans') {
                    Config::$serverKey = config('services.midtrans.server_key');
                    Config::$clientKey = config('services.midtrans.client_key');
                    Config::$isProduction = config('services.midtrans.is_production', false);
                    Config::$isSanitized = true;
                    Config::$is3ds = true;

                    $params = [
                        'transaction_details' => [
                            'order_id' => $order->id,
                            'order_code' => $order->order_code,
                            'gross_amount' => (int) round($total)
                        ],
                        'customer_details' => [
                            'first_name' => $request->customer_name ?? 'No-Name'
                        ]
                        // 'enabled_payments' => ['gopay', 'qris'],
                    ];
                    $snapToken = Snap::getSnapToken($params);

                }

            });
            if ($request->payment_method === 'midtrans') {
                return response()->json([
                    'success' => true,
                    'payment_method' => 'midtrans',
                    'snap_token' => $snapToken,
                    'order_id' => $orderId,
                    'order_code' => $orderCode,
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil di proses!',
                    'payment_method' => 'cash',
                    'order_id' => $orderId,
                    'order_code' => $orderCode,
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Transaksi gagal di proses!' . $th
            ], 400);
        }
        // 200 artinya HTTP OK



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
