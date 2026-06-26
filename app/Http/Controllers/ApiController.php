<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Setting;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function init()
    {
        // Get unique category names or base medicine names for searching
        $medicinesList = ['Paracetamol', 'Azithromycin', 'Cetirizine', 'Omeprazole', 'Ibuprofen', 'Dolo 650', 'Metformin', 'Amoxicillin'];

        $catalog = Medicine::all()->map(function ($med) {
            $disc = $med->mrp > 0 ? round((($med->mrp - $med->price) / $med->mrp) * 100) : 0;
            // Generate inv key (e.g. "Paracetamol 500mg" -> "Paracetamol")
            $invKey = preg_replace('/(\s+\d+mg|\s+\d+g|\s+mg)/i', '', $med->name);
            return [
                'id' => 'm' . $med->id,
                'name' => $med->name,
                'cat' => $med->category,
                'emoji' => $med->emoji,
                'mrp' => (float)$med->mrp,
                'price' => (float)$med->price,
                'disc' => $disc,
                'inv' => $invKey
            ];
        });

        $shops = Shop::where('status', 'approved')->get()->map(function ($shop) {
            $inventory = [];
            foreach ($shop->inventories as $inv) {
                if ($inv->medicine) {
                    $key = preg_replace('/(\s+\d+mg|\s+\d+g|\s+mg)/i', '', $inv->medicine->name);
                    $inventory[$key] = (float) $inv->price;
                } else {
                    $inventory[$inv->name] = (float) $inv->price;
                }
            }
            return [
                'id' => $shop->id,
                'name' => $shop->name,
                'area' => $shop->area,
                'rating' => (float)$shop->rating,
                'reviews' => (int)$shop->reviews,
                'distanceKm' => (float)$shop->distance_km,
                'top' => (bool)$shop->is_top,
                'deliveryEnabled' => (bool)$shop->delivery_enabled,
                'isOnline' => (bool)$shop->is_online,
                'inventory' => $inventory
            ];
        });

        return response()->json([
            'medicines' => $medicinesList,
            'catalog' => $catalog,
            'shops' => $shops
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'mode' => 'required|string', // pickup, delivery
            'total_price' => 'required|numeric',
            'delivery_charge' => 'required|numeric',
            'items' => 'required|array'
        ]);

        $order = Order::create([
            'shop_id' => $request->shop_id,
            'status' => 'Pending',
            'mode' => $request->mode,
            'total_price' => $request->total_price,
            'delivery_charge' => $request->delivery_charge,
            'items' => $request->items
        ]);

        // Update Wallet total sales & commission if commission is enabled
        $commOn = Setting::getVal('comm_on', 'true') === 'true';
        if ($commOn) {
            $commRate = (float) Setting::getVal('comm_rate', '2');
            $dueComm = ($request->total_price * $commRate) / 100;
            
            $wallet = Wallet::where('shop_id', $request->shop_id)->first();
            if ($wallet) {
                $wallet->total_sales += $request->total_price;
                $wallet->due_commission += $dueComm;
                // If due commission exceeds limit, we restrict the account
                if ($wallet->due_commission >= $wallet->credit_limit) {
                    $wallet->status = 'restricted';
                }
                $wallet->save();
            }
        }

        // Return order with shop info for orderSuccess view
        $shop = Shop::find($request->shop_id);
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'shop' => [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'area' => $shop->area,
                    'distanceKm' => (float)$shop->distance_km
                ],
                'status' => $order->status,
                'mode' => $order->mode,
                'totalWithDelivery' => (float)($order->total_price + $order->delivery_charge),
                'available' => $order->items
            ]
        ]);
    }

    public function getOrder($id)
    {
        $order = Order::findOrFail($id);
        $shop = Shop::find($order->shop_id);
        return response()->json([
            'id' => $order->id,
            'shop' => [
                'id' => $shop->id,
                'name' => $shop->name,
                'area' => $shop->area,
                'distanceKm' => (float)$shop->distance_km
            ],
            'status' => $order->status,
            'mode' => $order->mode,
            'totalWithDelivery' => (float)($order->total_price + $order->delivery_charge),
            'available' => $order->items
        ]);
    }
}
