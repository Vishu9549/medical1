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
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Dawalo Registered Stores</p>
      </div>
    </div>
  </div>

  <!-- Admin Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 12px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/admin') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:15px;">📊</span>Overview
    </a>
    <a href="{{ url('/admin/stores') }}" class="admin-tab active" style="background:linear-gradient(135deg,#1F2937,#4B5563); color:#fff; flex:1; box-shadow: 0 4px 12px rgba(31,41,55,0.3);">
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
    @php
      $statusStyles = [
        'approved' => ['bg' => '#DCFCE7', 'color' => '#16A34A', 'label' => 'Approved'],
        'pending' => ['bg' => '#FEF3C7', 'color' => '#D97706', 'label' => 'Pending'],
        'blocked' => ['bg' => '#FEE2E2', 'color' => '#DC2626', 'label' => 'Blocked']
      ];
    @endphp

    <div class="responsive-grid">
      @foreach($stores as $s)
        @php
          $style = $statusStyles[$s->status] ?? ['bg' => '#F3F4F6', 'color' => '#555', 'label' => $s->status];
        @endphp
        <div class="card" style="padding:14px; margin:0;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
            <div>
              <div style="font-weight:800; font-size:14px; color:#1A1A1A;">{{ $s->name }}</div>
              <div style="font-size:12px; color:#888; margin-top:2px;">👤 {{ $s->owner_name }} • 📍 {{ $s->area }}</div>
            </div>
            <span style="font-size:11px; font-weight:800; padding:3px 10px; border-radius:8px; background:{{ $style['bg'] }}; color:{{ $style['color'] }}; white-space:nowrap;">
              {{ $style['label'] }}
            </span>
          </div>
          
          <div style="display:flex; gap:14px; margin-bottom:10px; font-size:12px; color:#666;">
            <span>📦 {{ $s->orders_count ?? 0 }} orders</span>
            <span>💰 ₹{{ number_format($s->orders_sum_total_price ?? 0, 1) }}</span>
          </div>

          <form action="{{ url('/admin/stores/status') }}" method="POST">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $s->id }}">
            
            @if($s->status !== 'blocked')
              <input type="hidden" name="status" value="blocked">
              <button type="submit" class="btn-danger" style="width:100%; padding:10px; font-size:12px;">🚫 Block Store</button>
            @else
              <input type="hidden" name="status" value="approved">
              <button type="submit" class="btn-green" style="width:100%; padding:10px; font-size:12px; background:#F0FDF4; border:1px solid #BBF7D0; color:#16A34A;">✅ Approve / Unblock</button>
            @endif
          </form>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
