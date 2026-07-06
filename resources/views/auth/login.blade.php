@extends('layouts.app')

@section('content')
<div class="screen" style="align-items:center; justify-content:center; padding:30px 20px;">
  <div style="background:#fff; border-radius:20px; padding:24px; max-width:400px; width:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08); border:1px solid #E5E7EB;">
    <div style="text-align:center; margin-bottom:20px;">
      <div style="font-size:32px; margin-bottom:10px;">🔑</div>
      <h2 style="font-weight:900; font-size:18px; color:#1A1A1A;">Login to Dawalo</h2>
      <p style="font-size:12px; color:#888; margin-top:4px;">Manage orders, pharmacies, and inventory</p>
    </div>

    @if($errors->any())
      <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:10px 12px; font-size:12px; color:#DC2626; margin-bottom:12px;">
        @foreach($errors->all() as $error)
          <div>⚠️ {{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
      @csrf
      <div style="margin-bottom:12px;">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-input" placeholder="name@example.com" value="{{ old('email') }}" required>
      </div>
      <div style="margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
          <label class="form-label" style="margin-bottom:0;">Password</label>
          <a href="{{ url('/forgot-password') }}" style="color:#2563EB; font-size:11.5px; font-weight:700; text-decoration:none;">Forgot Password?</a>
        </div>
        <input type="password" name="password" class="form-input" placeholder="••••••" required>
      </div>
      <button type="submit" class="btn-blue" style="width:100%; border:none; padding:14px; border-radius:12px; font-weight:800; font-size:15px; color:#fff;">
        Sign In
      </button>
    </form>

    <div style="text-align:center; margin-top:18px; font-size:13px; color:#666; display:flex; flex-direction:column; gap:8px;">
      <div>Naya account chahiye? <a href="{{ url('/register') }}" style="color:#2563EB; font-weight:700; text-decoration:none;">Customer Registration</a></div>
      <div style="border-top:1px dashed #E5E7EB; padding-top:8px;">Dukan partner banna hai? <a href="{{ url('/register/shop') }}" style="color:#059669; font-weight:700; text-decoration:none;">Pharmacy Partner Registration</a></div>
    </div>
  </div>
</div>
@endsection
