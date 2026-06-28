@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Minimalist Secure Checkout Header -->
  <div style="background: #1A202C; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; border-radius: 14px; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <a href="{{ url('/smartcart') }}" style="color: #fff; text-decoration: none; font-size: 16px;">← Smart Cart</a>
      <span style="color: #4A5568; font-size: 18px;">|</span>
      <span style="color: #CBD5E0; font-size: 13px; font-weight: 700; text-transform: uppercase;">Checkout</span>
    </div>
    <div style="color: #A0AEC0; font-size: 12px; display: flex; align-items: center; gap: 4px;">
      🔒 Secure Checkout
    </div>
  </div>

  <style>
    @media (min-width: 992px) {
      .checkout-grid {
        flex-direction: row !important;
        align-items: flex-start;
      }
    }
  </style>

  <div class="scroll" style="flex:1;">
    <form id="checkout-form" action="{{ url('/order') }}" method="POST">
      @csrf
      <input type="hidden" name="shop_id" value="{{ $bestMatch['shop']->id }}">

      <!-- Amazon Style 2-Column Grid -->
      <div class="checkout-grid" style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Left Side: Accordion Sections -->
        <div style="flex: 2; display: flex; flex-direction: column; gap: 16px;">
          
          <!-- SECTION 1: Delivery Mode (Kaise lenge aap?) -->
          <div style="background: #fff; border: 1px solid #D2D6DC; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
              <span style="background: #E2E8F0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; color: #4A5568;">1</span>
              <h3 style="font-weight: 900; font-size: 16px; color: #1A1A1A; margin: 0;">Select Delivery Mode</h3>
            </div>

            <div style="display: flex; gap: 12px;">
              <!-- Pickup Option -->
              <label style="flex: 1; border: 2.5px solid #1A3C8F; border-radius: 12px; padding: 14px 10px; text-align: center; cursor: pointer; background: #EEF2FF; position: relative; display: block; transition: all 0.2s;" class="mode-label" id="label-pickup">
                <input type="radio" name="mode" value="pickup" style="position: absolute; top: 10px; right: 10px; accent-color: #1A3C8F;" required checked>
                <div style="font-size: 24px; margin-bottom: 4px;">🚶</div>
                <div style="font-weight: 800; font-size: 13px; color: #1A1A1A;">Self Pickup</div>
                <div style="margin-top: 6px; background: #DCFCE7; color: #16A34A; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 6px; display: inline-block;">FREE</div>
              </label>

              <!-- Delivery Option -->
              @if($bestMatch['shop']->delivery_enabled)
                <label style="flex: 1; border: 1.5px solid #E5E7EB; border-radius: 12px; padding: 14px 10px; text-align: center; cursor: pointer; background: #fff; position: relative; display: block; transition: all 0.2s;" class="mode-label" id="label-delivery">
                  <input type="radio" name="mode" value="delivery" style="position: absolute; top: 10px; right: 10px; accent-color: #1A3C8F;">
                  <div style="font-size: 24px; margin-bottom: 4px;">🛵</div>
                  <div style="font-weight: 800; font-size: 13px; color: #1A1A1A;">Home Delivery</div>
                  <div style="margin-top: 6px; background: #FEF3C7; color: #D97706; font-weight: 800; font-size: 11px; padding: 2px 8px; border-radius: 6px; display: inline-block;">+₹{{ $bestMatch['deliveryCharge'] }}</div>
                </label>
              @endif
            </div>
          </div>

          <!-- SECTION 2: Shipping / Delivery Address (Shows only when Home Delivery selected) -->
          <div id="delivery-address-section" style="display: none; background: #fff; border: 1px solid #D2D6DC; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
              <span style="background: #E2E8F0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; color: #4A5568;">2</span>
              <h3 style="font-weight: 900; font-size: 16px; color: #1A1A1A; margin: 0;">Enter Delivery Address</h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
              <input type="text" name="address_name" class="form-input" placeholder="Full Name *" id="addr-name">
              <input type="text" name="address_line1" class="form-input" placeholder="Flat, House no., Building *" id="addr-line1">
              <input type="text" name="address_line2" class="form-input" placeholder="Area, Colony, Street, Sector *" id="addr-line2">
              <div style="display: flex; gap: 10px;">
                <input type="text" name="address_city" class="form-input" placeholder="Town/City *" value="Muzaffarpur" style="flex:1;" id="addr-city">
                <input type="text" name="address_pincode" class="form-input" placeholder="Pincode (6-digit) *" style="flex:1;" id="addr-pin">
              </div>
            </div>
          </div>

          <!-- SECTION 3: Review Items & Availability -->
          <div style="background: #fff; border: 1px solid #D2D6DC; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
              <span style="background: #E2E8F0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; color: #4A5568;">3</span>
              <h3 style="font-weight: 900; font-size: 16px; color: #1A1A1A; margin: 0;">Review Items & Pharmacy</h3>
            </div>

            <!-- Matched Pharmacy Details -->
            <div style="background: #F8FAFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
              <div>
                <div style="font-weight: 800; font-size: 14px; color: #1A1A1A;">🏪 {{ $bestMatch['shop']->name }}</div>
                <div style="font-size: 11px; color: #718096; margin-top: 2px;">📍 {{ $bestMatch['shop']->area }} • {{ $bestMatch['shop']->distance_km }} km away</div>
              </div>
              <span style="background: #DCFCE7; color: #15803D; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px;">Matched</span>
            </div>

            <!-- Items List -->
            @foreach($cartItems as $item)
              @php
                $availItem = collect($bestMatch['available'])->firstWhere('id', $item->id);
              @endphp
              <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #F3F4F6;">
                <div style="display: flex; gap: 10px; align-items: center;">
                  <span style="font-size: 20px;">{{ $item->emoji }}</span>
                  <span style="font-weight: 700; font-size: 13px; color: #2D3748;">{{ $item->name }} (x{{ $cart[$item->id] }})</span>
                </div>
                <div>
                  @if($availItem)
                    <span style="font-weight: 800; font-size: 14px; color: #2D3748;">₹{{ $availItem['shopPrice'] * $cart[$item->id] }}</span>
                  @else
                    <span style="font-size: 11px; color: #E53E3E; font-weight: 700;">❌ Unavailable</span>
                  @endif
                </div>
              </div>
            @endforeach

            <!-- Missing Items Warning -->
            @if(count($bestMatch['missing']) > 0)
              <div style="background: #FFFBEB; border-radius: 8px; padding: 10px 12px; border: 1px solid #FDE68A; margin-top: 14px;">
                <div style="font-weight: 800; font-size: 12px; color: #B45309; margin-bottom: 6px;">⚠️ {{ count($bestMatch['missing']) }} item(s) unavailable at this pharmacy</div>
                @foreach($bestMatch['missing'] as $miss)
                  <div style="font-size: 11px; color: #B45309;">• {{ $miss->name }}</div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <!-- Right Side: Order Summary (Amazon Style Sidebar) -->
        <div style="flex: 1; display: flex; flex-direction: column; gap: 16px;">
          <div style="background: #F7FAFC; border: 1.5px solid #CBD5E0; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
            <h3 style="font-weight: 900; font-size: 16px; color: #1D2D44; margin-top: 0; margin-bottom: 14px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">Order Summary</h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; color: #4A5568;">
              <span>Items Total:</span>
              <span style="font-weight: 700; color: #2D3748;">₹{{ $bestMatch['totalPrice'] }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: #4A5568;" id="summary-delivery-row">
              <span>Delivery Charges:</span>
              <span style="font-weight: 700; color: #16A34A;" id="summary-delivery-text">FREE</span>
            </div>
            
            <div style="border-top: 1px solid #CBD5E0; padding-top: 12px; display: flex; justify-content: space-between; margin-bottom: 20px;">
              <span style="font-size: 16px; font-weight: 900; color: #1A202C;">Order Total:</span>
              <span style="font-size: 18px; font-weight: 900; color: #B7791F;" id="summary-total-text">₹{{ $bestMatch['totalPrice'] }}</span>
            </div>

            <!-- Amazon Style Yellow Button -->
            <button type="submit" class="btn-green" style="background: linear-gradient(to bottom, #f7dfa5, #f0c14b); border: 1px solid #a88734; border-radius: 8px; color: #111; font-weight: 700; font-size: 14px; padding: 12px; width: 100%; cursor: pointer; box-shadow: 0 1px 0 rgba(255,255,255,.4) inset;">
              Place Your Order
            </button>
            <div style="font-size: 11px; text-align: center; color: #718096; margin-top: 10px;">
              By placing your order, you agree to our privacy notice and terms.
            </div>
          </div>
        </div>

      </div>
    </form>
  </div>
</div>

<script>
  const priceMedicines = {{ $bestMatch['totalPrice'] }};
  const priceDelivery = {{ $bestMatch['deliveryCharge'] }};
  
  const labelPickup = document.getElementById('label-pickup');
  const labelDelivery = document.getElementById('label-delivery');
  const deliveryAddressSection = document.getElementById('delivery-address-section');
  const summaryDeliveryText = document.getElementById('summary-delivery-text');
  const summaryTotalText = document.getElementById('summary-total-text');
  
  // Fields validation elements
  const addrName = document.getElementById('addr-name');
  const addrLine1 = document.getElementById('addr-line1');
  const addrLine2 = document.getElementById('addr-line2');
  const addrPin = document.getElementById('addr-pin');

  function updatePricing(mode) {
    if (mode === 'delivery') {
      if (labelDelivery) {
        labelDelivery.style.borderColor = '#1A3C8F';
        labelDelivery.style.background = '#EEF2FF';
      }
      if (labelPickup) {
        labelPickup.style.borderColor = '#E5E7EB';
        labelPickup.style.background = '#fff';
      }
      
      // Show Address
      deliveryAddressSection.style.display = 'block';
      addrName.required = true;
      addrLine1.required = true;
      addrLine2.required = true;
      addrPin.required = true;

      summaryDeliveryText.innerText = '₹' + priceDelivery;
      summaryDeliveryText.style.color = '#B7791F';
      summaryTotalText.innerText = '₹' + (priceMedicines + priceDelivery);
    } else {
      if (labelPickup) {
        labelPickup.style.borderColor = '#1A3C8F';
        labelPickup.style.background = '#EEF2FF';
      }
      if (labelDelivery) {
        labelDelivery.style.borderColor = '#E5E7EB';
        labelDelivery.style.background = '#fff';
      }
      
      // Hide Address
      deliveryAddressSection.style.display = 'none';
      addrName.required = false;
      addrLine1.required = false;
      addrLine2.required = false;
      addrPin.required = false;

      summaryDeliveryText.innerText = 'FREE';
      summaryDeliveryText.style.color = '#16A34A';
      summaryTotalText.innerText = '₹' + priceMedicines;
    }
  }

  if (labelPickup) {
    labelPickup.addEventListener('click', () => updatePricing('pickup'));
  }
  if (labelDelivery) {
    labelDelivery.addEventListener('click', () => updatePricing('delivery'));
  }
</script>
@endsection
