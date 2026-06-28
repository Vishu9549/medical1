@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
      <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">⚙️ Account Settings</h2>
    </div>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="background:#fff; border-radius:20px; padding:20px; border:1px solid #E2E8F0; box-shadow:0 4px 16px rgba(0,0,0,0.04);">
      <h3 style="font-weight:800; font-size:15px; color:#1A202C; margin-top:0; margin-bottom:16px;">Personal Information</h3>

      <div style="margin-bottom:12px;">
        <label style="font-size:11px; font-weight:800; color:#64748B; text-transform:uppercase; display:block; margin-bottom:4px;">Full Name</label>
        <input type="text" class="form-input" value="{{ $user->name }}" readonly style="background:#F8FAFF;">
      </div>
      <div style="margin-bottom:12px;">
        <label style="font-size:11px; font-weight:800; color:#64748B; text-transform:uppercase; display:block; margin-bottom:4px;">Phone Number</label>
        <input type="text" class="form-input" value="{{ $user->phone ?? 'Not set' }}" readonly style="background:#F8FAFF;">
      </div>
      <div style="margin-bottom:16px;">
        <label style="font-size:11px; font-weight:800; color:#64748B; text-transform:uppercase; display:block; margin-bottom:4px;">User Role Type</label>
        <input type="text" class="form-input" value="{{ strtoupper($user->role) }}" readonly style="background:#F8FAFF;">
      </div>

      <a href="{{ url('/profile') }}" class="btn-outline" style="width:100%; font-size:13px; text-decoration:none; display:block; text-align:center; padding:12px;">Back to Profile</a>
    </div>
  </div>
</div>
@endsection
