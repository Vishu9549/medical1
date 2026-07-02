@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
      <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">📋 My Orders</h2>
    </div>
  </div>

  <!-- Tab Menu -->
  <div style="display:flex; background:#fff; padding:6px; gap:6px; box-shadow:0 2px 10px rgba(0,0,0,0.04); border-radius:12px; margin-bottom:16px;">
    <button onclick="switchTab('med-orders')" id="tab-med-orders" style="flex:1; border:none; border-radius:10px; padding:10px; font-weight:800; font-size:12.5px; cursor:pointer; background:#1A3C8F; color:#fff; transition:all 0.2s;">
      🛒 Medicine Orders
    </button>
    <button onclick="switchTab('rx-orders')" id="tab-rx-orders" style="flex:1; border:none; border-radius:10px; padding:10px; font-weight:800; font-size:12.5px; cursor:pointer; background:#F3F4F6; color:#888; transition:all 0.2s;">
      📋 Prescriptions
    </button>
  </div>

  <div class="scroll" style="flex:1;">
    <!-- Medicine Orders Tab Content -->
    <div id="med-orders-list" style="display:flex; flex-direction:column; gap:14px;">
      @if($orders->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#888; background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,0.05);">
          <div style="font-size:48px; margin-bottom:10px;">📦</div>
          <div style="font-weight:800; font-size:16px;">Koi order nahi mila</div>
          <p style="font-size:12px; color:#aaa; margin-top:4px;">Smart Cart se first order place karein!</p>
          <a href="{{ url('/smartcart') }}" class="btn-blue" style="margin-top:14px; text-decoration:none; font-size:13px;">Smart Cart →</a>
        </div>
      @else
        @foreach($orders as $order)
          <div style="background:#fff; border-radius:20px; padding:16px; border:1px solid #E2E8F0; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
              <div>
                <span style="font-size:11px; color:#94A3B8; font-weight:800; text-transform:uppercase;">ORDER #{{ $order->id }}</span>
                <div style="font-weight:800; font-size:14px; color:#1A202C; margin-top:2px;">🏪 {{ $order->shop->name }}</div>
              </div>
              <span style="background:{{ $order->status === 'Delivered' ? '#DCFCE7' : ($order->status === 'Cancelled' ? '#FEE2E2' : '#EFF6FF') }}; color:{{ $order->status === 'Delivered' ? '#16A34A' : ($order->status === 'Cancelled' ? '#DC2626' : '#1D4ED8') }}; font-size:11px; font-weight:800; padding:4px 10px; border-radius:10px;">
                {{ $order->status }}
              </span>
            </div>

            <!-- Items -->
            <div style="border-top:1px solid #F1F5F9; border-bottom:1px solid #F1F5F9; padding:8px 0; margin-bottom:10px;">
              @foreach($order->items as $item)
                <div style="display:flex; justify-content:space-between; font-size:12.5px; color:#4A5568; padding:3px 0;">
                  <span>{{ $item['emoji'] ?? '💊' }} {{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                  <span style="font-weight:700; color:#2D3748;">₹{{ $item['price'] * $item['quantity'] }}</span>
                </div>
              @endforeach
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:12px; color:#94A3B8;">{{ $order->created_at->format('d M Y, h:i A') }}</span>
              <div style="font-weight:900; font-size:15px; color:#1A3C8F;">Total: ₹{{ $order->total_price + $order->delivery_charge }}</div>
            </div>
            
            @if($order->delivery_address)
              <div style="background:#F8FAFF; font-size:11px; color:#475569; padding:8px 12px; border-radius:8px; border:1px solid #E2E8F0; margin-top:10px;">
                📍 <strong>Delivery Address:</strong> {{ $order->delivery_address }}
              </div>
            @endif
          </div>
        @endforeach
      @endif
    </div>

    <!-- Prescription Orders Tab Content -->
    <div id="rx-orders-list" style="display:none; flex-direction:column; gap:14px;">
      @if($prescriptions->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#888; background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,0.05);">
          <div style="font-size:48px; margin-bottom:10px;">📋</div>
          <div style="font-weight:800; font-size:16px;">Prescriptions nahi mile</div>
          <p style="font-size:12px; color:#aaa; margin-top:4px;">Pehle prescription upload karein!</p>
          <a href="{{ url('/prescription/upload') }}" class="btn-blue" style="margin-top:14px; text-decoration:none; font-size:13px; background:#00B29A;">Upload Now ↑</a>
        </div>
      @else
        @foreach($prescriptions as $rx)
          <div style="background:#fff; border-radius:20px; padding:16px; border:1px solid #E2E8F0; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
              <div>
                <span style="font-size:11px; color:#94A3B8; font-weight:800; text-transform:uppercase;">PRESCRIPTION #RX-{{ str_pad($rx->id, 5, '0', STR_PAD_LEFT) }}</span>
                <div style="font-weight:800; font-size:14px; color:#1A202C; margin-top:2px;">🏪 {{ $rx->shop->name ?? 'Pharmacy' }}</div>
              </div>
              <span style="background:{{ $rx->status === 'Delivered' ? '#DCFCE7' : ($rx->status === 'Cancelled' ? '#FEE2E2' : '#EFF6FF') }}; color:{{ $rx->status === 'Delivered' ? '#16A34A' : ($rx->status === 'Cancelled' ? '#DC2626' : '#1D4ED8') }}; font-size:11px; font-weight:800; padding:4px 10px; border-radius:10px;">
                {{ $rx->status }}
              </span>
            </div>

            <!-- Items -->
            <div style="display:flex; gap:12px; align-items:center; border-top:1px solid #F1F5F9; border-bottom:1px solid #F1F5F9; padding:10px 0; margin-bottom:10px;">
              <a href="{{ $rx->image_path }}" target="_blank" style="flex-shrink:0;">
                <img src="{{ $rx->image_path }}" style="width:60px; height:60px; border-radius:8px; object-fit:cover; border:1px solid #E2E8F0;" alt="RX">
              </a>
              <div style="flex:1; font-size:12.5px; color:#4A5568;">
                <div><strong>Patient:</strong> {{ $rx->patient_name }} ({{ $rx->patient_age ?? 'N/A' }} Yrs)</div>
                <div style="font-size:11.5px; color:#888; margin-top:3px;">📍 Address: {{ Str::limit($rx->delivery_address, 45) }}</div>
              </div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:12px; color:#94A3B8;">{{ $rx->created_at->format('d M Y, h:i A') }}</span>
              <a href="{{ url('/prescription/'.$rx->id.'/success') }}" style="font-size:12.5px; font-weight:800; color:#00B29A; text-decoration:none;">View Details →</a>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>

<script>
  function switchTab(tab) {
    const medBtn = document.getElementById('tab-med-orders');
    const rxBtn = document.getElementById('tab-rx-orders');
    const medList = document.getElementById('med-orders-list');
    const rxList = document.getElementById('rx-orders-list');

    if (tab === 'med-orders') {
      medBtn.style.background = '#1A3C8F';
      medBtn.style.color = '#fff';
      rxBtn.style.background = '#F3F4F6';
      rxBtn.style.color = '#888';
      medList.style.display = 'flex';
      rxList.style.display = 'none';
    } else {
      rxBtn.style.background = '#1A3C8F';
      rxBtn.style.color = '#fff';
      medBtn.style.background = '#F3F4F6';
      medBtn.style.color = '#888';
      medList.style.display = 'none';
      rxList.style.display = 'flex';
    }
  }
</script>
@endsection
