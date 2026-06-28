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
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">Inventory stock management</p>
      </div>
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
    <a href="{{ url('/shop/inventory') }}" class="dash-tab active" style="background:#1A3C8F; color:#fff; flex:1; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
      <span style="font-size:16px;">📦</span>Inventory
    </a>
    <a href="{{ url('/shop/orders') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📋</span>Orders
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    
    <!-- Action buttons & toggling manual add form -->
    <div style="display:flex; gap:10px; margin-bottom:14px;">
      <a href="{{ url('/shop/inventory?showAddForm=1') }}" class="btn-blue" style="flex:1; text-decoration:none; padding:14px; font-size:13px; font-weight:800; display:block; text-align:center;">➕ Manual Add</a>
      <a href="#" class="btn-outline" style="flex:1; text-decoration:none; padding:14px; font-size:13px; font-weight:800; display:block; text-align:center;">📤 Bulk Upload</a>
    </div>

    <!-- Manual Add Form -->
    @if(request('showAddForm'))
      <div style="background:#fff; border-radius:18px; padding:16px; box-shadow:0 4px 20px rgba(0,0,0,0.1); border:2px solid #BFDBFE; margin-bottom:14px; max-width:500px; margin-left:auto; margin-right:auto;">
        <h3 style="font-weight:800; font-size:14px; color:#1A1A1A; margin-bottom:12px;">➕ Add Medicine Manually</h3>
        
        <form action="{{ url('/shop/inventory/add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="shop_id" value="{{ $shop->id }}">

          <div style="margin-bottom:10px;">
            <input type="text" name="name" class="form-input" placeholder="Medicine ka Naam *" required>
          </div>
          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <input type="number" step="0.01" name="price" class="form-input" style="flex:1;" placeholder="Price ₹" required>
            <input type="number" name="qty" class="form-input" style="flex:1;" placeholder="Quantity" value="50" required>
          </div>
          <div style="margin-bottom:12px;">
            <label class="form-label">Upload Medicine Images</label>
            <input type="file" name="images[]" multiple class="form-input" accept="image/*">
          </div>
          <div style="display:flex; gap:10px;">
            <a href="{{ url('/shop/inventory') }}" class="btn-outline" style="flex:1; text-decoration:none; text-align:center; padding:11px; font-size:13px; color:#555; border-color:#E5E7EB;">Cancel</a>
            <button type="submit" class="btn-blue" style="flex:1; padding:11px; font-size:13px;">✅ Add Medicine</button>
          </div>
        </form>
      </div>
    @endif

    <!-- Inventory Stock Table/Grid -->
    @if($inventory->isEmpty())
      <div style="text-align:center; padding:40px 20px; color:#888;">
        <div style="font-size:40px; margin-bottom:10px;">📦</div>
        <div style="font-weight:700; font-size:15px;">Koi medicine nahi hai</div>
        <div style="font-size:12px; margin-top:4px;">Quick Setup se medicines tick karein ya manually add karein.</div>
      </div>
    @else
      <div class="responsive-grid">
        @foreach($inventory as $item)
          <div style="background:#fff; border-radius:16px; padding:12px; display:flex; gap:12px; align-items:center; box-shadow:0 2px 12px rgba(0,0,0,0.05); margin:0;">
            <div style="width:50px; height:50px; border-radius:12px; background:#F8FAFF; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; overflow:hidden; position:relative;">
              @if(!empty($item->images))
                <img src="{{ asset($item->images[0]) }}" style="width:100%; height:100%; object-fit:cover;">
              @elseif($item->medicine && !empty($item->medicine->images))
                <img src="{{ asset($item->medicine->images[0]) }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                {{ $item->medicine ? $item->medicine->emoji : '💊' }}
              @endif
            </div>
            <div style="flex:1;">
              <div style="font-weight:800; font-size:14px; color:#1A1A1A;">
                {{ $item->medicine ? $item->medicine->name : $item->name }}
              </div>
              <div style="display:flex; gap:10px; margin-top:2px;">
                <div style="font-size:12px; color:#1A3C8F; font-weight:700;">₹{{ $item->price }}</div>
                <div style="font-size:12px; color:#888;">Stock: {{ $item->quantity }}</div>
              </div>
              @php
                $imgList = !empty($item->images) ? $item->images : ($item->medicine && !empty($item->medicine->images) ? $item->medicine->images : []);
              @endphp
              @if(count($imgList) > 1)
                <div style="display:flex; gap:4px; margin-top:6px;">
                  @foreach($imgList as $img)
                    <img src="{{ asset($img) }}" style="width:24px; height:24px; border-radius:4px; object-fit:cover; border:1px solid #eee;">
                  @endforeach
                </div>
              @endif
            </div>
            <div>
              <form action="{{ url('/shop/inventory/delete/'.$item->id) }}" method="POST" onsubmit="return confirm('Dawai stock se hatayein?');">
                @csrf
                @method('DELETE')
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <button type="submit" style="background:#FEF2F2; border:none; border-radius:10px; width:34px; height:34px; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center;">
                  🗑️
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</div>
@endsection
