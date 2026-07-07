<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Inventory;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    private function getActiveShop()
    {
        if (Auth::check()) {
            return Auth::user()->shop;
        }
        return null;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'owner' => 'required|string',
            'phone' => 'required|string',
            'area' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opens_at' => 'required|string',
            'closes_at' => 'required|string',
            'delivery_enabled' => 'nullable',
            'delivery_charge_type' => 'required|string|in:fixed,dynamic',
            'delivery_charge_rate' => 'nullable|numeric|min:0'
        ]);

        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Pehle account login karein.');
        }

        $deliveryEnabled = $request->has('delivery_enabled');
        $chargeType = $request->delivery_charge_type;
        $rate = (float)($request->delivery_charge_rate ?? 0.00);

        $fixedCharge = ($chargeType === 'fixed') ? $rate : 0.00;
        $perKmCharge = ($chargeType === 'dynamic') ? $rate : 0.00;

        $shop = Shop::create([
            'name' => $request->name,
            'owner_name' => $request->owner,
            'phone' => $request->phone,
            'area' => $request->area,
            'address' => $request->address,
            'rating' => 5.0,
            'reviews' => 0,
            'distance_km' => round(rand(5, 45) / 10, 1),
            'is_top' => false,
            'delivery_enabled' => $deliveryEnabled,
            'is_online' => true,
            'status' => 'approved', // Automatically approve for demo purposes
            'user_id' => Auth::id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'opens_at' => $request->opens_at,
            'closes_at' => $request->closes_at,
            'delivery_charge_type' => $chargeType,
            'delivery_charge_fixed' => $fixedCharge,
            'delivery_charge_per_km' => $perKmCharge,
        ]);

        // Create wallet
        Wallet::create([
            'shop_id' => $shop->id,
            'total_sales' => 0,
            'due_commission' => 0,
            'credit_limit' => 100,
            'status' => 'active'
        ]);

        // Automatically update role to shop_owner if they were customer
        $user = Auth::user();
        if ($user->role === 'customer') {
            $user->role = 'shop_owner';
            $user->save();
        }

        return redirect('/shop/dashboard')->with('success', 'Store registered successfully!');
    }

    public function dashboard()
    {
        $shop = $this->getActiveShop();
        if (!$shop) {
            return redirect('/profile')->with('error', 'Pehle store register karein!');
        }

        $orders = Order::where('shop_id', $shop->id)->orderBy('id', 'desc')->get();
        $ordersCount = $orders->count();
        $revenue = $orders->sum('total_price');
        
        $inventory = Inventory::where('shop_id', $shop->id)->get();
        $inventoryCount = $inventory->count();

        $wallet = Wallet::where('shop_id', $shop->id)->first();

        $prescriptions = \App\Models\Prescription::where('shop_id', $shop->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('shop.dashboard', compact('shop', 'ordersCount', 'revenue', 'inventoryCount', 'wallet', 'prescriptions'));
    }

    public function toggleOnline(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $shop->is_online = !$shop->is_online;
        $shop->save();

        return redirect()->back()->with('success', 'Online status updated!');
    }

    public function toggleDelivery(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $shop->delivery_enabled = !$shop->delivery_enabled;
        $shop->save();

        return redirect()->back()->with('success', 'Delivery status updated!');
    }

    public function quickSetupIndex(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $category = $request->input('category', 'All');
        $search = $request->input('q', '');
        $company = $request->input('company', 'All');

        $medQuery = Medicine::query();
        if ($category !== 'All') {
            if ($category === 'Tablet') {
                $medQuery->where(function($q) {
                    $q->where('product_form', 'like', '%tablet%')
                      ->orWhere('product_form', 'like', '%capsule%');
                });
            } elseif ($category === 'Liquid') {
                $medQuery->where(function($q) {
                    $q->where('product_form', 'like', '%liquid%')
                      ->orWhere('product_form', 'like', '%suspension%')
                      ->orWhere('product_form', 'like', '%syrup%')
                      ->orWhere('product_form', 'like', '%solution%')
                      ->orWhere('product_form', 'like', '%drop%');
                });
            } elseif ($category === 'Powder') {
                $medQuery->where(function($q) {
                    $q->where('product_form', 'like', '%powder%')
                      ->orWhere('product_form', 'like', '%sachet%')
                      ->orWhere('product_form', 'like', '%granule%');
                });
            } elseif ($category === 'Injection') {
                $medQuery->where(function($q) {
                    $q->where('product_form', 'like', '%injection%')
                      ->orWhere('product_form', 'like', '%vial%')
                      ->orWhere('product_form', 'like', '%ampoule%')
                      ->orWhere('product_form', 'like', '%prefilled pen%');
                });
            } elseif ($category === 'Ointment/Cream') {
                $medQuery->where(function($q) {
                    $q->where('product_form', 'like', '%ointment%')
                      ->orWhere('product_form', 'like', '%cream%')
                      ->orWhere('product_form', 'like', '%gel%')
                      ->orWhere('product_form', 'like', '%lotion%');
                });
            } else {
                $medQuery->where('product_form', 'like', "%{$category}%");
            }
        }
        if ($search) {
            $medQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('product_form', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        
        $masterMedicinesCollection = $medQuery->get();
        
        // Filter by company name dynamically (accessor attribute)
        if ($company !== 'All') {
            $masterMedicinesCollection = $masterMedicinesCollection->filter(function($med) use ($company) {
                return strcasecmp($med->company, $company) === 0;
            });
        }
        
        $allCompanies = ['Cipla Ltd', 'Abbott India', 'Sun Pharma', 'Alkem Laboratories', 'Mankind Pharma', 'Lupin Ltd'];
        
        // Paginate the collection manually
        $perPage = 30;
        $page = (int) $request->input('page', 1);
        $sliced = $masterMedicinesCollection->slice(($page - 1) * $perPage, $perPage)->values();
        
        $masterMedicines = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $masterMedicinesCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $shopInventory = Inventory::where('shop_id', $shop->id)->get();

        return view('shop.quicksetup', compact('shop', 'masterMedicines', 'shopInventory', 'category', 'search', 'company', 'allCompanies'));
    }

    public function quickSetupSave(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $qsSel = $request->input('qs_sel', []);
        $addedCount = 0;

        foreach ($qsSel as $medIdStr => $data) {
            $medId = (int) str_replace('m', '', $medIdStr);
            $has = isset($data['has']) && $data['has'] === 'true';
            $price = (float) ($data['price'] ?? 0);
            $qty = (int) ($data['qty'] ?? 50);

            if ($has) {
                Inventory::updateOrCreate(
                    ['shop_id' => $shop->id, 'medicine_id' => $medId],
                    ['price' => $price, 'quantity' => $qty]
                );
                $addedCount++;
            }
        }

        return redirect('/shop/inventory')->with('success', $addedCount . ' Catalogue medicines added successfully!');
    }

    public function inventoryIndex()
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $inventory = Inventory::where('shop_id', $shop->id)->with('medicine')->get();

        return view('shop.inventory', compact('shop', 'inventory'));
    }

    public function medicineSearchSuggestions(Request $request)
    {
        $q = $request->input('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }
        $meds = Medicine::where('name', 'like', '%' . $q . '%')->limit(15)->get();
        return response()->json($meds);
    }

    public function inventoryAdd(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $medId = $request->input('medicine_id');
        $master = null;
        if ($medId) {
            $master = Medicine::find($medId);
        } else {
            $master = Medicine::where('name', 'like', '%' . $request->name . '%')->first();
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/medicines'), $filename);
                $imagePaths[] = '/uploads/medicines/' . $filename;
            }
        }

        Inventory::create([
            'shop_id' => $shop->id,
            'medicine_id' => $master ? $master->id : null,
            'name' => $master ? null : $request->name,
            'price' => $request->price,
            'quantity' => $request->qty,
            'images' => !empty($imagePaths) ? $imagePaths : null
        ]);

        return redirect('/shop/inventory')->with('success', 'Medicine stock added manually!');
    }

    public function inventoryDelete(Request $request, $id)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        // Confirm inventory item belongs to this shop for protection
        Inventory::where('id', $id)->where('shop_id', $shop->id)->delete();

        return redirect('/shop/inventory')->with('success', 'Medicine stock removed!');
    }

    public function ordersIndex()
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $orders = Order::where('shop_id', $shop->id)->orderBy('id', 'desc')->get();

        return view('shop.orders', compact('shop', 'orders'));
    }

    public function ordersUpdate(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $request->validate([
            'order_id' => 'required|integer',
            'status' => 'required|string'
        ]);

        // Secure checking that order belongs to active shop owner
        $order = Order::where('id', $request->order_id)->where('shop_id', $shop->id)->firstOrFail();
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated to ' . $request->status);
    }

    public function updateTimings(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $request->validate([
            'opens_at' => 'required|string',
            'closes_at' => 'required|string',
        ]);

        $shop->opens_at = $request->opens_at;
        $shop->closes_at = $request->closes_at;
        $shop->save();

        return redirect()->back()->with('success', 'Store timings updated successfully!');
    }

    public function updateDeliverySettings(Request $request)
    {
        $shop = $this->getActiveShop();
        if (!$shop) return redirect('/profile');

        $request->validate([
            'delivery_radius_km' => 'required|numeric|min:0.1',
            'delivery_charge_type' => 'required|string|in:fixed,dynamic',
            'delivery_charge_fixed' => 'nullable|numeric|min:0',
            'delivery_charge_per_km' => 'nullable|numeric|min:0',
            'offer_min_bill' => 'required|numeric|min:0',
            'offer_discount_pct' => 'required|numeric|min:0|max:100',
        ]);

        $shop->delivery_radius_km = $request->delivery_radius_km;
        $shop->delivery_charge_type = $request->delivery_charge_type;
        $shop->delivery_charge_fixed = $request->delivery_charge_fixed ?? 0.00;
        $shop->delivery_charge_per_km = $request->delivery_charge_per_km ?? 0.00;
        $shop->offer_min_bill = $request->offer_min_bill;
        $shop->offer_discount_pct = $request->offer_discount_pct;
        $shop->save();

        return redirect()->back()->with('success', 'Delivery and Offer settings updated successfully!');
    }

    public function settingsIndex()
    {
        $shop = $this->getActiveShop();
        if (!$shop) {
            return redirect('/profile')->with('error', 'Pehle store register karein!');
        }

        $wallet = Wallet::where('shop_id', $shop->id)->first();

        return view('shop.settings', compact('shop', 'wallet'));
    }
}
