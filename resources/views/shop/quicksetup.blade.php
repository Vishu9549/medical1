@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Shop Header -->
  <div class="hdr-gradient" style="padding:24px 20px 24px; position:relative; overflow:hidden; flex-shrink:0; border-radius: 20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; position:relative; z-index:1;">
      <a href="{{ url('/shop/dashboard') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">{{ $shop->name }}</h2>
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">Quick Setup Inventory Setup</p>
      </div>
    </div>
  </div>

  <!-- Dashboard Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 10px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/shop/dashboard') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📊</span>Overview
    </a>
    <a href="{{ url('/shop/quicksetup') }}" class="dash-tab active" style="background:#1A3C8F; color:#fff; flex:1; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
      <span style="font-size:16px;">⚡</span>Quick Setup
    </a>
    <a href="{{ url('/shop/inventory') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📦</span>Inventory
    </a>
    <a href="{{ url('/shop/orders') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📋</span>Orders
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="background:linear-gradient(135deg,#1A3C8F,#2563EB); border-radius:18px; padding:16px 18px; margin-bottom:16px; color:#fff;">
      <div style="font-weight:900; font-size:16px; margin-bottom:4px;">⚡ Quick Setup</div>
      <div style="font-size:12.5px; opacity:0.9; line-height:1.5;">Typing nahi karni — bas tick ✓ karo jo medicine aapke paas hai, price daalo, aur Save!</div>
    </div>

    <!-- Category Pills Filter -->
    @php
      $cats = ['All', 'Fever', 'Antibiotic', 'Allergy', 'Acidity', 'Pain', 'Diabetes', 'Heart', 'Supplement', 'Skin', 'Eye', 'Dental'];
      $activeCat = request('category', 'All');
    @endphp
    <div style="display:flex; gap:8px; margin-bottom:14px; overflow-x:auto; padding-bottom:4px;">
      @foreach($cats as $c)
        <a href="{{ url('/shop/quicksetup?category='.$c) }}" style="flex-shrink:0; padding:7px 16px; border-radius:20px; background:{{ $activeCat === $c ? '#1A3C8F' : '#F3F4F6' }}; color:{{ $activeCat === $c ? '#fff' : '#555' }}; font-weight:700; font-size:12px; text-decoration:none;">
          {{ $c }}
        </a>
      @endforeach
    </div>

    <!-- Form for Quick Setup inventory list -->
    <form action="{{ url('/shop/quicksetup') }}" method="POST">
      @csrf
      <input type="hidden" name="shop_id" value="{{ $shop->id }}">

      <div class="responsive-grid">
        @foreach($masterMedicines as $med)
          @php
            $hasInShop = $shopInventory->contains('medicine_id', $med->id);
            $shopPrice = $shopInventory->firstWhere('medicine_id', $med->id)?->price ?? $med->price;
          @endphp
          <div class="qs-card" style="border: {{ $hasInShop ? '2px solid #BBF7D0' : '2px solid transparent' }};">
            <input type="checkbox" name="qs_sel[m{{ $med->id }}][has]" value="true" style="width:20px; height:20px; cursor:pointer;" {{ $hasInShop ? 'checked' : '' }} class="qs-toggle">
            <div style="width:44px; height:44px; border-radius:12px; flex-shrink:0; background:{{ $hasInShop ? '#F0FDF4' : '#F8FAFF' }}; display:flex; align-items:center; justify-content:center; font-size:22px;">
              {{ $med->emoji }}
            </div>
            <div style="flex:1;">
              <div style="font-weight:800; font-size:13px; color:#1A1A1A;">{{ $med->name }}</div>
              <div style="font-size:11px; color:#888;">MRP ₹{{ $med->mrp }} • {{ $med->category }}</div>
            </div>
            <div style="flex-shrink:0; display:flex; align-items:center; gap:4px;">
              <span style="font-size:13px; color:#888;">₹</span>
              <input type="number" step="0.01" name="qs_sel[m{{ $med->id }}][price]" value="{{ $shopPrice }}" style="width:70px; padding:8px 6px; border:1.5px solid #E5E7EB; border-radius:8px; font-size:13px; font-weight:800; text-align:center; outline:none;" class="qs-price">
            </div>
          </div>
        @endforeach
      </div>

      <div style="height:16px;"></div>
      <button type="submit" class="btn-green" style="width:100%; padding:15px; font-weight:900; font-size:15px; box-shadow:0 4px 16px rgba(22,163,74,0.35); border:none;">
        💾 Save Inventory Stocks
      </button>
    </form>
  </div>
</div>

<script>
  // Dynamic border color highlights on checkbox toggle
  document.querySelectorAll('.qs-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const card = this.closest('.qs-card');
      if (this.checked) {
        card.style.borderColor = '#BBF7D0';
      } else {
        card.style.borderColor = 'transparent';
      }
    });
  });
</script>
@endsection
