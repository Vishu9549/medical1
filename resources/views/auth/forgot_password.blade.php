@extends('layouts.app')

@section('content')
<div class="screen" style="align-items:center; justify-content:center; padding:30px 20px;">
  <div style="background:#fff; border-radius:20px; padding:24px; max-width:400px; width:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08); border:1px solid #E5E7EB;">
    <div style="text-align:center; margin-bottom:20px;">
      <div style="font-size:32px; margin-bottom:10px;">🔒</div>
      <h2 style="font-weight:900; font-size:18px; color:#1A1A1A;">Reset Password</h2>
      <p style="font-size:12px; color:#888; margin-top:4px;">Put your registered email address and we'll send you a password reset link.</p>
    </div>

    @if($errors->any())
      <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:10px 12px; font-size:12px; color:#DC2626; margin-bottom:12px;">
        @foreach($errors->all() as $error)
          <div>⚠️ {{ $error }}</div>
        @endforeach
      </div>
    @endif

    @if(session('status'))
      <div style="background:#ECFDF5; border:1px solid #A7F3D0; border-radius:10px; padding:10px 12px; font-size:12px; color:#059669; margin-bottom:12px;">
        ✅ {{ session('status') }}
      </div>
    @endif

    <form action="{{ url('/forgot-password') }}" method="POST">
      @csrf
      <div style="margin-bottom:16px;">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-input" placeholder="name@example.com" value="{{ old('email') }}" required>
      </div>
      <button type="submit" class="btn-blue" style="width:100%; border:none; padding:14px; border-radius:12px; font-weight:800; font-size:15px; color:#fff;">
        Send Reset Link
      </button>
    </form>

    <div style="text-align:center; margin-top:18px; font-size:13px; color:#666;">
      Wapas login pe jana hai? <a href="{{ url('/login') }}" style="color:#2563EB; font-weight:700; text-decoration:none;">Login Here</a>
    </div>
  </div>
</div>
@endsection
