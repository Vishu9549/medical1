@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- === HEADER === -->
  <div class="hdr-gradient" style="padding-bottom: 30px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="margin-bottom:12px; position:relative; z-index:1; display:flex; align-items:center; gap:12px;">
      <a href="{{ url('/') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <h2 style="color:#fff; font-size:22px; font-weight:800;">Search Results</h2>
    </div>

    <!-- Search Form -->
    <form action="{{ url('/search') }}" method="GET" class="search-box" style="position:relative; z-index:1;">
      @if(request('shop_id'))
        <input type="hidden" name="shop_id" value="{{ request('shop_id') }}">
      @endif
      <input name="q" class="search-input" placeholder="Medicine ka naam likhein..." type="text" value="{{ $query }}" id="search-q">
      <button type="submit" class="search-btn">🔍 Search</button>
    </form>
  </div>

  <!-- === RESULTS === -->
  <div class="scroll" style="flex:1; padding-bottom:8px;">
    <div style="background:#fff; border-radius: 18px; padding: 16px 0; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
      <div style="padding:0 16px 14px; border-bottom:1px solid #F3F4F6; font-size:13px; color:#555; font-weight:600;">
        @if($medicines->count() > 0)
          {{ $medicines->count() }} results for "<span style="color:#1A3C8F; font-weight:800;">{{ $query ?: 'All Medicines' }}</span>"
          @if($selectedShop)
            at <strong style="color:#1A3C8F;">{{ $selectedShop->name }}</strong>
          @endif
        @else
          No results for "<strong>{{ $query }}</strong>"
        @endif
      </div>

      <div class="responsive-grid" style="background: #fff; padding: 16px 14px 0;">
        @foreach($medicines as $idx => $med)
          @php
            $qty = $cart[$med->id] ?? 0;
            $disc = $med->mrp > 0 ? round((($med->mrp - $med->price) / $med->mrp) * 100) : 0;
          @endphp
          <div class="med-row" style="background:{{ $qty > 0 ? '#FAFBFF' : '#fff' }}; border: 1px solid #E5E7EB; border-radius: 14px; margin-bottom: 0;">
            <div class="med-img" style="overflow:hidden; position:relative;">
              @if(!empty($med->images))
                <img src="{{ asset($med->images[0]) }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                <div>{{ $med->emoji }}</div>
              @endif
              @if($idx < 2)
                <div class="bestseller">Bestseller ✦</div>
              @endif
            </div>
            <div style="flex:1; padding-left:14px; display:flex; flex-direction:column; justify-content:space-between;">
              <div>
                <div style="font-weight:800; font-size:15px; color:#1A1A1A; line-height:1.3; margin-bottom:3px;">{{ $med->name }}</div>
                <div style="font-size:12px; color:#888; margin-bottom:8px;">{{ $med->category }} • 10 tablets</div>
                <div style="font-size:12px; color:#7C3AED; font-weight:700; margin-bottom:8px;">⚡ Get in 45 mins</div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                  <span style="font-size:20px; font-weight:900; color:#1A1A1A;">₹{{ $med->price }}</span>
                  <span style="font-size:13px; color:#aaa; text-decoration:line-through;">₹{{ $med->mrp }}</span>
                  <span style="font-size:12px; color:#E05D2E; font-weight:800;">{{ $disc }}% off</span>
                </div>
              </div>
              
              <div class="cart-controls" data-med-id="{{ $med->id }}">
                @if($qty == 0)
                  <form action="{{ url('/cart/add') }}" method="POST" class="cart-form">
                    @csrf
                    <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                    <button type="submit" class="add-btn">ADD</button>
                  </form>
                @else
                  <div class="qty-row">
                    <form action="{{ url('/cart/update') }}" method="POST" class="cart-form" style="flex:1;">
                      @csrf
                      <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                      <input type="hidden" name="qty" value="{{ $qty - 1 }}">
                      <button type="submit" class="qty-btn">−</button>
                    </form>
                    <div class="qty-num">{{ $qty }}</div>
                    <form action="{{ url('/cart/update') }}" method="POST" class="cart-form" style="flex:1;">
                      @csrf
                      <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                      <input type="hidden" name="qty" value="{{ $qty + 1 }}">
                      <button type="submit" class="qty-btn">+</button>
                    </form>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>

      @if($medicines->count() == 0)
        <div style="text-align:center; padding:40px 20px; color:#888;">
          <div style="font-size:40px; margin-bottom:10px;">😔</div>
          <div style="font-weight:700; font-size:16px; margin-bottom:6px;">Medicine nahi mili</div>
          <div style="font-size:13px;">Prescription upload karein — hum dhundh denge</div>
        </div>
      @endif
    </div>
  </div>

  <!-- Cart floating bar -->
  @if($cartCount > 0)
    <div class="cart-bar" style="position: sticky; bottom: 20px; z-index: 99;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="background:#fff; border-radius:10px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; color:#1A3C8F;">
          {{ $cartCount }}
        </div>
        <div>
          <div style="color:#fff; font-weight:800; font-size:13px;">Cart mein {{ $cartCount }} item{{ $cartCount > 1 ? 's' : '' }}</div>
          <div style="color:rgba(255,255,255,0.7); font-size:11px;">Pharmacy auto-match hogi</div>
        </div>
      </div>
      <a href="{{ url('/smartcart') }}" class="btn-outline" style="background:#fff; color:#1A3C8F; border:none; padding:10px 16px; font-size:13px;">Checkout →</a>
    </div>
  @endif
</div>

<script>
  // AJAX enhancement for premium experience
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
          window.location.reload(); // Reload to update navbar/footer cart badge and lists smoothly
        }
      })
      .catch(error => {
        console.error('Error:', error);
        this.submit(); // fallback to standard submit
      });
    });
  });
</script>
@endsection
