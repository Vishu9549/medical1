@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Header -->
  @php
    $perfect = count($bestMatch['missing']) === 0;
  @endphp
  <div class="hdr-gradient" style="background:{{ $perfect ? 'linear-gradient(135deg,#059669,#10B981)' : 'linear-gradient(135deg,#1A3C8F,#2563EB)' }}; padding:16px 20px 18px; flex-shrink:0; border-radius:20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/smartcart') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div>
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">
          {{ $perfect ? '🎉 Perfect Match!' : '⭐ Best Available Match' }}
        </h2>
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">
          {{ count($bestMatch['available']) }}/{{ count($bestMatch['available']) + count($bestMatch['missing']) }} medicines found
        </p>
      </div>
    </div>
  </div>

  <div class="scroll" style="flex:1;">
    <div class="responsive-grid">
      
      <!-- Primary Pharmacy Match Card -->
      <div class="card" style="overflow:hidden; margin:0;">
        <div style="padding:18px;">
          <div style="font-weight:900; font-size:20px; color:#1A1A1A; margin-bottom:6px;">{{ $bestMatch['shop']->name }}</div>
          <div style="font-size:12px; color:#888; margin-bottom:16px;">
            📍 {{ $bestMatch['shop']->area }} • ★ {{ number_format($bestMatch['shop']->rating, 1) }} • {{ $bestMatch['shop']->distance_km }} km away
          </div>

          <!-- Items list inside this shop -->
          @foreach($cartItems as $item)
            @php
              $availItem = collect($bestMatch['available'])->firstWhere('id', $item->id);
            @endphp
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #F3F4F6;">
              <div style="display:flex; gap:10px; align-items:center;">
                <span style="font-size:22px;">{{ $item->emoji }}</span>
                <span style="font-weight:700; font-size:14px; color:#1A1A1A;">{{ $item->name }} (x{{ $cart[$item->id] }})</span>
              </div>
              <div>
                @if($availItem)
                  <span style="font-weight:800; font-size:15px; color:#1A3C8F;">₹{{ $availItem['shopPrice'] * $cart[$item->id] }}</span>
                @else
                  <span style="font-size:12px; color:#DC2626; font-weight:700;">❌ Nahi mili</span>
                @endif
              </div>
            </div>
          @endforeach

          <!-- Mode of Order Selection (Pickup vs Delivery) -->
          <form id="checkout-form" action="{{ url('/order') }}" method="POST" style="margin-top:20px;">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $bestMatch['shop']->id }}">
            
            <div style="font-weight:800; font-size:15px; color:#1A1A1A; margin-bottom:12px;">Kaise lenge aap? 🤔</div>
            <div style="display:flex; gap:12px; margin-bottom:20px;">
              
              <!-- Pickup Option -->
              <label style="flex:1; border:2px solid #E5E7EB; border-radius:16px; padding:16px 12px; text-align:center; cursor:pointer; background:#fff; position:relative; display:block;" class="mode-label" id="label-pickup">
                <input type="radio" name="mode" value="pickup" style="position:absolute; top:10px; right:10px;" required checked>
                <div style="font-size:28px; margin-bottom:6px;">🚶</div>
                <div style="font-weight:800; font-size:14px; color:#1A1A1A;">Khud Lunga</div>
                <div style="font-size:12px; color:#888; margin-top:2px;">Self Pickup</div>
                <div style="margin-top:8px; background:#DCFCE7; color:#16A34A; font-weight:800; font-size:13px; padding:4px 10px; border-radius:8px;">FREE</div>
              </label>

              <!-- Delivery Option -->
              @if($bestMatch['shop']->delivery_enabled)
                <label style="flex:1; border:2px solid #E5E7EB; border-radius:16px; padding:16px 12px; text-align:center; cursor:pointer; background:#fff; position:relative; display:block;" class="mode-label" id="label-delivery">
                  <input type="radio" name="mode" value="delivery" style="position:absolute; top:10px; right:10px;">
                  <div style="font-size:28px; margin-bottom:6px;">🛵</div>
                  <div style="font-weight:800; font-size:14px; color:#1A1A1A;">Ghar Pe Chahiye</div>
                  <div style="font-size:12px; color:#888; margin-top:2px;">Home Delivery</div>
                  <div style="margin-top:8px; background:#FEF3C7; color:#D97706; font-weight:800; font-size:13px; padding:4px 10px; border-radius:8px;">+₹{{ $bestMatch['deliveryCharge'] }}</div>
                </label>
              @endif
            </div>

            <!-- Bill Summary Summary -->
            <div style="background:#F8FAFF; border-radius:16px; padding:16px; margin-bottom:20px; border:1px solid #E0E7FF;">
              <div style="font-weight:800; font-size:14px; color:#1A1A1A; margin-bottom:12px;">💰 Bill Summary</div>
              
              <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:13px; color:#666;">Medicines Total</span>
                <span style="font-size:13px; font-weight:700; color:#1A1A1A;">₹{{ $bestMatch['totalPrice'] }}</span>
              </div>
              <div style="display:flex; justify-content:space-between; margin-bottom:8px;" id="delivery-row">
                <span style="font-size:13px; color:#666;">Delivery Charge</span>
                <span style="font-size:13px; font-weight:700; color:#16A34A;" id="delivery-charge-text">FREE (Self Pickup)</span>
              </div>
              <div style="border-top:1px dashed #CBD5E1; padding-top:10px; margin-top:4px; display:flex; justify-content:space-between;">
                <span style="font-size:15px; font-weight:800; color:#1A1A1A;">Total</span>
                <span style="font-size:15px; font-weight:900; color:#1A3C8F;" id="total-text">₹{{ $bestMatch['totalPrice'] }}</span>
              </div>
            </div>

            <button type="submit" class="btn-green" style="width:100%; padding:18px; border:none; border-radius:16px; font-weight:900; font-size:17px; cursor:pointer;">
              ✅ Order Karo — <span id="btn-total-text">₹{{ $bestMatch['totalPrice'] }}</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Missing items warning card -->
      @if(count($bestMatch['missing']) > 0)
        <div style="background:#FFFBEB; border-radius:16px; padding:14px 16px; border:1px solid #FDE68A; margin:0;">
          <div style="font-weight:800; font-size:13px; color:#92400E; margin-bottom:10px;">
            ⚠️ {{ count($bestMatch['missing']) }} medicine yahan nahi mili
          </div>
          @foreach($bestMatch['missing'] as $miss)
            <div style="background:#fff; border-radius:12px; padding:11px 14px; margin-bottom:8px; border:1px solid #FDE68A; display:flex; align-items:center; gap:8px;">
              <span style="font-size:18px;">{{ $miss->emoji }}</span>
              <div style="font-weight:800; font-size:13px; color:#1A1A1A;">{{ $miss->name }}</div>
              <div style="margin-left:auto; font-size:11px; color:#888;">Unavailable</div>
            </div>
          @endforeach
        </div>
      @endif

    </div>
  </div>
</div>

<script>
  // Dynamic pricing toggle between delivery/pickup in UI
  const priceMedicines = {{ $bestMatch['totalPrice'] }};
  const priceDelivery = {{ $bestMatch['deliveryCharge'] }};
  
  const labelPickup = document.getElementById('label-pickup');
  const labelDelivery = document.getElementById('label-delivery');
  const deliveryChargeText = document.getElementById('delivery-charge-text');
  const totalText = document.getElementById('total-text');
  const btnTotalText = document.getElementById('btn-total-text');

  function updatePricing(mode) {
    if (mode === 'delivery') {
      if (labelDelivery) labelDelivery.style.borderColor = '#1A3C8F';
      if (labelDelivery) labelDelivery.style.background = '#EEF2FF';
      if (labelPickup) labelPickup.style.borderColor = '#E5E7EB';
      if (labelPickup) labelPickup.style.background = '#fff';
      
      deliveryChargeText.innerText = '+₹' + priceDelivery;
      deliveryChargeText.style.color = '#D97706';
      totalText.innerText = '₹' + (priceMedicines + priceDelivery);
      btnTotalText.innerText = '₹' + (priceMedicines + priceDelivery);
    } else {
      if (labelPickup) labelPickup.style.borderColor = '#1A3C8F';
      if (labelPickup) labelPickup.style.background = '#EEF2FF';
      if (labelDelivery) labelDelivery.style.borderColor = '#E5E7EB';
      if (labelDelivery) labelDelivery.style.background = '#fff';
      
      deliveryChargeText.innerText = 'FREE (Self Pickup)';
      deliveryChargeText.style.color = '#16A34A';
      totalText.innerText = '₹' + priceMedicines;
      btnTotalText.innerText = '₹' + priceMedicines;
    }
  }

  document.querySelectorAll('input[name="mode"]').forEach(radio => {
    radio.addEventListener('change', function() {
      updatePricing(this.value);
    });
  });

  // Run once on load to set initial state correctly
  const checkedRadio = document.querySelector('input[name="mode"]:checked');
  if (checkedRadio) {
    updatePricing(checkedRadio.value);
  }
</script>
@endsection
