<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Shop;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        $catalogQuery = Medicine::query();
        if ($query) {
            $catalogQuery->where('name', 'like', "%{$query}%")
                         ->orWhere('category', 'like', "%{$query}%");
        }
        $catalog = $catalogQuery->limit(100)->get()->map(function ($med) {
            $disc = $med->mrp > 0 ? round((($med->mrp - $med->price) / $med->mrp) * 100) : 0;
            return (object) [
                'id' => $med->id,
                'name' => $med->name,
                'category' => $med->category,
                'emoji' => $med->emoji,
                'price' => (float)$med->price,
                'mrp' => (float)$med->mrp,
                'disc' => $disc,
                'images' => $med->images
            ];
        });

        return view('customer.smartcart', compact('catalog', 'cart', 'cartCount', 'query'));
    }

    public function add(Request $request)
    {
        $request->validate(['medicine_id' => 'required|integer']);
        $medId = $request->medicine_id;
        
        $cart = session('cart', []);
        $cart[$medId] = ($cart[$medId] ?? 0) + 1;
        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cartCount' => array_sum($cart),
                'qty' => $cart[$medId]
            ]);
        }
        return redirect()->back()->with('success', 'Medicine added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|integer',
            'qty' => 'required|integer|min:0'
        ]);
        $medId = $request->medicine_id;
        $qty = $request->qty;

        $cart = session('cart', []);
        if ($qty == 0) {
            unset($cart[$medId]);
        } else {
            $cart[$medId] = $qty;
        }
        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cartCount' => array_sum($cart),
                'qty' => $qty
            ]);
        }
        return redirect()->back();
    }

    public function results()
    {
        $cart = session('cart', []);
        $cartItems = Medicine::whereIn('id', array_keys($cart))->get();
        if ($cartItems->isEmpty()) {
            return redirect('/smartcart')->with('error', 'Cart is empty!');
        }

        $allShops = Shop::where('status', 'approved')->where('is_online', true)->get();
        $matches = [];

        foreach ($allShops as $shop) {
            $available = [];
            $missing = [];
            $totalPrice = 0;

            foreach ($cartItems as $med) {
                // Generate inv key matches
                $invKey = preg_replace('/(\s+\d+mg|\s+\d+g|\s+mg)/i', '', $med->name);
                
                // Look up in inventories relation
                $inv = $shop->inventories()
                            ->where(function($q) use ($med, $invKey) {
                                $q->where('medicine_id', $med->id)
                                  ->orWhere('name', 'like', "%{$invKey}%");
                            })->first();

                if ($inv) {
                    $available[] = [
                        'id' => $med->id,
                        'name' => $med->name,
                        'emoji' => $med->emoji,
                        'shopPrice' => (float)$inv->price
                    ];
                    $totalPrice += $inv->price * $cart[$med->id];
                } else {
                    $missing[] = $med;
                }
            }

            // Calculate delivery charges according to shop settings
            $deliveryCharge = 0;
            if ($shop->delivery_charge_type === 'fixed') {
                $deliveryCharge = (float)($shop->delivery_charge_fixed ?? 20);
            } else {
                $deliveryCharge = round($shop->distance_km * ($shop->delivery_charge_per_km ?? 8));
            }

            // Calculate active offers and bill payment discount
            $discount = 0;
            if ($shop->offer_min_bill > 0 && $totalPrice >= $shop->offer_min_bill && $shop->offer_discount_pct > 0) {
                $discount = round(($totalPrice * $shop->offer_discount_pct) / 100, 2);
            }

            // Check delivery radius constraint
            $isOutOfRadius = $shop->distance_km > ($shop->delivery_radius_km ?? 10.0);

            $matches[] = [
                'shop' => $shop,
                'available' => $available,
                'missing' => $missing,
                'matchCount' => count($available),
                'totalPrice' => $totalPrice,
                'deliveryCharge' => $deliveryCharge,
                'discount' => $discount,
                'isOutOfRadius' => $isOutOfRadius,
                'totalWithDelivery' => $totalPrice - $discount + ($shop->delivery_enabled && !$isOutOfRadius ? $deliveryCharge : 0)
            ];
        }

        // Sort: highest match count first, then cheapest total price
        usort($matches, function ($a, $b) {
            if ($b['matchCount'] !== $a['matchCount']) {
                return $b['matchCount'] - $a['matchCount'];
            }
            return $a['totalWithDelivery'] <=> $b['totalWithDelivery'];
        });

        $bestMatch = $matches[0] ?? null;
        if (!$bestMatch) {
            return redirect('/smartcart')->with('error', 'No matching shops found!');
        }

        $cartCount = array_sum($cart);

        return view('customer.cart_results', compact('bestMatch', 'cart', 'cartItems', 'cartCount'));
    }
}
