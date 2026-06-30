@extends('layouts.app')

@section('content')
<div class="screen" style="align-items:center; justify-content:center; padding:30px 20px;">
  <div style="background:#fff; border-radius:20px; padding:28px; max-width:650px; width:100%; box-shadow:0 10px 30px rgba(0,0,0,0.08); border:1px solid #E5E7EB;">
    <div style="text-align:center; margin-bottom:24px;">
      <div style="font-size:36px; margin-bottom:10px;">🏪</div>
      <h2 style="font-weight:900; font-size:22px; color:#1A1A1A;">Pharmacy Partner Registration</h2>
      <p style="font-size:13px; color:#888; margin-top:4px;">Register your pharmacy store on Dawalo platform to start selling</p>
    </div>

    @if($errors->any())
      <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:10px 12px; font-size:12px; color:#DC2626; margin-bottom:16px;">
        @foreach($errors->all() as $error)
          <div>⚠️ {{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ url('/register/shop') }}" method="POST">
      @csrf

      <div style="display:grid; grid-template-columns: 1fr; gap:20px; margin-bottom:20px;">
        @media (min-width: 600px) {
          div[style*="display:grid"] {
            grid-template-columns: 1fr 1fr !important;
          }
        }
      </div>
      
      <!-- Layout columns grid using flexbox/grid styled inline -->
      <div style="display:flex; flex-direction:column; gap:24px;">
        <!-- Column 1: Owner (Account) details -->
        <div style="flex:1;">
          <h3 style="font-weight:800; font-size:14px; color:#1A3C8F; border-bottom:1px solid #E5E7EB; padding-bottom:6px; margin-bottom:12px;">👤 Owner Details</h3>
          
          <div style="margin-bottom:12px;">
            <label class="form-label">Owner Full Name</label>
            <input type="text" name="name" class="form-input" placeholder="e.g. Rajesh Sharma" value="{{ old('name') }}" required>
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
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" placeholder="Min. 6 characters" required>
          </div>

          <div style="margin-bottom:12px;">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Retype password" required>
          </div>
        </div>

        <!-- Column 2: Shop details -->
        <div style="flex:1;">
          <h3 style="font-weight:800; font-size:14px; color:#1A3C8F; border-bottom:1px solid #E5E7EB; padding-bottom:6px; margin-bottom:12px;">🏪 Pharmacy Store Details</h3>
          
          <div style="margin-bottom:12px;">
            <label class="form-label">Pharmacy Name</label>
            <input type="text" name="shop_name" class="form-input" placeholder="e.g. Sharma Medical Store" value="{{ old('shop_name') }}" required>
          </div>

          <div style="margin-bottom:12px;">
            <label class="form-label">City / Region Area</label>
            <select name="area" class="form-input" required>
              <option value="Muzaffarpur" {{ old('area') === 'Muzaffarpur' ? 'selected' : '' }}>Muzaffarpur</option>
              <option value="Patna" {{ old('area') === 'Patna' ? 'selected' : '' }}>Patna</option>
              <option value="Jaipur" {{ old('area') === 'Jaipur' ? 'selected' : '' }}>Jaipur</option>
              <option value="Darbhanga" {{ old('area') === 'Darbhanga' ? 'selected' : '' }}>Darbhanga</option>
            </select>
          </div>

          <div style="margin-bottom:12px;">
            <label class="form-label">Full Address</label>
            <input type="text" name="address" class="form-input" placeholder="Shop Address, Mithanpura" value="{{ old('address') }}" required>
          </div>

          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
              <label class="form-label">Latitude</label>
              <input type="number" step="any" name="latitude" id="lat-input" class="form-input" placeholder="26.1209" value="{{ old('latitude', '26.1209') }}" required>
            </div>
            <div style="flex:1;">
              <label class="form-label">Longitude</label>
              <input type="number" step="any" name="longitude" id="lng-input" class="form-input" placeholder="85.3647" value="{{ old('longitude', '85.3647') }}" required>
            </div>
          </div>
          
          <button type="button" onclick="detectGPSCoordinates()" class="btn-outline" style="width:100%; border:1px solid #1A3C8F; color:#1A3C8F; background:#fff; padding:10px; border-radius:10px; font-weight:800; font-size:12px; cursor:pointer; margin-bottom:12px;">
            📍 Auto-Detect Coordinates
          </button>
        </div>
      </div>

      <button type="submit" class="btn-blue" style="width:100%; border:none; padding:14px; border-radius:12px; font-weight:800; font-size:15px; color:#fff; cursor:pointer; margin-top:20px; box-shadow: 0 4px 12px rgba(37,99,235,0.2);">
        Register Pharmacy & Launch
      </button>
    </form>

    <div style="text-align:center; margin-top:20px; font-size:13px; color:#666;">
      Pehle se account hai? <a href="{{ url('/login') }}" style="color:#2563EB; font-weight:700; text-decoration:none;">Login Karein</a>
    </div>
  </div>
</div>

<script>
function detectGPSCoordinates() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      document.getElementById('lat-input').value = position.coords.latitude.toFixed(6);
      document.getElementById('lng-input').value = position.coords.longitude.toFixed(6);
      alert("GPS coordinates detected successfully!");
    }, err => {
      alert("GPS detection failed: " + err.message);
    });
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}
</script>
@endsection
