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

    <!-- Multiple Category Filters -->
    <div style="margin-top: 14px; position: relative; z-index: 1;">
      <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: rgba(255,255,255,0.7); font-weight: 800; margin-bottom: 6px;">Filter Categories:</div>
      <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px;" class="hide-scrollbar">
        @foreach($allCategories as $cat)
          @php
            $isChecked = in_array($cat, $selectedCategories);
          @endphp
          <button type="button" onclick="toggleCategoryFilter('{{ $cat }}')" style="flex-shrink: 0; border: none; border-radius: 20px; padding: 6px 14px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s;
            {{ $isChecked ? 'background:#fff; color:#1A3C8F; box-shadow: 0 4px 10px rgba(0,0,0,0.15); font-weight:800;' : 'background:rgba(255,255,255,0.15); color:#fff;' }}">
            {{ $cat }}
          </button>
        @endforeach
      </div>
    </div>
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
            $detailUrl = url('/medicine/'.$med->id.(!empty(request('shop_id')) ? '?shop_id='.request('shop_id') : ''));
          @endphp
          <div class="med-row" style="background:{{ $qty > 0 ? '#FAFBFF' : '#fff' }}; border: 1px solid #E5E7EB; border-radius: 14px; margin-bottom: 0;">
            <a href="{{ $detailUrl }}" class="med-img" style="overflow:hidden; position:relative; display:flex;">
              @if(!empty($med->images))
                <img src="{{ asset($med->images[0]) }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                <div>{{ $med->emoji }}</div>
              @endif
              @if($idx < 2)
                <div class="bestseller" style="z-index: 2;">Bestseller ✦</div>
              @endif
            </a>
            <div style="flex:1; padding-left:14px; display:flex; flex-direction:column; justify-content:space-between;">
              <a href="{{ $detailUrl }}" style="text-decoration:none; display:block;">
                <div style="font-weight:800; font-size:15px; color:#1A1A1A; line-height:1.3; margin-bottom:3px;">{{ $med->name }}</div>
                <div style="font-size:12px; color:#888; margin-bottom:8px;">{{ $med->category }} • 10 tablets</div>
                <div style="font-size:12px; color:#7C3AED; font-weight:700; margin-bottom:8px;">⚡ Get in 45 mins</div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                  <span style="font-size:20px; font-weight:900; color:#1A1A1A;">₹{{ $med->price }}</span>
                  <span style="font-size:13px; color:#aaa; text-decoration:line-through;">₹{{ $med->mrp }}</span>
                  <span style="font-size:12px; color:#E05D2E; font-weight:800;">{{ $disc }}% off</span>
                </div>
              </a>
              
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
  function toggleCategoryFilter(category) {
    const urlParams = new URLSearchParams(window.location.search);
    let categories = urlParams.getAll('categories[]');
    
    if (categories.includes(category)) {
      categories = categories.filter(c => c !== category);
    } else {
      categories.push(category);
    }
    
    urlParams.delete('categories[]');
    categories.forEach(c => urlParams.append('categories[]', c));
    
    window.location.search = urlParams.toString();
  }

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
