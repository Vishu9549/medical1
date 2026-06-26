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
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Platform Commission Settings</p>
      </div>
    </div>
  </div>

  <!-- Admin Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 12px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/admin') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1;">
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
    <a href="{{ url('/admin/commission') }}" class="admin-tab active" style="background:linear-gradient(135deg,#1F2937,#4B5563); color:#fff; flex:1; box-shadow: 0 4px 12px rgba(31,41,55,0.3);">
      <span style="font-size:15px;">💰</span>Commission
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="background:#fff; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.06); max-width:550px; margin:0 auto; width:100%;">
      <h3 style="font-weight:900; font-size:16px; color:#1A1A1A; margin-bottom:18px;">💰 Platform Commission Parameters</h3>
      
      <form action="{{ url('/admin/commission') }}" method="POST">
        @csrf
        
        <!-- Toggle Switch for Commission Enabled -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #F3F4F6; padding-bottom:14px;">
          <div>
            <div style="font-weight:800; font-size:14px; color:#1A1A1A;">Charge Platform Commission</div>
            <div style="font-size:12px; color:#888; margin-top:2px;">Calculate & log dues for pharmacy orders</div>
          </div>
          
          <input type="checkbox" name="comm_on" value="1" style="width:24px; height:24px; cursor:pointer;" {{ $commOn ? 'checked' : '' }}>
        </div>

        <!-- Commission percentage rate -->
        <div style="margin-bottom:24px;">
          <label class="form-label">Commission Rate (%)</label>
          <div style="position:relative; display:flex; align-items:center;">
            <input type="number" name="comm_rate" class="form-input" min="0" max="100" value="{{ $commRate }}" required style="padding-right:32px;">
            <span style="position:absolute; right:14px; font-weight:800; color:#555;">%</span>
          </div>
          <p style="font-size:11px; color:#888; margin-top:6px; line-height:1.4;">
            * Commission matches total order basket value. Delivery fee is kept 100% by the pharmacy.
          </p>
        </div>

        <button type="submit" class="btn-blue" style="width:100%; padding:14px; font-size:15px; border:none; background:#1F2937; color:#fff; border-radius:12px;">
          💾 Update Parameters
        </button>
      </form>
    </div>

    <!-- Shop Dues/Wallets Audit list -->
    <div style="max-width:550px; margin:24px auto 0; width:100%;">
      <h3 style="font-weight:800; font-size:14px; color:#1A1A1A; margin-bottom:12px;">🏪 Pharmacy Commission Wallets</h3>
      
      <div class="responsive-grid">
        @foreach($wallets as $w)
          <div style="background:#fff; border-radius:16px; padding:12px 14px; box-shadow:0 2px 12px rgba(0,0,0,0.05); margin:0;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <span style="font-weight:800; font-size:13px; color:#1A1A1A;">{{ $w->shop->name ?? 'Unknown Shop' }}</span>
              <span style="font-size:9px; font-weight:800; background:{{ $w->status === 'active' ? '#DCFCE7' : '#FEE2E2' }}; color:{{ $w->status === 'active' ? '#16A34A' : '#DC2626' }}; padding:2px 8px; border-radius:6px;">
                {{ strtoupper($w->status) }}
              </span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:11px; color:#666;">
              <span>Total Sales: ₹{{ number_format($w->total_sales, 1) }}</span>
              <span>Dues: <strong style="color:#DC2626;">₹{{ number_format($w->due_commission, 1) }}</strong></span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
