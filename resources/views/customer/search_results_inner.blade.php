<div style="background:#fff; border-radius: 18px; padding: 16px 0; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
  <div style="padding:0 16px 14px; border-bottom:1px solid #F3F4F6; font-size:13px; color:#555; font-weight:600; display:flex; justify-content:space-between; align-items:center;">
    <span>
      @if($medicines->count() > 0)
        {{ $medicines->count() }} results for "<span style="color:#1A3C8F; font-weight:800;">{{ $query ?: 'All Medicines' }}</span>"
      @else
        No results for "<strong>{{ $query }}</strong>"
      @endif
    </span>
    <button type="button" onclick="clearSearchAndRestoreHome()" style="background:#F3F4F6; color:#EF4444; border:none; padding:4px 10px; border-radius:8px; font-weight:700; font-size:11px; cursor:pointer;">Close Results ×</button>
  </div>

  <div class="responsive-grid" style="background: #fff; padding: 16px 14px 0;">
    @foreach($medicines as $idx => $med)
      @php
        $qty = $cart[$med->id] ?? 0;
        $disc = $med->mrp > 0 ? round((($med->mrp - $med->price) / $med->mrp) * 100) : 0;
        $detailUrl = url('/medicine/'.$med->id);
      @endphp
      <div class="med-row" style="background:{{ $qty > 0 ? '#FAFBFF' : '#fff' }}; border: 1px solid #E5E7EB; border-radius: 14px; margin-bottom: 12px; display:flex; padding:12px;">
        <a href="{{ $detailUrl }}" class="med-img" style="overflow:hidden; position:relative; display:flex; width:80px; height:80px; flex-shrink:0; border-radius:12px; background:#F8FAFF; align-items:center; justify-content:center; text-decoration:none; border:1px solid #E5E7EB;">
          @if(!empty($med->images))
            @php
              $isRelAbsolute = strpos($med->images[0], 'http://') === 0 || strpos($med->images[0], 'https://') === 0;
              $relImgUrl = $isRelAbsolute ? $med->images[0] : asset($med->images[0]);
            @endphp
            <img src="{{ $relImgUrl }}" referrerpolicy="no-referrer" style="width:100%; height:100%; object-fit:contain;">
          @else
            <div style="font-size:32px;">{{ $med->emoji }}</div>
          @endif
        </a>
        <div style="flex:1; padding-left:14px; display:flex; flex-direction:column; justify-content:space-between;">
          <a href="{{ $detailUrl }}" style="text-decoration:none; display:block;">
            <div style="font-weight:800; font-size:14px; color:#1A1A1A; line-height:1.3; margin-bottom:3px;">{{ $med->name }}</div>
            <div style="font-size:11px; color:#888; margin-bottom:4px;">{{ $med->category }} • {{ $med->package ?? '10 tablets' }}</div>
            <div style="font-size:11px; color:#7C3AED; font-weight:700; margin-bottom:4px;">⚡ Get in 45 mins</div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
              <span style="font-size:16px; font-weight:900; color:#1A1A1A;">₹{{ $med->price }}</span>
              <span style="font-size:12px; color:#aaa; text-decoration:line-through;">₹{{ $med->mrp }}</span>
              <span style="font-size:11px; color:#E05D2E; font-weight:800;">{{ $disc }}% off</span>
            </div>
          </a>
          
          <div class="cart-controls" data-med-id="{{ $med->id }}">
            @if($qty == 0)
              <form action="{{ url('/cart/add') }}" method="POST" class="cart-form">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                <button type="submit" class="add-btn" style="padding:6px 14px; font-size:11px; font-weight:800; border-radius:8px; background:#1A3C8F; color:#fff; border:none; cursor:pointer;">ADD</button>
              </form>
            @else
              <div class="qty-row" style="display:flex; align-items:center; gap:8px;">
                <form action="{{ url('/cart/update') }}" method="POST" class="cart-form">
                  @csrf
                  <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                  <input type="hidden" name="qty" value="{{ $qty - 1 }}">
                  <button type="submit" class="qty-btn" style="width:24px; height:24px; border-radius:50%; border:1px solid #1A3C8F; background:#fff; color:#1A3C8F; font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center;">−</button>
                </form>
                <div class="qty-num" style="font-weight:800; font-size:13px;">{{ $qty }}</div>
                <form action="{{ url('/cart/update') }}" method="POST" class="cart-form">
                  @csrf
                  <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                  <input type="hidden" name="qty" value="{{ $qty + 1 }}">
                  <button type="submit" class="qty-btn" style="width:24px; height:24px; border-radius:50%; border:1px solid #1A3C8F; background:#1A3C8F; color:#fff; font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center;">+</button>
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
