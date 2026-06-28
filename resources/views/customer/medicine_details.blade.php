@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- === HEADER === -->
  <div class="hdr-gradient" style="padding-bottom: 20px; display: flex; align-items: center; gap: 12px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>
    
    <a href="{{ !empty(request('shop_id')) ? url('/search?shop_id='.request('shop_id')) : url()->previous() }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
    <h2 style="color:#fff; font-size:20px; font-weight:800; margin:0; z-index:1; position:relative;">Medicine Details</h2>
  </div>

  <!-- === PRODUCT INFO === -->
  <div class="scroll" style="flex:1; padding:16px;">
    
    <!-- Image Gallery Carousel -->
    <div style="background:#fff; border-radius:24px; padding:16px; box-shadow:0 10px 30px rgba(0,0,0,0.05); margin-bottom:20px; display:flex; flex-direction:column; align-items:center; border:1px solid #E5E7EB; position:relative; overflow:hidden;">
      @if(!empty($medicine->images))
        <div style="width:100%; height:200px; display:flex; overflow-x:auto; scroll-snap-type:x mandatory; gap:10px; border-radius:16px;" id="detail-carousel" class="hide-scrollbar">
          @foreach($medicine->images as $img)
            <img src="{{ asset($img) }}" style="width:100%; height:100%; object-fit:cover; flex-shrink:0; scroll-snap-align:start; border-radius:16px;">
          @endforeach
        </div>
        @if(count($medicine->images) > 1)
          <div style="display:flex; gap:6px; margin-top:10px;">
            @foreach($medicine->images as $k => $img)
              <span style="width:8px; height:8px; border-radius:50%; background:#1A3C8F; opacity:{{ $k == 0 ? '1' : '0.3' }};"></span>
            @endforeach
          </div>
        @endif
      @else
        <div style="font-size:72px; padding:20px;">
          {{ $medicine->emoji }}
        </div>
      @endif
      
      @if($selectedShop)
        <div style="position:absolute; top:12px; left:12px; background:#EFF6FF; color:#1D4ED8; font-size:11px; font-weight:800; padding:6px 12px; border-radius:20px; border:1px solid #BFDBFE;">
          🏪 {{ $selectedShop->name }}
        </div>
      @endif
    </div>

    <!-- Medicine Core Information -->
    <div style="background:#fff; border-radius:24px; padding:20px; box-shadow:0 10px 30px rgba(0,0,0,0.05); margin-bottom:20px; border:1px solid #E5E7EB;">
      <span style="background:#EEF2F6; color:#475569; font-size:11px; font-weight:800; padding:4px 10px; border-radius:12px; text-transform:uppercase;">
        {{ $medicine->category }}
      </span>
      
      <h3 style="font-weight:900; font-size:22px; color:#1A1A1A; margin-top:10px; margin-bottom:4px; line-height:1.2;">
        {{ $medicine->name }}
      </h3>
      <p style="font-size:13px; color:#888; margin-bottom:16px;">Strip containing 10 tablets/capsules</p>

      <div style="display:flex; align-items:baseline; gap:12px; margin-bottom:20px;">
        <span style="font-size:28px; font-weight:900; color:#1A1A1A;">₹{{ $price }}</span>
        @if($medicine->mrp > $price)
          @php
            $disc = round((($medicine->mrp - $price) / $medicine->mrp) * 100);
          @endphp
          <span style="font-size:16px; color:#aaa; text-decoration:line-through;">₹{{ $medicine->mrp }}</span>
          <span style="font-size:13px; color:#E05D2E; font-weight:800; background:#FFF3F0; padding:2px 8px; border-radius:8px;">{{ $disc }}% OFF</span>
        @endif
      </div>

      <!-- Add to Cart Controls -->
      @php
        $qty = $cart[$medicine->id] ?? 0;
      @endphp
      <div class="cart-controls" data-med-id="{{ $medicine->id }}" style="margin-bottom:10px;">
        @if($qty == 0)
          <form action="{{ url('/cart/add') }}" method="POST" class="cart-form">
            @csrf
            <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
            <button type="submit" class="btn-blue" style="width:100%; padding:14px; font-size:14px; font-weight:800; border-radius:14px;">➕ ADD TO CART</button>
          </form>
        @else
          <div style="display:flex; align-items:center; background:#F1F5F9; border-radius:14px; padding:6px; justify-content:space-between;">
            <form action="{{ url('/cart/update') }}" method="POST" class="cart-form" style="width:30%;">
              @csrf
              <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
              <input type="hidden" name="qty" value="{{ $qty - 1 }}">
              <button type="submit" class="btn-outline" style="width:100%; border:none; background:#fff; padding:10px; font-weight:900; font-size:16px; color:#1A3C8F; border-radius:10px;">−</button>
            </form>
            
            <div style="font-weight:900; font-size:18px; color:#1A1A1A;">{{ $qty }}</div>
            
            <form action="{{ url('/cart/update') }}" method="POST" class="cart-form" style="width:30%;">
              @csrf
              <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
              <input type="hidden" name="qty" value="{{ $qty + 1 }}">
              <button type="submit" class="btn-outline" style="width:100%; border:none; background:#fff; padding:10px; font-weight:900; font-size:16px; color:#1A3C8F; border-radius:10px;">+</button>
            </form>
          </div>
        @endif
      </div>
    </div>

    <!-- Medicine Description/Details Details -->
    <div style="background:#fff; border-radius:24px; padding:20px; box-shadow:0 10px 30px rgba(0,0,0,0.05); margin-bottom:20px; border:1px solid #E5E7EB;">
      <h4 style="font-weight:800; font-size:14px; color:#1A1A1A; margin-bottom:10px; text-transform:uppercase; letter-spacing:0.05em;">Product Details</h4>
      
      <div style="font-size:13px; color:#555; line-height:1.6;">
        <div style="margin-bottom:8px;"><strong>Dosage:</strong> Take as directed by a healthcare professional. Do not exceed suggested usage instructions.</div>
        <div style="margin-bottom:8px;"><strong>Storage:</strong> Store in a cool, dry place away from direct sunlight. Keep out of reach of children.</div>
        <div><strong>Side Effects:</strong> If you experience any symptoms, stop usage immediately and consult your physician.</div>
      </div>
    </div>

    <!-- Related Medicines -->
    <div>
      <h3 style="font-weight:900; font-size:16px; color:#1A1A1A; margin-bottom:12px; padding-left:4px;">📋 Related Medicines</h3>
      <div style="display:flex; gap:12px; overflow-x:auto; padding-bottom:10px;" class="hide-scrollbar">
        @foreach($relatedMedicines as $rel)
          @php
            $relUrl = url('/medicine/'.$rel->id.(!empty(request('shop_id')) ? '?shop_id='.request('shop_id') : ''));
          @endphp
          <a href="{{ $relUrl }}" style="text-decoration:none; background:#fff; border:1px solid #E5E7EB; border-radius:18px; padding:12px; width:140px; flex-shrink:0; display:flex; flex-direction:column; box-shadow:0 4px 14px rgba(0,0,0,0.03);">
            <div style="height:70px; display:flex; align-items:center; justify-content:center; font-size:32px; background:#F8FAFF; border-radius:12px; margin-bottom:8px; overflow:hidden;">
              @if(!empty($rel->images))
                <img src="{{ asset($rel->images[0]) }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                {{ $rel->emoji }}
              @endif
            </div>
            <div style="font-weight:800; font-size:12px; color:#1A1A1A; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; height:34px; line-height:1.4;">
              {{ $rel->name }}
            </div>
            <div style="font-weight:900; font-size:14px; color:#1A3C8F; margin-top:6px;">₹{{ $rel->price }}</div>
          </a>
        @endforeach
      </div>
    </div>

  </div>

  <!-- Cart floating bar -->
  @if($cartCount > 0)
    <div class="cart-bar" style="position: sticky; bottom: 20px; z-index: 99; margin:0 16px 20px;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="background:#fff; border-radius:10px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; color:#1A3C8F;">
          {{ $cartCount }}
        </div>
        <div>
          <div style="color:#fff; font-weight:800; font-size:13px;">Cart mein {{ $cartCount }} item{{ $cartCount > 1 ? 's' : '' }}</div>
          <div style="color:rgba(255,255,255,0.7); font-size:11px;">Pharmacy auto-match hogi</div>
        </div>
      </div>
      <a href="{{ url('/smartcart') }}" class="btn-outline" style="background:#fff; color:#1A3C8F; border:none; padding:10px 16px; font-size:13px; text-decoration:none;">Checkout →</a>
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
