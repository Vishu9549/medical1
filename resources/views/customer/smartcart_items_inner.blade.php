@foreach($catalog as $med)
  @php
    $qty = $cart[$med->id] ?? 0;
    $disc = $med->mrp > 0 ? round((($med->mrp - $med->price) / $med->mrp) * 100) : 0;
    $detailUrl = url('/medicine/'.$med->id.(!empty(request('shop_id')) ? '?shop_id='.request('shop_id') : ''));
  @endphp
  <div class="cart-item-card catalog-item-row" data-name="{{ strtolower($med->name) }}" data-category="{{ strtolower($med->category) }}" style="border: {{ $qty > 0 ? '2px solid #BFDBFE' : '2px solid transparent' }};">
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
          <button type="submit" style="width:28px; height:28px; background:#1A3C8F; border:none; font-size:16px; font-weight:900; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;">+</button>
        </form>
      </div>
    </div>
  </div>
@endforeach
