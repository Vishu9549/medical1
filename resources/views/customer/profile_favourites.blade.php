@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
      <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">❤️ Favourite Shops</h2>
    </div>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="display:flex; flex-direction:column; gap:14px;">
      
      @foreach($shops as $shop)
        <div style="background:#fff; border-radius:20px; padding:16px; border:1px solid #E2E8F0; box-shadow:0 4px 12px rgba(0,0,0,0.04); display:flex; gap:14px; align-items:center;">
          <div style="width:52px; height:52px; border-radius:14px; background:#FEE2E2; display:flex; align-items:center; justify-content:center; font-size:26px; flex-shrink:0;">🏪</div>
          <div style="flex:1;">
            <div style="font-weight:800; font-size:14px; color:#1A202C;">{{ $shop->name }}</div>
            <div style="font-size:11px; color:#64748B; margin-top:2px;">📍 {{ $shop->area }} • ★ {{ number_format($shop->rating, 1) }}</div>
          </div>
          <a href="{{ url('/search?shop_id='.$shop->id) }}" class="btn-blue" style="font-size:12px; padding:8px 14px;">Order</a>
        </div>
      @endforeach

    </div>
  </div>
</div>
@endsection
