<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function uploadForm()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Pehle login karein prescription upload karne ke liye.');
        }

        // Get past addresses from orders to auto-fill as suggestions
        $pastAddresses = \App\Models\Order::where('user_id', Auth::id())
            ->whereNotNull('delivery_address')
            ->distinct()
            ->pluck('delivery_address');

        // Get all approved shops
        $shops = Shop::where('status', 'approved')->orderBy('name', 'asc')->get();

        return view('customer.prescription.upload', compact('pastAddresses', 'shops'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $request->validate([
            'prescription_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            'shop_id' => 'required|integer|exists:shops,id',
            'patient_name' => 'required|string|max:100',
            'patient_age' => 'nullable|integer|min:1|max:120',
            'patient_phone' => 'required|string|max:15',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Upload prescription image
        $imagePath = '';
        if ($request->hasFile('prescription_image')) {
            $image = $request->file('prescription_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/prescriptions'), $filename);
            $imagePath = '/uploads/prescriptions/' . $filename;
        }

        $prescription = Prescription::create([
            'user_id' => Auth::id(),
            'shop_id' => $request->shop_id,
            'image_path' => $imagePath,
            'patient_name' => $request->patient_name,
            'patient_age' => $request->patient_age,
            'patient_phone' => $request->patient_phone,
            'delivery_address' => $request->delivery_address,
            'notes' => $request->notes,
            'status' => 'Pending'
        ]);

        return redirect('/prescription/' . $prescription->id . '/success')->with('success', 'Prescription uploaded successfully!');
    }

    public function success($id)
    {
        $prescription = Prescription::findOrFail($id);

        if (Auth::id() !== $prescription->user_id) {
            abort(403, 'Aapko is prescription record ko dekhne ki permission nahi hai.');
        }

        return view('customer.prescription.success', compact('prescription'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|integer',
            'status' => 'required|string'
        ]);

        // Verify that the shop owner has permissions for this shop
        $shop = null;
        if (Auth::check() && Auth::user()->role === 'shop_owner') {
            $shop = Auth::user()->shop;
        }

        if (!$shop) {
            return redirect('/profile')->with('error', 'Shop owner panel access needed.');
        }

        $prescription = Prescription::where('id', $request->prescription_id)
            ->where('shop_id', $shop->id)
            ->firstOrFail();

        $prescription->status = $request->status;
        $prescription->save();

        return redirect()->back()->with('success', 'Prescription status updated to ' . $request->status);
    }
}
