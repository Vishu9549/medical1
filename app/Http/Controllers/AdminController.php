<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Medicine;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $shopsCount = Shop::count();
        $approvedShopsCount = Shop::where('status', 'approved')->count();
        $pendingApprovalsCount = Shop::where('status', 'pending')->count();
        $blockedShopsCount = Shop::where('status', 'blocked')->count();
        
        $revenue = Order::sum('total_price');

        // Retrieve top 3 performing shops with order sums
        $topShops = Shop::withCount('orders')
            ->withSum('orders', 'total_price')
            ->orderBy('orders_count', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'shopsCount', 'approvedShopsCount', 'pendingApprovalsCount', 'blockedShopsCount',
            'revenue', 'topShops'
        ));
    }

    public function stores()
    {
        $stores = Shop::withCount('orders')->withSum('orders', 'total_price')->get();
        $pendingApprovalsCount = Shop::where('status', 'pending')->count();

        return view('admin.stores', compact('stores', 'pendingApprovalsCount'));
    }

    public function storesStatus(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer',
            'status' => 'required|string'
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $shop->status = $request->status;
        $shop->save();

        return redirect()->back()->with('success', 'Shop status updated to ' . $request->status);
    }

    public function approvals()
    {
        $pendingApprovals = Shop::where('status', 'pending')->get();
        $pendingApprovalsCount = $pendingApprovals->count();

        return view('admin.approvals', compact('pendingApprovals', 'pendingApprovalsCount'));
    }

    public function medicines()
    {
        $medicines = Medicine::all();
        $pendingApprovalsCount = Shop::where('status', 'pending')->count();

        return view('admin.medicines', compact('medicines', 'pendingApprovalsCount'));
    }

    public function medicinesAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'mrp' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $emojis = [
            'Fever' => '🌡️',
            'Antibiotic' => '💊',
            'Allergy' => '🤧',
            'Acidity' => '🔵',
            'Pain' => '🩹',
            'Diabetes' => '💉',
            'Heart' => '❤️',
            'Supplement' => '🍊',
            'Skin' => '🧴',
            'Eye' => '👁️',
            'Dental' => '🦷'
        ];

        $emoji = $emojis[$request->category] ?? '💊';

        Medicine::create([
            'name' => $request->name,
            'category' => $request->category,
            'emoji' => $emoji,
            'mrp' => $request->mrp,
            'price' => $request->price
        ]);

        return redirect('/admin/medicines')->with('success', 'Master medicine added successfully!');
    }

    public function medicinesDelete($id)
    {
        Medicine::destroy($id);

        return redirect('/admin/medicines')->with('success', 'Master medicine deleted.');
    }

    public function commission()
    {
        $commRate = (int) Setting::getVal('comm_rate', 2);
        $commOn = Setting::getVal('comm_on', 'true') === 'true';

        $wallets = Wallet::with('shop')->get();
        $pendingApprovalsCount = Shop::where('status', 'pending')->count();

        return view('admin.commission', compact('commRate', 'commOn', 'wallets', 'pendingApprovalsCount'));
    }

    public function commissionUpdate(Request $request)
    {
        Setting::setVal('comm_on', $request->has('comm_on') ? 'true' : 'false');
        Setting::setVal('comm_rate', $request->input('comm_rate', 2));

        return redirect('/admin/commission')->with('success', 'Commission settings updated successfully!');
    }
}
