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
      <div id="header-cart-badge" style="background:#fff; border-radius:14px; padding:8px 14px; display:{{ $cartCount > 0 ? 'flex' : 'none' }}; align-items:center; gap:6px;">
        <span>🛒</span>
        <strong style="font-weight:900; font-size:14px; color:#1A3C8F;">{{ $cartCount }}</strong>
      </div>
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
          $detailUrl = url('/medicine/'.$med->id.(!empty(request('shop_id')) ? '?shop_id='.request('shop_id') : ''));
        @endphp
        <div class="cart-item-card" style="border: {{ $qty > 0 ? '2px solid #BFDBFE' : '2px solid transparent' }};">
          <a href="{{ $detailUrl }}" style="display:flex; align-items:center; gap:12px; flex:1; text-decoration:none; text-align:left; color:inherit; overflow:hidden;">
            <div style="width:52px; height:52px; border-radius:14px; background:{{ $qty > 0 ? '#EEF2FF' : '#F8FAFF' }}; display:flex; align-items:center; justify-content:center; font-size:26px; flex-shrink:0; overflow:hidden;">
              @if(!empty($med->images))
                @php
                  $isMedAbsolute = strpos($med->images[0], 'http://') === 0 || strpos($med->images[0], 'https://') === 0;
                  $medImgUrl = $isMedAbsolute ? $med->images[0] : asset($med->images[0]);
                @endphp
                <img src="{{ $medImgUrl }}" style="width:100%; height:100%; object-fit:contain;">
              @else
                {{ $med->emoji }}
              @endif
            </div>
            <div style="flex:1; overflow:hidden;">
              <div style="font-weight:800; font-size:13px; color:#1A1A1A; margin-bottom:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $med->name }}</div>
              <div style="font-size:11px; color:#888; margin-bottom:4px;">{{ $med->category }}</div>
              <div style="display:flex; gap:5px; align-items:center;">
                <div style="font-size:14px; font-weight:900; color:#1A3C8F;">₹{{ $med->price }}</div>
                <div style="font-size:11px; color:#aaa; text-decoration:line-through;">₹{{ $med->mrp }}</div>
                <div style="background:#DCFCE7; color:#16A34A; font-size:9px; font-weight:800; padding:2px 6px; border-radius:5px;">{{ $disc }}% OFF</div>
              </div>
            </div>
          </a>
          <div class="cart-controls" data-med-id="{{ $med->id }}">
            <!-- Add Button Form (visible when qty == 0) -->
            <form action="{{ url('/cart/add') }}" method="POST" class="cart-form add-form-el" style="{{ $qty == 0 ? 'display:block;' : 'display:none;' }}">
              @csrf
              <input type="hidden" name="medicine_id" value="{{ $med->id }}">
              <button type="submit" class="btn-blue" style="font-size:12px; padding:8px 14px;">+ Add</button>
            </form>

            <!-- Quantity Update controls (visible when qty > 0) -->
            <div class="qty-control-el" style="{{ $qty > 0 ? 'display:flex;' : 'display:none;' }}; align-items:center; gap:7px;">
              <form action="{{ url('/cart/update') }}" method="POST" class="cart-form dec-form-el">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                <input type="hidden" name="qty" class="qty-input-dec" value="{{ $qty - 1 }}">
                <button type="submit" style="width:28px; height:28px; border-radius:8px; border:2px solid #E5E7EB; background:#fff; font-size:16px; font-weight:900; color:#1A3C8F; cursor:pointer; display:flex; align-items:center; justify-content:center;">−</button>
              </form>
              <div class="qty-display" style="font-weight:900; font-size:14px; color:#1A3C8F; min-width:14px; text-align:center;">{{ $qty }}</div>
              <form action="{{ url('/cart/update') }}" method="POST" class="cart-form inc-form-el">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                <input type="hidden" name="qty" class="qty-input-inc" value="{{ $qty + 1 }}">
                <button type="submit" style="width:28px; height:28px; border-radius:8px; background:#1A3C8F; border:none; font-size:16px; font-weight:900; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;">+</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Checkout Button Container -->
  <div id="smartcart-checkout-bar" style="background:#fff; border-top:1px solid #E5E7EB; padding:12px 16px 20px; flex-shrink:0; border-radius:14px; margin-top:16px; {{ $cartCount > 0 ? 'display:block;' : 'display:none;' }}">
    <a href="{{ url('/smartcart/results') }}" class="btn-blue" style="width:100%; padding:15px; background:linear-gradient(135deg,#1A3C8F,#2563EB); border:none; border-radius:14px; color:#fff; font-weight:900; font-size:15px; display:block; text-align:center; text-decoration:none;">
      🔍 Best Pharmacy Dhundho — <span id="checkout-item-count">{{ $cartCount }}</span> items →
    </a>
  </div>

  <!-- Floating Action Button (FAB) for quick checkout -->
  <a href="{{ url('/smartcart/results') }}" id="smartcart-fab" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; border-radius: 50%; width: 56px; height: 56px; display: {{ $cartCount > 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center; background: linear-gradient(135deg,#1A3C8F,#2563EB); color: #fff; font-size: 22px; box-shadow: 0 8px 24px rgba(37,99,235,0.4); text-decoration: none; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1.0)'">
    🛒
  </a>
</div>

<script>
  function attachCartSubmitHandlers(container) {
    container.querySelectorAll('.cart-form').forEach(form => {
      if (form.dataset.hasListener) return;
      form.dataset.hasListener = "true";

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
            const medControls = form.closest('.cart-controls');
            const cardEl = form.closest('.cart-item-card');
            
            const addForm = medControls.querySelector('.add-form-el');
            const qtyControl = medControls.querySelector('.qty-control-el');
            const qtyDisplay = medControls.querySelector('.qty-display');
            const qtyInputDec = medControls.querySelector('.qty-input-dec');
            const qtyInputInc = medControls.querySelector('.qty-input-inc');

            if (data.qty === 0) {
              addForm.style.display = 'block';
              qtyControl.style.display = 'none';
              if (cardEl) cardEl.style.borderColor = 'transparent';
            } else {
              addForm.style.display = 'none';
              qtyControl.style.display = 'flex';
              qtyDisplay.innerText = data.qty;
              qtyInputDec.value = data.qty - 1;
              qtyInputInc.value = data.qty + 1;
              if (cardEl) cardEl.style.borderColor = '#BFDBFE';
            }

            // Update checkout button count
            const checkoutBar = document.getElementById('smartcart-checkout-bar');
            const checkoutCountSpan = document.getElementById('checkout-item-count');
            const checkoutFab = document.getElementById('smartcart-fab');
            
            if (data.cartCount > 0) {
              if (checkoutBar) checkoutBar.style.display = 'block';
              if (checkoutCountSpan) checkoutCountSpan.innerText = data.cartCount;
              if (checkoutFab) checkoutFab.style.display = 'flex';
            } else {
              if (checkoutBar) checkoutBar.style.display = 'none';
              if (checkoutFab) checkoutFab.style.display = 'none';
            }

            // Update top header count badge
            let headerBadge = document.getElementById('header-cart-badge');
            if (headerBadge) {
              if (data.cartCount > 0) {
                headerBadge.style.display = 'flex';
                headerBadge.querySelector('strong').innerText = data.cartCount;
              } else {
                headerBadge.style.display = 'none';
              }
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          this.submit();
        });
      });
    });
  }

  window.addEventListener('DOMContentLoaded', () => {
    attachCartSubmitHandlers(document);
  });
</script>
@endsection
