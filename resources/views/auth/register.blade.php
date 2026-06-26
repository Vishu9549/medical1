@extends('layouts.app')

@section('content')
<div class="screen" style="align-items:center; justify-content:center; padding:30px 20px;">
  <div style="background:#fff; border-radius:20px; padding:24px; max-width:450px; width:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08); border:1px solid #E5E7EB;">
    <div style="text-align:center; margin-bottom:20px;">
      <div style="font-size:32px; margin-bottom:10px;">📝</div>
      <h2 style="font-weight:900; font-size:18px; color:#1A1A1A;">Create an Account</h2>
      <p style="font-size:12px; color:#888; margin-top:4px;">Join Dawalo e-commerce pharmacy aggregator</p>
    </div>

    @if($errors->any())
      <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:10px 12px; font-size:12px; color:#DC2626; margin-bottom:12px;">
        @foreach($errors->all() as $error)
          <div>⚠️ {{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
      @csrf
      
      <div style="margin-bottom:12px;">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-input" placeholder="e.g. Rahul Kumar" value="{{ old('name') }}" required>
      </div>

      <div style="margin-bottom:12px;">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-input" placeholder="name@example.com" value="{{ old('email') }}" required>
      </div>

      <div style="margin-bottom:12px;">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-input" placeholder="e.g. 9876543210" value="{{ old('phone') }}" required>
      </div>

      <div style="margin-bottom:12px;">
        <label class="form-label">Aap kaun hain? (Account Role)</label>
        <select name="role" class="form-input" required>
          <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer (Medicine Kharidni Hai)</option>
          <option value="shop_owner" {{ old('role') === 'shop_owner' ? 'selected' : '' }}>Store Owner (Dukan List Karni Hai)</option>
        </select>
      </div>

      <div style="margin-bottom:12px;">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-input" placeholder="Min. 6 characters" required>
      </div>

      <div style="margin-bottom:16px;">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-input" placeholder="Retype password" required>
      </div>

      <button type="submit" class="btn-blue" style="width:100%; border:none; padding:14px; border-radius:12px; font-weight:800; font-size:15px; color:#fff;">
        Create Account
      </button>
    </form>

    <div style="text-align:center; margin-top:18px; font-size:13px; color:#666;">
      Pehle se account hai? <a href="{{ url('/login') }}" style="color:#2563EB; font-weight:700; text-decoration:none;">Login Karein</a>
    </div>
  </div>
</div>
@endsection
