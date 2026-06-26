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
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Master Catalog Management</p>
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
    <a href="{{ url('/admin/medicines') }}" class="admin-tab active" style="background:linear-gradient(135deg,#1F2937,#4B5563); color:#fff; flex:1; box-shadow: 0 4px 12px rgba(31,41,55,0.3);">
      <span style="font-size:15px;">💊</span>Medicines
    </a>
    <a href="{{ url('/admin/commission') }}" class="admin-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:15px;">💰</span>Commission
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
      <div>
        <h3 style="font-weight:900; font-size:16px; color:#1A1A1A; margin:0;">💊 Master Medicine Catalog</h3>
        <p style="font-size:12px; color:#888; margin-top:2px;">Listed for shop stock configuration</p>
      </div>
      <a href="{{ url('/admin/medicines?showForm=1') }}" class="btn-blue" style="font-size:13px; text-decoration:none;">+ Add Medicine</a>
    </div>

    <!-- Master Medicine Form -->
    @if(request('showForm'))
      <div style="background:#fff; border-radius:18px; padding:16px; box-shadow:0 4px 20px rgba(0,0,0,0.1); border:2px solid #BFDBFE; margin-bottom:14px; max-width:500px; margin-left:auto; margin-right:auto;">
        <h4 style="font-weight:800; font-size:14px; color:#1A1A1A; margin-bottom:12px;">➕ Add Master Medicine</h4>
        
        <form action="{{ url('/admin/medicines/add') }}" method="POST">
          @csrf
          <div style="margin-bottom:10px;">
            <input type="text" name="name" class="form-input" placeholder="Medicine Name *" required>
          </div>
          <div style="margin-bottom:10px;">
            <select name="category" class="form-input" required>
              <option value="">Select Category</option>
              @foreach(['Fever', 'Antibiotic', 'Allergy', 'Acidity', 'Pain', 'Diabetes', 'Heart', 'Supplement', 'Skin', 'Eye', 'Dental'] as $c)
                <option value="{{ $c }}">{{ $c }}</option>
              @endforeach
            </select>
          </div>
          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <input type="number" step="0.01" name="mrp" class="form-input" style="flex:1;" placeholder="MRP ₹" required>
            <input type="number" step="0.01" name="price" class="form-input" style="flex:1;" placeholder="Selling Price ₹" required>
          </div>
          <div style="display:flex; gap:10px;">
            <a href="{{ url('/admin/medicines') }}" class="btn-outline" style="flex:1; text-decoration:none; text-align:center; padding:11px; font-size:13px; color:#555; border-color:#E5E7EB;">Cancel</a>
            <button type="submit" class="btn-blue" style="flex:1; padding:11px; font-size:13px;">Save Item</button>
          </div>
        </form>
      </div>
    @endif

    <!-- Master Medicine Grid -->
    <div class="responsive-grid">
      @foreach($medicines as $med)
        <div style="background:#fff; border-radius:16px; padding:12px; display:flex; gap:12px; align-items:center; box-shadow:0 2px 12px rgba(0,0,0,0.05); margin:0;">
          <div style="width:50px; height:50px; border-radius:12px; background:#F8FAFF; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">
            {{ $med->emoji }}
          </div>
          <div style="flex:1;">
            <div style="font-weight:800; font-size:14px; color:#1A1A1A;">{{ $med->name }}</div>
            <div style="display:flex; gap:10px; margin-top:2px;">
              <span style="font-size:12px; color:#888;">{{ $med->category }}</span>
              <span style="font-size:12px; color:#1A3C8F; font-weight:700;">₹{{ $med->price }}</span>
              <span style="font-size:12px; color:#aaa; text-decoration:line-through;">₹{{ $med->mrp }}</span>
            </div>
          </div>
          <div>
            <form action="{{ url('/admin/medicines/delete/'.$med->id) }}" method="POST" onsubmit="return confirm('Master item ko permanent delete karein?');">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:#FEF2F2; border:none; border-radius:10px; width:34px; height:34px; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center;">
                🗑️
              </button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</div>
@endsection
