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
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Pending Pharmacy Reviews</p>
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
    <a href="{{ url('/admin/approvals') }}" class="admin-tab active" style="background:linear-gradient(135deg,#1F2937,#4B5563); color:#fff; flex:1; box-shadow: 0 4px 12px rgba(31,41,55,0.3); position:relative;">
      <span style="font-size:15px;">✅</span>Approvals
      @if($pendingApprovals->count() > 0)
        <div style="position:absolute; top:2px; right:2px; background:#DC2626; color:#fff; font-size:8px; font-weight:900; width:14px; height:14px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
          {{ $pendingApprovals->count() }}
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
    @if($pendingApprovals->isEmpty())
      <div style="text-align:center; padding:40px 20px; color:#888;">
        <div style="font-size:40px; margin-bottom:10px;">✅</div>
        <div style="font-weight:700; font-size:15px;">Sab clear hai!</div>
        <div style="font-size:12px; margin-top:4px;">Koi pending pharmacy registration review nahi hai.</div>
      </div>
    @else
      <div class="responsive-grid">
        @foreach($pendingApprovals as $s)
          <div class="card" style="padding:14px; border:1px solid #FDE68A; margin:0;">
            <div style="font-weight:800; font-size:14px; color:#1A1A1A; margin-bottom:4px;">{{ $s->name }}</div>
            <div style="font-size:12px; color:#888; margin-bottom:10px;">
              👤 Owner: {{ $s->owner_name }} • 📍 Area: {{ $s->area }} • 📞 Phone: {{ $s->phone }}
            </div>
            
            <div style="display:flex; gap:8px;">
              <form action="{{ url('/admin/stores/status') }}" method="POST" style="flex:1;">
                @csrf
                <input type="hidden" name="shop_id" value="{{ $s->id }}">
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn-blue" style="width:100%; font-size:12px; padding:10px;">✅ Approve</button>
              </form>
              
              <form action="{{ url('/admin/stores/status') }}" method="POST" style="flex:1;">
                @csrf
                <input type="hidden" name="shop_id" value="{{ $s->id }}">
                <input type="hidden" name="status" value="blocked">
                <button type="submit" class="btn-danger" style="width:100%; font-size:12px; padding:10px;">❌ Reject</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
