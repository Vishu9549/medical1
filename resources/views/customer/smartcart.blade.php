@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px; position:relative; z-index:1;">
      <a href="{{ url('/') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">🛒 Smart Cart</h2>
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Best pharmacy auto-match hogi</p>
      </div>
      @if($cartCount > 0)
        <div style="background:#fff; border-radius:14px; padding:8px 14px; display:flex; align-items:center; gap:6px;">
          <span>🛒</span>
          <strong style="font-weight:900; font-size:14px; color:#1A3C8F;">{{ $cartCount }}</strong>
        </div>
      @endif
    </div>

    <!-- Search in Smart Cart -->
    <form action="{{ url('/smartcart') }}" method="GET" class="search-box" style="position:relative; z-index:1;">
      <input name="q" class="search-input" placeholder="Medicine ya category likhein..." type="text" value="{{ $query }}">
      <button type="submit" class="search-btn">Filter</button>
    </form>
  </div>

  <!-- Medicine Catalog Selection -->
  <div class="scroll" style="flex:1;">
    <div class="responsive-grid">
      @foreach($catalog as $med)
        @php
          $qty = $cart[$med->id] ?? 0;
          $disc = $med->mrp > 0 ? round((($med->mrp - $med->price) / $med->mrp) * 100) : 0;
        @endphp
        <div class="cart-item-card" style="border: {{ $qty > 0 ? '2px solid #BFDBFE' : '2px solid transparent' }};">
          <div style="width:52px; height:52px; border-radius:14px; background:{{ $qty > 0 ? '#EEF2FF' : '#F8FAFF' }}; display:flex; align-items:center; justify-content:center; font-size:26px; flex-shrink:0;">
            {{ $med->emoji }}
          </div>
          <div style="flex:1;">
            <div style="font-weight:800; font-size:13px; color:#1A1A1A; margin-bottom:2px;">{{ $med->name }}</div>
            <div style="font-size:11px; color:#888; margin-bottom:4px;">{{ $med->category }}</div>
            <div style="display:flex; gap:5px; align-items:center;">
              <div style="font-size:14px; font-weight:900; color:#1A3C8F;">₹{{ $med->price }}</div>
              <div style="font-size:11px; color:#aaa; text-decoration:line-through;">₹{{ $med->mrp }}</div>
              <div style="background:#DCFCE7; color:#16A34A; font-size:9px; font-weight:800; padding:2px 6px; border-radius:5px;">{{ $disc }}% OFF</div>
            </div>
          </div>
          <div class="cart-controls" data-med-id="{{ $med->id }}">
            @if($qty == 0)
              <form action="{{ url('/cart/add') }}" method="POST" class="cart-form">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                <button type="submit" class="btn-blue" style="font-size:12px; padding:8px 14px;">+ Add</button>
              </form>
            @else
              <div style="display:flex; align-items:center; gap:7px;">
                <form action="{{ url('/cart/update') }}" method="POST" class="cart-form">
                  @csrf
                  <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                  <input type="hidden" name="qty" value="{{ $qty - 1 }}">
                  <button type="submit" style="width:28px; height:28px; border-radius:8px; border:2px solid #E5E7EB; background:#fff; font-size:16px; font-weight:900; color:#1A3C8F; cursor:pointer; display:flex; align-items:center; justify-content:center;">−</button>
                </form>
                <div style="font-weight:900; font-size:14px; color:#1A3C8F; min-width:14px; text-align:center;">{{ $qty }}</div>
                <form action="{{ url('/cart/update') }}" method="POST" class="cart-form">
                  @csrf
                  <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                  <input type="hidden" name="qty" value="{{ $qty + 1 }}">
                  <button type="submit" style="width:28px; height:28px; border-radius:8px; background:#1A3C8F; border:none; font-size:16px; font-weight:900; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;">+</button>
                </form>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>

  @if($cartCount > 0)
    <div style="background:#fff; border-top:1px solid #E5E7EB; padding:12px 16px 20px; flex-shrink:0; border-radius:14px; margin-top:16px;">
      <a href="{{ url('/smartcart/results') }}" class="btn-blue" style="width:100%; padding:15px; background:linear-gradient(135deg,#1A3C8F,#2563EB); border:none; border-radius:14px; color:#fff; font-weight:900; font-size:15px; display:block; text-align:center; text-decoration:none;">
        🔍 Best Pharmacy Dhundho — {{ $cartCount }} items →
      </a>
    </div>
  @endif
</div>

<script>
  document.querySelectorAll('.cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const url = this.getAttribute('action');
      const formData = new FormData(this);

      fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        this.submit();
      });
    });
  });
</script>
@endsection
