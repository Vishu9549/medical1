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

        $shops = Shop::where('status', 'approved')->get();
        $shopsCount = $shops->count();
        $onlineShopsCount = $shops->where('is_online', true)->count();

        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.home', compact('pills', 'categories', 'shops', 'shopsCount', 'onlineShopsCount', 'cart', 'cartCount'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $shopId = $request->input('shop_id');
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

        $medicines = $medQuery->get();
        $cart = session('cart', []);
        $cartCount = array_sum($cart);

        return view('customer.search', compact('medicines', 'cart', 'cartCount', 'query', 'selectedShop'));
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
}
