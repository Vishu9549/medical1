@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Shop Dashboard Header -->
  <div class="hdr-gradient" style="padding:24px 20px 24px; position:relative; overflow:hidden; flex-shrink:0; border-radius: 20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">{{ $shop->name }}</h2>
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">📍 {{ $shop->area }} • Store Settings</p>
      </div>
    </div>

    <!-- Toggles (Online Status, Delivery) -->
    <div style="display:flex; gap:10px; position:relative; z-index:1;">
      <!-- Online/Offline Switch -->
      <form action="{{ url('/shop/toggle-online') }}" method="POST" style="flex:1;">
        @csrf
        <button type="submit" style="width:100%; border:none; text-align:left; background:none; padding:0; cursor:pointer; font-family:inherit;">
          @php
            $online = (bool) $shop->is_online;
            $bg = $online ? 'rgba(34,197,94,0.25)' : 'rgba(255,255,255,0.12)';
            $border = $online ? '1.5px solid rgba(34,197,94,0.6)' : '1.5px solid rgba(255,255,255,0.2)';
          @endphp
          <div style="border-radius:16px; padding:12px 14px; background:{{ $bg }}; border:{{ $border }}; display:flex; align-items:center; gap:10px;">
            <div style="width:36px; height:36px; border-radius:10px; background:{{ $online ? '#22C55E' : 'rgba(255,255,255,0.2)' }}; display:flex; align-items:center; justify-content:center; font-size:18px;">
              {{ $online ? '🟢' : '🔴' }}
            </div>
            <div>
              <div style="color:#fff; font-weight:900; font-size:13px;">{{ $online ? 'Shop Online' : 'Shop Offline' }}</div>
              <div style="color:rgba(255,255,255,0.7); font-size:10px; margin-top:1px;">{{ $online ? 'Orders aa rahe hain' : 'Orders band hain' }}</div>
            </div>
          </div>
        </button>
      </form>

      <!-- Delivery Switch -->
      <form action="{{ url('/shop/toggle-delivery') }}" method="POST" style="flex:1;">
        @csrf
        <button type="submit" style="width:100%; border:none; text-align:left; background:none; padding:0; cursor:pointer; font-family:inherit;">
          @php
            $deliv = (bool) $shop->delivery_enabled;
            $bg = $deliv ? 'rgba(37,99,235,0.3)' : 'rgba(255,255,255,0.12)';
            $border = $deliv ? '1.5px solid rgba(96,165,250,0.6)' : '1.5px solid rgba(255,255,255,0.2)';
          @endphp
          <div style="border-radius:16px; padding:12px 14px; background:{{ $bg }}; border:{{ $border }}; display:flex; align-items:center; gap:10px;">
            <div style="width:36px; height:36px; border-radius:10px; background:{{ $deliv ? '#2563EB' : 'rgba(255,255,255,0.2)' }}; display:flex; align-items:center; justify-content:center; font-size:18px;">
              🛵
            </div>
            <div>
              <div style="color:#fff; font-weight:900; font-size:13px;">Delivery {{ $deliv ? 'ON' : 'OFF' }}</div>
              <div style="color:rgba(255,255,255,0.7); font-size:10px; margin-top:1px;">{{ $deliv ? 'Home delivery active' : 'Sirf pickup' }}</div>
            </div>
          </div>
        </button>
      </form>
    </div>
  </div>

  <!-- Dashboard Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 10px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/shop/dashboard') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📊</span>Overview
    </a>
    <a href="{{ url('/shop/quicksetup') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">⚡</span>Quick Setup
    </a>
    <a href="{{ url('/shop/inventory') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📦</span>Inventory
    </a>
    <a href="{{ url('/shop/orders') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📋</span>Orders
    </a>
    <a href="{{ url('/shop/settings') }}" class="dash-tab active" style="background:#1A3C8F; color:#fff; flex:1; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
      <span style="font-size:16px;">⚙️</span>Settings
    </a>
  </div>

  <!-- Content scroll pane -->
  <div class="scroll" style="flex:1; padding-bottom:20px;">
    <div class="responsive-grid">
      
      <!-- Store Timings Settings Card -->
      <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid #F3F4F6; margin:0; grid-column: 1 / -1;">
        <h4 style="font-weight:900; font-size:14px; color:#1A3C8F; margin-top:0; margin-bottom:12px; display:flex; align-items:center; gap:6px;">🕰️ Store Timings</h4>
        <form action="{{ url('/shop/update-timings') }}" method="POST">
          @csrf
          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
              <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Opening Time</label>
              <input type="time" name="opens_at" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->opens_at ?? '09:00' }}" required>
            </div>
            <div style="flex:1;">
              <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Closing Time</label>
              <input type="time" name="closes_at" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->closes_at ?? '21:00' }}" required>
            </div>
          </div>
          <button type="submit" class="btn-blue" style="width:100%; border:none; padding:12px; border-radius:10px; font-weight:800; font-size:13px; color:#fff; cursor:pointer;">
            Save Store Timings
          </button>
        </form>
      </div>

      <!-- Delivery & Offer Settings Card -->
      <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid #F3F4F6; margin:0; grid-column: 1 / -1;">
        <h4 style="font-weight:900; font-size:14px; color:#1A3C8F; margin-top:0; margin-bottom:12px; display:flex; align-items:center; gap:6px;">⚙️ Delivery & Offer Settings</h4>
        <form action="{{ url('/shop/update-delivery-settings') }}" method="POST">
          @csrf
          
          <!-- Delivery Settings row -->
          <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:16px; border-bottom:1px solid #F3F4F6; padding-bottom:16px;">
            <div style="font-weight:800; font-size:12.5px; color:#374151;">🛵 Delivery Configuration</div>
            
            <div style="display:flex; gap:10px;">
              <div style="flex:1;">
                <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Delivery Radius (KM)</label>
                <input type="number" step="0.1" name="delivery_radius_km" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->delivery_radius_km ?? '10' }}" required>
              </div>
              <div style="flex:1;">
                <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Delivery Charge Type</label>
                <select name="delivery_charge_type" id="delivery_charge_type" class="form-input" style="padding:10px; font-size:13px; height:40px;" onchange="toggleDeliveryChargeInputs()" required>
                  <option value="fixed" {{ ($shop->delivery_charge_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Flat Rate</option>
                  <option value="dynamic" {{ ($shop->delivery_charge_type ?? 'dynamic') === 'dynamic' ? 'selected' : '' }}>Dynamic (Per KM)</option>
                </select>
              </div>
            </div>

            <div style="display:flex; gap:10px;">
              <div style="flex:1;" id="fixed-charge-wrapper">
                <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Fixed Delivery Charge (₹)</label>
                <input type="number" step="0.5" name="delivery_charge_fixed" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->delivery_charge_fixed ?? '20' }}">
              </div>
              <div style="flex:1;" id="per-km-charge-wrapper">
                <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Charge per KM (₹)</label>
                <input type="number" step="0.5" name="delivery_charge_per_km" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->delivery_charge_per_km ?? '8' }}">
              </div>
            </div>
          </div>

          <!-- Billing Offers row -->
          <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:16px;">
            <div style="font-weight:800; font-size:12.5px; color:#374151;">🎁 Discount Offers (Customer Savings)</div>
            
            <div style="display:flex; gap:10px;">
              <div style="flex:1;">
                <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Min Bill Amount (₹)</label>
                <input type="number" step="1" name="offer_min_bill" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->offer_min_bill ?? '0' }}" required>
              </div>
              <div style="flex:1;">
                <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Discount Percentage (%)</label>
                <input type="number" step="0.5" name="offer_discount_pct" class="form-input" style="padding:10px; font-size:13px;" min="0" max="100" value="{{ $shop->offer_discount_pct ?? '0' }}" required>
              </div>
            </div>
          </div>

          <button type="submit" class="btn-blue" style="width:100%; border:none; padding:12px; border-radius:10px; font-weight:800; font-size:13px; color:#fff; cursor:pointer;">
            Save Settings
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  function toggleDeliveryChargeInputs() {
    const typeSelect = document.getElementById('delivery_charge_type');
    const fixedWrapper = document.getElementById('fixed-charge-wrapper');
    const perKmWrapper = document.getElementById('per-km-charge-wrapper');
    
    if (typeSelect.value === 'fixed') {
      fixedWrapper.style.display = 'block';
      perKmWrapper.style.display = 'none';
    } else {
      fixedWrapper.style.display = 'none';
      perKmWrapper.style.display = 'block';
    }
  }

  // Trigger on load
  document.addEventListener('DOMContentLoaded', toggleDeliveryChargeInputs);
</script>
@endsection
