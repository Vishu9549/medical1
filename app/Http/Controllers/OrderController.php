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

        if (!$shop->is_online) {
            return redirect()->back()->with('error', "Store Offline! Orders band hain is store ke.");
        }

        if (!$shop->isOpen()) {
            $opens = date('h:i A', strtotime($shop->opens_at ?? '09:00'));
            $closes = date('h:i A', strtotime($shop->closes_at ?? '21:00'));
            return redirect()->back()->with('error', "Store Closed! Order nahi kiya ja sakta. Store timings: $opens se $closes.");
        }

        if ($request->mode === 'delivery') {
            if (!$shop->delivery_enabled) {
                return redirect()->back()->with('error', "Home Delivery is currently disabled for this shop. Please select Self Pickup.");
            }
            if ($shop->distance_km > ($shop->delivery_radius_km ?? 10.0)) {
                return redirect()->back()->with('error', "Your address is out of delivery radius (Max {$shop->delivery_radius_km} KM). Please select Self Pickup.");
            }
        }

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

            if ($inv) {
                $newQty = max(0, $inv->quantity - $qty);
                $inv->update(['quantity' => $newQty]);
            }

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
            if ($shop->delivery_charge_type === 'fixed') {
                $deliveryCharge = (float)($shop->delivery_charge_fixed ?? 20);
            } else {
                $deliveryCharge = round($shop->distance_km * ($shop->delivery_charge_per_km ?? 8));
            }
            $deliveryAddress = $request->address_name . ', ' 
                             . $request->address_line1 . ', ' 
                             . $request->address_line2 . ', ' 
                             . $request->address_city . ' - ' 
                             . $request->address_pincode;
        }

        // Calculate offer discount
        $discountAmount = 0;
        if ($shop->offer_min_bill > 0 && $totalPrice >= $shop->offer_min_bill && $shop->offer_discount_pct > 0) {
            $discountAmount = round(($totalPrice * $shop->offer_discount_pct) / 100, 2);
        }

        $order = Order::create([
            'shop_id' => $shop->id,
            'status' => 'Pending',
            'mode' => $request->mode,
            'total_price' => $totalPrice - $discountAmount,
            'delivery_charge' => $deliveryCharge,
            'discount_amount' => $discountAmount,
            'delivery_address' => $deliveryAddress,
            'items' => $items,
            'user_id' => Auth::id()
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
