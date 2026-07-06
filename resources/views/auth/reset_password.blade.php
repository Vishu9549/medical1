@extends('layouts.app')

@section('content')
<div class="screen" style="align-items:center; justify-content:center; padding:30px 20px;">
  <div style="background:#fff; border-radius:20px; padding:24px; max-width:400px; width:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08); border:1px solid #E5E7EB;">
    <div style="text-align:center; margin-bottom:20px;">
      <div style="font-size:32px; margin-bottom:10px;">🔑</div>
      <h2 style="font-weight:900; font-size:18px; color:#1A1A1A;">Set New Password</h2>
      <p style="font-size:12px; color:#888; margin-top:4px;">Define a new secure password for your Dawalo account.</p>
    </div>

    @if($errors->any())
      <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:10px 12px; font-size:12px; color:#DC2626; margin-bottom:12px;">
        @foreach($errors->all() as $error)
          <div>⚠️ {{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ url('/reset-password') }}" method="POST">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      
      <div style="margin-bottom:12px;">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-input" placeholder="name@example.com" value="{{ request('email') ?? old('email') }}" required readonly>
      </div>
      <div style="margin-bottom:12px;">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-input" placeholder="Min 6 characters" required autofocus>
      </div>
      <div style="margin-bottom:16px;">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm password" required>
      </div>
      <button type="submit" class="btn-blue" style="width:100%; border:none; padding:14px; border-radius:12px; font-weight:800; font-size:15px; color:#fff;">
        Update Password
      </button>
    </form>
  </div>
</div>
@endsection
