@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Admin Header -->
  <div class="hdr-gradient" style="background:linear-gradient(135deg,#1F2937,#374151,#4B5563); padding:24px 20px 24px; position:relative; overflow:hidden; flex-shrink:0; border-radius: 20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div>
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">🛡️ Admin Panel</h2>
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Dawalo Master Admin Dashboard</p>
      </div>
    </div>

    <!-- Quick Stats -->
    <div style="display:flex; gap:10px; position:relative; z-index:1;">
      <div style="flex:1; background:rgba(255,255,255,0.12); border-radius:16px; padding:14px 12px; border:1px solid rgba(255,255,255,0.15);">
        <div style="font-size:20px; margin-bottom:4px;">🏪</div>
        <div style="color:#fff; font-weight:900; font-size:22px;">{{ $shopsCount }}</div>
        <div style="color:rgba(255,255,255,0.7); font-size:11px; font-weight:600;">Total Shops</div>
      </div>
      <div style="flex:1; background:rgba(255,255,255,0.12); border-radius:16px; padding:14px 12px; border:1px solid rgba(255,255,255,0.15);">
        <div style="font-size:20px; margin-bottom:4px;">💰</div>
        <div style="color:#fff; font-weight:900; font-size:22px;">₹{{ number_format($revenue, 1) }}</div>
        <div style="color:rgba(255,255,255,0.7); font-size:11px; font-weight:600;">Revenue</div>
      </div>
    </div>
  </div>

  <!-- Admin Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 12px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/admin') }}" class="admin-tab active" style="background:linear-gradient(135deg,#1F2937,#4B5563); color:#fff; flex:1;">
      <span style="font-size:15px;">📊</span>Overview
    </a>
    <a href="{{ url('/admin/stores') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:15px;">🏪</span>Stores
    </a>
    <a href="{{ url('/admin/approvals') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1; position:relative;">
      <span style="font-size:15px;">✅</span>Approvals
      @if($pendingApprovalsCount > 0)
        <div style="position:absolute; top:2px; right:2px; background:#DC2626; color:#fff; font-size:8px; font-weight:900; width:14px; height:14px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
          {{ $pendingApprovalsCount }}
        </div>
      @endif
    </a>
    <a href="{{ url('/admin/medicines') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:15px;">💊</span>Medicines
    </a>
    <a href="{{ url('/admin/commission') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:15px;">💰</span>Commission
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    <div class="responsive-grid">
      
      <!-- Store approval status distribution summary -->
      <div style="background:#fff; border-radius:16px; padding:16px; box-shadow:0 2px 12px rgba(0,0,0,0.05); text-align:center; margin:0;">
        <div style="font-size:26px; margin-bottom:6px;">✅</div>
        <div style="font-weight:900; font-size:20px; color:#16A34A;">{{ $approvedShopsCount }}</div>
        <div style="font-size:11px; color:#888;">Approved Shops</div>
      </div>
      <div style="background:#fff; border-radius:16px; padding:16px; box-shadow:0 2px 12px rgba(0,0,0,0.05); text-align:center; margin:0;">
        <div style="font-size:26px; margin-bottom:6px;">⏳</div>
        <div style="font-weight:900; font-size:20px; color:#D97706;">{{ $pendingApprovalsCount }}</div>
        <div style="font-size:11px; color:#888;">Pending Review</div>
      </div>
      <div style="background:#fff; border-radius:16px; padding:16px; box-shadow:0 2px 12px rgba(0,0,0,0.05); text-align:center; margin:0;">
        <div style="font-size:26px; margin-bottom:6px;">🚫</div>
        <div style="font-weight:900; font-size:20px; color:#DC2626;">{{ $blockedShopsCount }}</div>
        <div style="font-size:11px; color:#888;">Blocked Shops</div>
      </div>

      <!-- Top Performing Shops -->
      <div style="grid-column: 1 / -1; margin-top: 10px;">
        <h3 style="font-weight:800; font-size:15px; color:#1A1A1A; margin-bottom:12px;">🏆 Top Performing Pharmacies</h3>
        
        <div class="responsive-grid">
          @foreach($topShops as $idx => $s)
            <div style="background:#fff; border-radius:16px; padding:12px 14px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 12px rgba(0,0,0,0.05); margin:0;">
              <span style="font-size:20px;">
                @if($idx == 0) 🥇 @elseif($idx == 1) 🥈 @else 🥉 @endif
              </span>
              <div style="flex:1;">
                <div style="font-weight:800; font-size:13px; color:#1A1A1A;">{{ $s->name }}</div>
                <div style="font-size:11px; color:#888;">📍 {{ $s->area }} • {{ $s->orders_count }} orders</div>
              </div>
              <div style="font-weight:900; font-size:14px; color:#1A3C8F;">₹{{ number_format($s->orders_sum_total_price ?? 0, 1) }}</div>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
