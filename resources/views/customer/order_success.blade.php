@extends('layouts.app')

@section('content')
<div class="screen" style="align-items:center; justify-content:center; padding:30px 20px; background:#fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
  
  <div style="width:100px; height:100px; border-radius:50%; background:#DCFCE7; display:flex; align-items:center; justify-content:center; font-size:48px; margin-bottom:20px; box-shadow:0 0 0 16px rgba(34,197,94,0.1);">
    ✅
  </div>
  
  <h2 style="font-weight:900; font-size:22px; color:#1A1A1A; margin-bottom:6px; text-align:center;">Order Ho Gaya! 🎉</h2>
  <p style="font-size:14px; color:#888; margin-bottom:24px; text-align:center;">
    {{ $order->shop->name }} ko request bheji ja rahi hai...
  </p>
  
  <div class="responsive-grid" style="width: 100%; max-width: 500px;">
    <!-- Items list card -->
    <div class="card" style="background:#F9FAFB; border-radius:20px; padding:20px; width:100%; box-shadow:0 2px 8px rgba(0,0,0,0.02); border:1px solid #E5E7EB; margin-bottom:0;">
      @foreach($order->items as $item)
        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
          <div style="font-size:13px; color:#555;">{{ $item['emoji'] ?? '💊' }} {{ $item['name'] }} (x{{ $item['quantity'] ?? $item['qty'] ?? 1 }})</div>
          <div style="font-size:13px; font-weight:700; color:#1A3C8F;">₹{{ ($item['price'] ?? 0) * ($item['quantity'] ?? $item['qty'] ?? 1) }}</div>
        </div>
      @endforeach
      
      <div style="border-top:1px dashed #E5E7EB; padding-top:10px; margin-top:6px; display:flex; flex-direction:column; gap:4px;">
        <div style="display:flex; justify-content:space-between; font-size:12px; color:#555;">
          <span>Items Subtotal:</span>
          <span>₹{{ $order->total_price + $order->discount_amount }}</span>
        </div>
        @if(($order->discount_amount ?? 0) > 0)
          <div style="display:flex; justify-content:space-between; font-size:12px; color:#16A34A; font-weight:700;">
            <span>Bill Discount:</span>
            <span>-₹{{ $order->discount_amount }}</span>
          </div>
        @endif
        @if(($order->delivery_charge ?? 0) > 0)
          <div style="display:flex; justify-content:space-between; font-size:12px; color:#555;">
            <span>Delivery Charges:</span>
            <span>+₹{{ $order->delivery_charge }}</span>
          </div>
        @endif
        <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:800; border-top:1px solid #E5E7EB; padding-top:4px; margin-top:2px;">
          <span>Grand Total:</span>
          <span style="font-weight:900; color:#1A3C8F;">₹{{ $order->total_price + $order->delivery_charge }}</span>
        </div>
      </div>
    </div>

    <!-- Wait box info -->
    <div style="background:#EEF2FF; border-radius:16px; padding:14px 20px; width:100%; text-align:center; border: 1px solid #E0E7FF;">
      <div style="font-size:13px; color:#1A3C8F; font-weight:700;">⏳ Dukandaar ke accept karne ka wait karein...</div>
      <div style="font-size:12px; color:#888; margin-top:4px;">Usually 2-3 minutes mein respond karte hain</div>
    </div>
  </div>

  <a href="{{ url('/') }}" class="btn-blue" style="width:100%; max-width: 320px; padding:16px; border:none; border-radius:16px; color:#fff; font-weight:800; font-size:15px; text-decoration:none; display:inline-block; text-align:center; margin-top: 20px;">
    ← Wapas Home Pe Jaayein
  </a>
</div>
@endsection
