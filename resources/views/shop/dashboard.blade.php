@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Shop Dashboard Header -->
  <div class="hdr-gradient" style="padding:24px 20px 24px; position:relative; overflow:hidden; flex-shrink:0; border-radius: 20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">{{ $shop->name }}</h2>
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">📍 {{ $shop->area }} • Owner Dashboard</p>
      </div>
    </div>

    <!-- Toggles (Online Status, Delivery) -->
    <div style="display:flex; gap:10px; margin-bottom:16px; position:relative; z-index:1;">
      
      <!-- Online/Offline Switch -->
      <form action="{{ url('/shop/toggle-online') }}" method="POST" style="flex:1;">
        @csrf
        <button type="submit" style="width:100%; border:none; text-align:left; background:none; padding:0; cursor:pointer; font-family:inherit;">
          @php
            $online = (bool) $shop->is_online;
            $bg = $online ? 'rgba(34,197,94,0.25)' : 'rgba(255,255,255,0.12)';
            $border = $online ? '1.5px solid rgba(34,197,94,0.6)' : '1.5px solid rgba(255,255,255,0.2)';
          @endphp
          <div style="border-radius:16px; padding:12px 14px; background:{{ $bg }}; border:{{ $border }}; display:flex; align-items:center; gap:10px;">
            <div style="width:36px; height:36px; border-radius:10px; background:{{ $online ? '#22C55E' : 'rgba(255,255,255,0.2)' }}; display:flex; align-items:center; justify-content:center; font-size:18px;">
              {{ $online ? '🟢' : '🔴' }}
            </div>
            <div>
              <div style="color:#fff; font-weight:900; font-size:13px;">{{ $online ? 'Shop Online' : 'Shop Offline' }}</div>
              <div style="color:rgba(255,255,255,0.7); font-size:10px; margin-top:1px;">{{ $online ? 'Orders aa rahe hain' : 'Orders band hain' }}</div>
            </div>
          </div>
        </button>
      </form>

      <!-- Delivery Switch -->
      <form action="{{ url('/shop/toggle-delivery') }}" method="POST" style="flex:1;">
        @csrf
        <button type="submit" style="width:100%; border:none; text-align:left; background:none; padding:0; cursor:pointer; font-family:inherit;">
          @php
            $deliv = (bool) $shop->delivery_enabled;
            $bg = $deliv ? 'rgba(37,99,235,0.3)' : 'rgba(255,255,255,0.12)';
            $border = $deliv ? '1.5px solid rgba(96,165,250,0.6)' : '1.5px solid rgba(255,255,255,0.2)';
          @endphp
          <div style="border-radius:16px; padding:12px 14px; background:{{ $bg }}; border:{{ $border }}; display:flex; align-items:center; gap:10px;">
            <div style="width:36px; height:36px; border-radius:10px; background:{{ $deliv ? '#2563EB' : 'rgba(255,255,255,0.2)' }}; display:flex; align-items:center; justify-content:center; font-size:18px;">
              🛵
            </div>
            <div>
              <div style="color:#fff; font-weight:900; font-size:13px;">Delivery {{ $deliv ? 'ON' : 'OFF' }}</div>
              <div style="color:rgba(255,255,255,0.7); font-size:10px; margin-top:1px;">{{ $deliv ? 'Home delivery active' : 'Sirf pickup' }}</div>
            </div>
          </div>
        </button>
      </form>
    </div>

    <!-- Quick Stats Summary -->
    <div style="display:flex; gap:10px; position:relative; z-index:1;">
      <div style="flex:1; background:rgba(255,255,255,0.16); border-radius:16px; padding:14px 12px; border:1px solid rgba(255,255,255,0.2);">
        <div style="font-size:20px; margin-bottom:4px;">📦</div>
        <div style="color:#fff; font-weight:900; font-size:22px;">{{ $ordersCount }}</div>
        <div style="color:rgba(255,255,255,0.75); font-size:11px; font-weight:600;">Total Orders</div>
      </div>
      <div style="flex:1; background:rgba(255,255,255,0.16); border-radius:16px; padding:14px 12px; border:1px solid rgba(255,255,255,0.2);">
        <div style="font-size:20px; margin-bottom:4px;">💰</div>
        <div style="color:#fff; font-weight:900; font-size:22px;">₹{{ number_format($revenue, 1) }}</div>
        <div style="color:rgba(255,255,255,0.75); font-size:11px; font-weight:600;">Revenue</div>
      </div>
      <div style="flex:1; background:rgba(255,255,255,0.16); border-radius:16px; padding:14px 12px; border:1px solid rgba(255,255,255,0.2);">
        <div style="font-size:20px; margin-bottom:4px;">💊</div>
        <div style="color:#fff; font-weight:900; font-size:22px;">{{ $inventoryCount }}</div>
        <div style="color:rgba(255,255,255,0.75); font-size:11px; font-weight:600;">Products</div>
      </div>
    </div>
  </div>

  <!-- Dashboard Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 10px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/shop/dashboard') }}" class="dash-tab {{ Request::is('shop/dashboard') ? 'active' : '' }}" style="background:#1A3C8F; color:#fff; flex:1; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
      <span style="font-size:16px;">📊</span>Overview
    </a>
    <a href="{{ url('/shop/quicksetup') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">⚡</span>Quick Setup
    </a>
    <a href="{{ url('/shop/inventory') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📦</span>Inventory
    </a>
    <a href="{{ url('/shop/orders') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📋</span>Orders
    </a>
  </div>

  <!-- Content scroll pane -->
  <div class="scroll" style="flex:1;">
    <div class="responsive-grid">
      
      @if($inventoryCount === 0)
        <a href="{{ url('/shop/quicksetup') }}" style="text-decoration:none; margin:0;">
          <div style="background:linear-gradient(135deg,#7C3AED,#A855F7); border-radius:18px; padding:16px 18px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 4px 16px rgba(124,58,237,0.3);">
            <div>
              <div style="color:#fff; font-weight:900; font-size:14px; margin-bottom:3px;">⚡ Quick Setup se shuru karein</div>
              <div style="color:rgba(255,255,255,0.85); font-size:12px;">Bas tick karo — 2 min mein ready</div>
            </div>
            <div style="color:#fff; font-size:22px;">→</div>
          </div>
        </a>
      @endif

      <!-- Welcome Banner -->
      <div style="background:linear-gradient(135deg,#EEF2FF,#F8FAFF); border-radius:20px; padding:18px; box-shadow:0 4px 20px rgba(37,99,235,0.08); border:1px solid #E0E7FF; margin:0;">
        <h3 style="font-weight:900; font-size:16px; color:#1A1A1A; margin-bottom:6px;">👋 Namaste, {{ $shop->owner_name }}!</h3>
        <p style="font-size:12.5px; color:#666; line-height:1.6; margin:0;">
          Aapki dukan <strong style="color:#1A3C8F;">{{ $shop->name }}</strong> ab Dawalo pe live hai. Customers aapki dukan se medicines search kar ke order kar sakte hain.
        </p>
      </div>

      <!-- Store Timings Settings Card -->
      <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid #F3F4F6; margin:0;">
        <h4 style="font-weight:900; font-size:14px; color:#1A3C8F; margin-top:0; margin-bottom:12px; display:flex; align-items:center; gap:6px;">🕰️ Store Timings</h4>
        <form action="{{ url('/shop/update-timings') }}" method="POST">
          @csrf
          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
              <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Opening Time</label>
              <input type="time" name="opens_at" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->opens_at ?? '09:00' }}" required>
            </div>
            <div style="flex:1;">
              <label class="form-label" style="font-size:11px; margin-bottom:4px; display:block; font-weight:700; color:#555;">Closing Time</label>
              <input type="time" name="closes_at" class="form-input" style="padding:10px; font-size:13px;" value="{{ $shop->closes_at ?? '21:00' }}" required>
            </div>
          </div>
          <button type="submit" class="btn-blue" style="width:100%; border:none; padding:11px; border-radius:10px; font-weight:800; font-size:12px; color:#fff; cursor:pointer;">
            Save Store Timings
          </button>
        </form>
      </div>

      <!-- Quick Action Grid -->
      <div style="display:flex; gap:16px; margin:0; width:100%; grid-column: 1 / -1;">
        <a href="{{ url('/shop/inventory') }}" style="flex:1; text-decoration:none;">
          <div style="background:linear-gradient(135deg,#1A3C8F,#2563EB); border-radius:18px; padding:18px; text-align:center; box-shadow:0 6px 20px rgba(37,99,235,0.25);">
            <div style="font-size:30px; margin-bottom:8px;">📦</div>
            <div style="font-weight:800; font-size:13px; color:#fff;">Manage Inventory</div>
            <div style="font-size:11px; color:rgba(255,255,255,0.8); margin-top:2px;">{{ $inventoryCount }} medicines listed</div>
          </div>
        </a>
        <a href="{{ url('/shop/orders') }}" style="flex:1; text-decoration:none;">
          <div style="background:linear-gradient(135deg,#059669,#10B981); border-radius:18px; padding:18px; text-align:center; box-shadow:0 6px 20px rgba(16,185,129,0.25);">
            <div style="font-size:30px; margin-bottom:8px;">📋</div>
            <div style="font-weight:800; font-size:13px; color:#fff;">View Orders</div>
            <div style="font-size:11px; color:rgba(255,255,255,0.8); margin-top:2px;">{{ $ordersCount }} total received</div>
          </div>
        </a>
      </div>

      <!-- Wallet Commission Health Card -->
      <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin:0; grid-column: 1 / -1;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
          <h4 style="font-weight:800; font-size:14px; color:#1A1A1A; margin:0;">💰 Commission Wallet</h4>
          <span style="font-size:11px; font-weight:800; background:#DCFCE7; color:#16A34A; padding:3px 10px; border-radius:8px;">
            {{ strtoupper($wallet->status ?? 'active') }}
          </span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
          <span style="color:#666;">Due Commission:</span>
          <strong style="color:#DC2626;">₹{{ number_format($wallet->due_commission ?? 0, 2) }}</strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:13px;">
          <span style="color:#666;">Credit Limit:</span>
          <span style="font-weight:700;">₹{{ number_format($wallet->credit_limit ?? 100, 2) }}</span>
        </div>
        
        <!-- Due Limit Progress Bar -->
        @php
          $percent = min(100, max(0, (($wallet->due_commission ?? 0) / ($wallet->credit_limit ?? 100)) * 100));
          $barColor = $percent > 80 ? '#DC2626' : ($percent > 50 ? '#D97706' : '#2563EB');
        @endphp
        <div class="due-bar-track">
          <div class="due-bar-fill" style="width: {{ $percent }}%; background: {{ $barColor }};"></div>
        </div>
        <p style="font-size:11px; color:#888; margin-top:8px; line-height:1.4;">
          * Agar aapka due limit limits exceed karega toh account temporary restrict kar diya jayega.
        </p>
      </div>

      <!-- Prescription Orders Card -->
      <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin:0; grid-column: 1 / -1; border:1px solid #F3F4F6;">
        <h4 style="font-weight:900; font-size:15px; color:#1A3C8F; margin-top:0; margin-bottom:14px; display:flex; align-items:center; gap:6px;">📋 Prescription Orders</h4>
        
        @if($prescriptions->count() === 0)
          <div style="text-align:center; padding:20px; color:#888; font-size:13px;">
            Koi prescription orders nahi mile abhi tak.
          </div>
        @else
          <div style="display:flex; flex-direction:column; gap:16px;">
            @foreach($prescriptions as $rx)
              <div style="background:#F8FAFC; border-radius:14px; padding:14px; border:1px solid #E2E8F0;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
                  <div>
                    <span style="font-size:12px; font-weight:800; color:#1A3C8F;">#RX-{{ str_pad($rx->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <div style="font-size:11px; color:#666; margin-top:2px;">{{ $rx->created_at->format('d M Y, h:i A') }}</div>
                  </div>
                  
                  <!-- Dropdown Status Form -->
                  <form action="{{ url('/shop/prescription/status') }}" method="POST" style="margin:0;">
                    @csrf
                    <input type="hidden" name="prescription_id" value="{{ $rx->id }}">
                    <select name="status" onchange="this.form.submit()" style="font-size:11.5px; font-weight:700; padding:4px 8px; border-radius:8px; border:1px solid #CBD5E1; background:#fff; color:#1A1A1A; cursor:pointer;">
                      <option value="Pending" {{ $rx->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                      <option value="Accepted" {{ $rx->status === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                      <option value="Out for Delivery" {{ $rx->status === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                      <option value="Delivered" {{ $rx->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                      <option value="Cancelled" {{ $rx->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                  </form>
                </div>

                <div style="display:flex; gap:12px; align-items:flex-start;">
                  <!-- Thumbnail Link -->
                  <a href="{{ $rx->image_path }}" target="_blank" style="flex-shrink:0;">
                    <img src="{{ $rx->image_path }}" style="width:70px; height:70px; border-radius:8px; object-fit:cover; border:1px solid #CBD5E1;" alt="RX">
                  </a>
                  
                  <div style="flex:1; font-size:12px; color:#475569;">
                    <div style="margin-bottom:2px;"><strong style="color:#1A1A1A;">Patient:</strong> {{ $rx->patient_name }} ({{ $rx->patient_age ?? 'N/A' }} Yrs)</div>
                    <div style="margin-bottom:2px;"><strong style="color:#1A1A1A;">Phone:</strong> {{ $rx->patient_phone }}</div>
                    <div style="margin-bottom:4px; line-height:1.3;"><strong style="color:#1A1A1A;">Address:</strong> {{ $rx->delivery_address }}</div>
                    @if($rx->notes)
                      <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:8px; padding:6px 8px; font-size:11px; color:#B45309; margin-top:4px;">
                        <strong>Notes:</strong> {{ $rx->notes }}
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

    </div>
  </div>
</div>
@endsection
