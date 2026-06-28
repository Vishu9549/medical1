<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Setting;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'mode' => 'required|string',
        ]);

        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Pehle account login karein checkout karne ke liye.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Cart is empty!');
        }

        $shop = Shop::findOrFail($request->shop_id);
        $cartItems = \App\Models\Medicine::whereIn('id', array_keys($cart))->get();

        $items = [];
        $totalPrice = 0;

        foreach ($cartItems as $med) {
            $invKey = preg_replace('/(\s+\d+mg|\s+\d+g|\s+mg)/i', '', $med->name);
            $inv = $shop->inventories()
                        ->where(function($q) use ($med, $invKey) {
                            $q->where('medicine_id', $med->id)
                              ->orWhere('name', 'like', "%{$invKey}%");
                        })->first();
            
            $price = $inv ? (float)$inv->price : (float)$med->price;
            $qty = $cart[$med->id] ?? 1;

            $items[] = [
                'name' => $med->name,
                'price' => $price,
                'quantity' => $qty,
                'emoji' => $med->emoji
            ];
            $totalPrice += $price * $qty;
        }

        $deliveryCharge = 0;
        $deliveryAddress = null;
        if ($request->mode === 'delivery') {
            $deliveryCharge = $shop->distance_km > 10 ? 0 : round($shop->distance_km * 8);
            $deliveryAddress = $request->address_name . ', ' 
                             . $request->address_line1 . ', ' 
                             . $request->address_line2 . ', ' 
                             . $request->address_city . ' - ' 
                             . $request->address_pincode;
        }

        $order = Order::create([
            'shop_id' => $shop->id,
            'status' => 'Pending',
            'mode' => $request->mode,
            'total_price' => $totalPrice,
            'delivery_charge' => $deliveryCharge,
            'delivery_address' => $deliveryAddress,
            'items' => $items,
            'user_id' => Auth::id() // Securely bind order to customer
        ]);

        // Update Wallet total sales & commission if commission is enabled
        $commOn = Setting::getVal('comm_on', 'true') === 'true';
        if ($commOn) {
            $commRate = (float) Setting::getVal('comm_rate', '2');
            $dueComm = ($totalPrice * $commRate) / 100;
            
            $wallet = Wallet::where('shop_id', $shop->id)->first();
            if ($wallet) {
                $wallet->total_sales += $totalPrice;
                $wallet->due_commission += $dueComm;
                if ($wallet->due_commission >= $wallet->credit_limit) {
                    $wallet->status = 'restricted';
                }
                $wallet->save();
            }
        }

        // Clear Cart
        session()->forget('cart');

        return redirect('/order/' . $order->id . '/success')->with('success', 'Order placed successfully!');
    }

    public function success($id)
    {
        $order = Order::findOrFail($id);
        
        // Security check: Only the customer who placed the order (or shop owner, or admin) can view the success receipt
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Pehle account login karein.');
        }

        $user = Auth::user();
        if ($order->user_id !== $user->id && $order->shop_id !== $user->shop?->id && $user->role !== 'admin') {
            abort(403, 'Aapko is order ko dekhne ki permission nahi hai.');
        }

        $order->shop = Shop::findOrFail($order->shop_id);

        return view('customer.order_success', compact('order'));
    }
}
