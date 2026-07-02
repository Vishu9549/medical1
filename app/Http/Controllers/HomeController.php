<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Medicine;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $pills = ['Paracetamol', 'Azithromycin', 'Cetirizine', 'Omeprazole', 'Ibuprofen', 'Dolo 650', 'Metformin', 'Amoxicillin'];
        
        $categories = [
            ['icon' => '🤒', 'label' => 'Bukhar', 'color' => '#FEF3C7'],
            ['icon' => '💊', 'label' => 'Antibiotic', 'color' => '#DBEAFE'],
            ['icon' => '❤️', 'label' => 'Heart', 'color' => '#FEE2E2'],
            ['icon' => '🧴', 'label' => 'Skin', 'color' => '#F3E8FF'],
            ['icon' => '🦷', 'label' => 'Dental', 'color' => '#E0F2FE'],
            ['icon' => '👁️', 'label' => 'Eye', 'color' => '#DCFCE7']
        ];

        $city = session('user_location', 'Muzaffarpur');
        $allShops = Shop::where('status', 'approved')->get();
        
        $shops = $allShops->sortBy(function($shop) use ($city) {
            return stripos($shop->address, $city) !== false ? 0 : 1;
        })->values();

        $cityShops = $allShops->filter(function($shop) use ($city) {
            return stripos($shop->address, $city) !== false;
        });

        if ($cityShops->isEmpty()) {
            $shopsCount = $allShops->count();
            $onlineShopsCount = $allShops->where('is_online', true)->count();
        } else {
            $shopsCount = $cityShops->count();
            $onlineShopsCount = $cityShops->where('is_online', true)->count();
        }

        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.home', compact('pills', 'categories', 'shops', 'shopsCount', 'onlineShopsCount', 'cart', 'cartCount'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $shopId = $request->input('shop_id');
        $selectedCategories = $request->input('categories', []);
        $selectedShop = null;

        $medQuery = Medicine::query();
        
        if ($shopId) {
            $selectedShop = Shop::findOrFail($shopId);
            // If searching in a specific shop, retrieve only master medicines mapped in its inventories
            $medIds = $selectedShop->inventories()->pluck('medicine_id')->filter()->toArray();
            $medQuery->whereIn('id', $medIds);
        }

        if ($query) {
            $medQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            });
        }

        if (!empty($selectedCategories)) {
            $medQuery->whereIn('category', $selectedCategories);
        }

        $medicines = $medQuery->get();
        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        // Get all unique categories for checkbox sidebar filter
        $allCategories = ['Fever', 'Antibiotic', 'Allergy', 'Acidity', 'Pain', 'Diabetes', 'Heart', 'Supplement', 'Skin', 'Eye', 'Dental'];

        return view('customer.search', compact('medicines', 'cart', 'cartCount', 'query', 'selectedShop', 'allCategories', 'selectedCategories'));
    }

    public function medicineDetails($id, Request $request)
    {
        $medicine = Medicine::findOrFail($id);
        $shopId = $request->input('shop_id');
        $selectedShop = null;
        $price = $medicine->price;

        if ($shopId) {
            $selectedShop = Shop::find($shopId);
            if ($selectedShop) {
                $inventory = $selectedShop->inventories()->where('medicine_id', $id)->first();
                if ($inventory) {
                    $price = $inventory->price;
                }
            }
        }

        $relatedMedicines = Medicine::where('category', $medicine->category)
            ->where('id', '!=', $id)
            ->limit(6)
            ->get();

        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.medicine_details', compact('medicine', 'relatedMedicines', 'price', 'selectedShop', 'cart', 'cartCount'));
    }

    public function profile()
    {
        $registeredShop = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $registeredShop = \Illuminate\Support\Facades\Auth::user()->shop;
        }
        
        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.profile', compact('registeredShop', 'cartCount'));
    }

    public function orders()
    {
        $orders = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())->latest()->get();
        $prescriptions = \App\Models\Prescription::where('user_id', \Illuminate\Support\Facades\Auth::id())->latest()->get();
        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.profile_orders', compact('orders', 'prescriptions', 'cartCount'));
    }

    public function addresses()
    {
        $cart = session('cart', []);
        $cartCount = array_sum($cart);
        $orders = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereNotNull('delivery_address')
            ->distinct()
            ->pluck('delivery_address');

        return view('customer.profile_addresses', compact('orders', 'cartCount'));
    }

    public function favourites()
    {
        $cart = session('cart', []);
        $cartCount = array_sum($cart);
        $shops = Shop::where('status', 'approved')->where('rating', '>=', 4.5)->limit(3)->get();

        return view('customer.profile_favourites', compact('shops', 'cartCount'));
    }

    public function notifications()
    {
        $cart = session('cart', []);
        $cartCount = array_sum($cart);
        $notifications = [
            ['title' => 'Order Status Update', 'text' => 'Aapka Dolo 650 ka order Sharma Medical ne accept kar liya hai! ⚡', 'time' => '10 mins ago'],
            ['title' => 'Cashback Added 💰', 'text' => 'Dawalo wallet check karein, new promo coupon applied.', 'time' => '2 hours ago']
        ];

        return view('customer.profile_notifications', compact('notifications', 'cartCount'));
    }

    public function settings()
    {
        $cart = session('cart', []);
        $cartCount = array_sum($cart);
        $user = \Illuminate\Support\Facades\Auth::user();

        return view('customer.profile_settings', compact('user', 'cartCount'));
    }

    public function help()
    {
        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.profile_help', compact('cartCount'));
    }

    public function setLocation(\Illuminate\Http\Request $request)
    {
        $city = $request->input('city', 'Muzaffarpur');
        session(['user_location' => $city]);
        return redirect()->back()->with('success', 'Location updated to ' . $city);
    }
}
