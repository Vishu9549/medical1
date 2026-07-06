@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Shop Header -->
  <div class="hdr-gradient" style="padding:24px 20px 24px; position:relative; overflow:hidden; flex-shrink:0; border-radius: 20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; position:relative; z-index:1;">
      <a href="{{ url('/shop/dashboard') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">{{ $shop->name }}</h2>
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">Received Orders Queue</p>
      </div>
    </div>
  </div>

  <!-- Dashboard Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 10px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/shop/dashboard') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📊</span>Overview
    </a>
    <a href="{{ url('/shop/inventory') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📦</span>Inventory
    </a>
    <a href="{{ url('/shop/orders') }}" class="dash-tab active" style="background:#1A3C8F; color:#fff; flex:1; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
      <span style="font-size:16px;">📋</span>Orders
    </a>
    <a href="{{ url('/shop/settings') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">⚙️</span>Settings
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    @php
      $statusColors = [
        'Pending' => ['bg' => '#FEF3C7', 'color' => '#D97706'],
        'Accepted' => ['bg' => '#DBEAFE', 'color' => '#2563EB'],
        'Delivered' => ['bg' => '#DCFCE7', 'color' => '#16A34A'],
        'Cancelled' => ['bg' => '#FEE2E2', 'color' => '#DC2626']
      ];
    @endphp

    @if($orders->isEmpty())
      <div style="text-align:center; padding:40px 20px; color:#888;">
        <div style="font-size:40px; margin-bottom:10px;">📋</div>
        <div style="font-weight:700; font-size:15px;">Abhi koi order nahi hai</div>
        <div style="font-size:12px; margin-top:4px;">Jab customer order karenge, yahan dikhai dega.</div>
      </div>
    @else
      <div class="responsive-grid">
        @foreach($orders as $o)
          @php
            $col = $statusColors[$o->status] ?? ['bg' => '#F3F4F6', 'color' => '#555'];
            // Build text listing
            $medItems = [];
            foreach ($o->items as $item) {
              $medItems[] = ($item['emoji'] ?? '💊') . ' ' . $item['name'] . ' (x' . ($item['quantity'] ?? $item['qty'] ?? 1) . ')';
            }
            $medListStr = implode(', ', $medItems);
          @endphp
          <div class="card" style="padding:14px; margin:0;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; align-items:center;">
              <span style="font-weight:800; font-size:14px; color:#1A1A1A;">Order #{{ $o->id }}</span>
              <span style="font-size:11px; font-weight:800; padding:3px 10px; border-radius:8px; background:{{ $col['bg'] }}; color:{{ $col['color'] }};">
                {{ $o->status }}
              </span>
            </div>
            
            <div style="font-size:13px; color:#444; margin-bottom:12px; line-height:1.4;">
              <strong>Dawaiyaan:</strong> {{ $medListStr }}
            </div>
            
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:12px; color:#888; border-top:1px solid #F3F4F6; padding-top:10px; margin-top:8px;">
              <span>{{ $o->mode === 'delivery' ? '🛵 Delivery' : '🚶 Pickup' }}</span>
              
              <div style="display:flex; gap:6px; align-items:center;">
                <span style="font-weight:800; color:#1A3C8F; font-size:14px; margin-right:8px;">₹{{ $o->total_price + $o->delivery_charge }}</span>
                
                @if($o->status === 'Pending')
                  <form action="{{ url('/shop/order/status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $o->id }}">
                    <input type="hidden" name="status" value="Accepted">
                    <button type="submit" class="btn-green" style="font-size:11px; padding:6px 10px; border-radius:8px;">Accept</button>
                  </form>
                @elseif($o->status === 'Accepted')
                  <form action="{{ url('/shop/order/status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $o->id }}">
                    <input type="hidden" name="status" value="Delivered">
                    <button type="submit" class="btn-blue" style="font-size:11px; padding:6px 10px; border-radius:8px;">Deliver</button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</div>
@endsection
