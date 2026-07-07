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
          
          <div style="margin-bottom:12px; position:relative;">
            <label class="form-label">🔍 Search Pharmacy Location</label>
            <input type="text" id="search-address" class="form-input" placeholder="Type to search (e.g. Mithanpura, Muzaffarpur)" oninput="debouncedSearchAddress(this.value)">
            <!-- Search Results Dropdown -->
            <div id="search-results" style="display:none; position:absolute; left:0; right:0; top:100%; background:#fff; border:1px solid #E5E7EB; border-radius:12px; max-height:200px; overflow-y:auto; z-index:1000; box-shadow:0 10px 20px rgba(0,0,0,0.1); margin-top:4px;"></div>
          </div>

          <div style="margin-bottom:12px;">
            <label class="form-label">Pharmacy Name</label>
            <input type="text" name="shop_name" class="form-input" placeholder="e.g. Sharma Medical Store" value="{{ old('shop_name') }}" required>
          </div>

          <div style="margin-bottom:12px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="form-label" style="margin-bottom:0;">City / Region Area Coverage</label>
              <button type="button" onclick="detectGPSCoordinates(true)" class="btn-outline" style="border:1px solid #1A3C8F; color:#1A3C8F; background:#fff; padding:4px 8px; border-radius:8px; font-weight:800; font-size:11px; cursor:pointer;">
                📍 Auto-Detect GPS
              </button>
            </div>
            <select name="area" id="area-select" class="form-input" required>
              <option value="Muzaffarpur" {{ old('area') === 'Muzaffarpur' ? 'selected' : '' }}>Muzaffarpur</option>
              <option value="Patna" {{ old('area') === 'Patna' ? 'selected' : '' }}>Patna</option>
              <option value="Jaipur" {{ old('area') === 'Jaipur' ? 'selected' : '' }}>Jaipur</option>
              <option value="Darbhanga" {{ old('area') === 'Darbhanga' ? 'selected' : '' }}>Darbhanga</option>
            </select>
          </div>

          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
              <label class="form-label">State</label>
              <input type="text" name="state" id="state-input" class="form-input" placeholder="e.g. Bihar" value="{{ old('state') }}">
            </div>
            <div style="flex:1;">
              <label class="form-label">City</label>
              <input type="text" name="city" id="city-input" class="form-input" placeholder="e.g. Muzaffarpur" value="{{ old('city') }}">
            </div>
          </div>

          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
              <label class="form-label">Area / Locality</label>
              <input type="text" name="area_name" id="area-name-input" class="form-input" placeholder="e.g. Mithanpura" value="{{ old('area_name') }}">
            </div>
            <div style="flex:1;">
              <label class="form-label">PIN Code</label>
              <input type="text" name="pin_code" id="pin-input" class="form-input" placeholder="e.g. 842002" value="{{ old('pin_code') }}">
            </div>
          </div>

          <div style="margin-bottom:12px;">
            <label class="form-label">Full Address</label>
            <input type="text" name="address" id="address-input" class="form-input" placeholder="Shop Address, Mithanpura" value="{{ old('address') }}" required>
          </div>

          <div style="display:flex; gap:10px; margin-bottom:12px;">
            <div style="flex:1;">
              <label class="form-label">Opening Time</label>
              <input type="time" name="opens_at" class="form-input" value="{{ old('opens_at', '09:00') }}" required>
            </div>
            <div style="flex:1;">
              <label class="form-label">Closing Time</label>
              <input type="time" name="closes_at" class="form-input" value="{{ old('closes_at', '21:00') }}" required>
            </div>
          </div>

          <!-- Home Delivery Toggle -->
          <div style="background:#F8FAFF; border:1px solid #E0E7FF; border-radius:14px; padding:14px; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between;">
            <div>
              <div style="font-weight:800; font-size:13px; color:#1A1A1A; display:flex; align-items:center; gap:6px;">
                🛵 Home Delivery
              </div>
              <div style="font-size:11px; color:#666; margin-top:2px;">Pickup + Delivery dono available</div>
            </div>
            <label class="switch-container" style="position:relative; display:inline-block; width:44px; height:24px;">
              <input type="checkbox" name="delivery_enabled" value="1" checked style="opacity:0; width:0; height:0;" onchange="toggleRegDeliverySection(this.checked)">
              <span class="switch-slider" style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#10B981; transition:.4s; border-radius:24px;"></span>
            </label>
          </div>

          <!-- Delivery Charge Options Container -->
          <div id="reg-delivery-charges-container" style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:14px; padding:14px; margin-bottom:14px;">
            <div style="font-weight:800; font-size:13px; color:#1A1A1A; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
              💰 Delivery Charge
            </div>
            <div style="font-size:11px; color:#666; margin-bottom:12px;">Aap decide karein customer se kitna delivery charge lena hai</div>

            <!-- Buttons Selector -->
            <div style="display:flex; gap:8px; margin-bottom:12px;">
              <button type="button" id="reg-btn-del-free" onclick="setRegDeliveryType('free')" style="flex:1; padding:8px; border:1.5px solid #CBD5E1; background:#fff; border-radius:10px; font-weight:700; font-size:12px; color:#475569; cursor:pointer; outline:none;">🎁 Free</button>
              <button type="button" id="reg-btn-del-perkm" onclick="setRegDeliveryType('perkm')" style="flex:1; padding:8px; border:2px solid #1A3C8F; background:#EFF6FF; border-radius:10px; font-weight:700; font-size:12px; color:#1A3C8F; cursor:pointer; outline:none;">🖊️ Per KM</button>
              <button type="button" id="reg-btn-del-fixed" onclick="setRegDeliveryType('fixed')" style="flex:1; padding:8px; border:1.5px solid #CBD5E1; background:#fff; border-radius:10px; font-weight:700; font-size:12px; color:#475569; cursor:pointer; outline:none;">📦 Fixed</button>
            </div>

            <!-- Value Input -->
            <input type="hidden" name="delivery_charge_type" id="reg-del-type" value="dynamic">
            
            <div id="reg-del-rate-wrapper">
              <label class="form-label" id="reg-del-rate-label" style="font-size:11.5px; font-weight:700; color:#555; margin-bottom:4px; display:block;">Rate per KM (₹)</label>
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-weight:800; color:#1A1A1A; font-size:14px;">₹</span>
                <input type="number" step="0.5" name="delivery_charge_rate" id="reg-del-rate-input" class="form-input" style="flex:1; padding:8px 10px; font-size:13px;" value="8">
                <span style="font-size:12px; color:#666;" id="reg-del-rate-suffix">/ km</span>
              </div>
              <div style="font-size:11px; color:#888; margin-top:6px;" id="reg-del-example">Example: 3 km &times; ₹8 = ₹24 delivery charge</div>
            </div>
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
function detectGPSCoordinates(showAlert = false) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;
      document.getElementById('lat-input').value = lat.toFixed(6);
      document.getElementById('lng-input').value = lng.toFixed(6);

      // Perform Reverse Geocoding via Nominatim
      fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(res => res.json())
        .then(data => {
          if (data && data.address) {
            const addr = data.address;
            document.getElementById('state-input').value = addr.state || '';
            document.getElementById('city-input').value = addr.city || addr.town || addr.village || addr.county || '';
            document.getElementById('area-name-input').value = addr.suburb || addr.neighbourhood || addr.residential || addr.road || '';
            document.getElementById('pin-input').value = addr.postcode || '';
            document.getElementById('address-input').value = data.display_name || '';

            // Auto-select city area coverage match
            const displayName = (data.display_name || '').toLowerCase();
            const areaSelect = document.getElementById('area-select');
            if (displayName.includes('patna')) {
              areaSelect.value = 'Patna';
            } else if (displayName.includes('jaipur')) {
              areaSelect.value = 'Jaipur';
            } else if (displayName.includes('darbhanga')) {
              areaSelect.value = 'Darbhanga';
            } else {
              areaSelect.value = 'Muzaffarpur';
            }

            if (showAlert) {
              alert("Location coordinates and details auto-detected successfully!");
            }
          }
        })
        .catch(err => {
          console.error("Reverse geocoding failed:", err);
          if (showAlert) alert("GPS detected, but address geocoding failed.");
        });

    }, err => {
      if (showAlert) {
        alert("GPS detection failed: " + err.message);
      }
    });
  } else {
    if (showAlert) alert("Geolocation is not supported by this browser.");
  }
}

// Automatically trigger on page load
window.addEventListener('DOMContentLoaded', () => {
  detectGPSCoordinates(false);
});

let searchTimeout;
function debouncedSearchAddress(query) {
  clearTimeout(searchTimeout);
  if (!query || query.length < 3) {
    document.getElementById('search-results').style.display = 'none';
    return;
  }
  searchTimeout = setTimeout(() => {
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=in`)
      .then(res => res.json())
      .then(data => {
        const resultsDiv = document.getElementById('search-results');
        resultsDiv.innerHTML = '';
        if (data.length === 0) {
          resultsDiv.style.display = 'none';
          return;
        }
        resultsDiv.style.display = 'block';
        data.forEach(item => {
          const div = document.createElement('div');
          div.style.padding = '10px 12px';
          div.style.cursor = 'pointer';
          div.style.borderBottom = '1px solid #F3F4F6';
          div.style.fontSize = '12px';
          div.style.color = '#374151';
          div.innerText = item.display_name;
          div.addEventListener('click', () => {
            selectSearchedLocation(item);
          });
          resultsDiv.appendChild(div);
        });
      })
      .catch(err => console.error(err));
  }, 400);
}

function selectSearchedLocation(item) {
  const lat = parseFloat(item.lat);
  const lon = parseFloat(item.lon);
  document.getElementById('lat-input').value = lat.toFixed(6);
  document.getElementById('lng-input').value = lon.toFixed(6);

  document.getElementById('address-input').value = item.display_name;

  // Attempt to parse out state, city, area and postcode from Nominatim search if possible
  fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
    .then(res => res.json())
    .then(data => {
      if (data && data.address) {
        const addr = data.address;
        document.getElementById('state-input').value = addr.state || '';
        document.getElementById('city-input').value = addr.city || addr.town || addr.village || addr.county || '';
        document.getElementById('area-name-input').value = addr.suburb || addr.neighbourhood || addr.residential || addr.road || '';
        document.getElementById('pin-input').value = addr.postcode || '';
      }
    });

  const displayName = item.display_name.toLowerCase();
  const areaSelect = document.getElementById('area-select');
  if (displayName.includes('patna')) {
    areaSelect.value = 'Patna';
  } else if (displayName.includes('jaipur')) {
    areaSelect.value = 'Jaipur';
  } else if (displayName.includes('darbhanga')) {
    areaSelect.value = 'Darbhanga';
  } else {
    areaSelect.value = 'Muzaffarpur';
  }

  document.getElementById('search-results').style.display = 'none';
  document.getElementById('search-address').value = item.display_name;
}

// Close search dropdown on click outside
document.addEventListener('click', function(e) {
  if (e.target.id !== 'search-address') {
    document.getElementById('search-results').style.display = 'none';
  }
});

function toggleRegDeliverySection(checked) {
  const container = document.getElementById('reg-delivery-charges-container');
  if (checked) {
    container.style.display = 'block';
  } else {
    container.style.display = 'none';
  }
}

function setRegDeliveryType(type) {
  const btnFree = document.getElementById('reg-btn-del-free');
  const btnPerKm = document.getElementById('reg-btn-del-perkm');
  const btnFixed = document.getElementById('reg-btn-del-fixed');
  const typeInput = document.getElementById('reg-del-type');
  const rateWrapper = document.getElementById('reg-del-rate-wrapper');
  const rateLabel = document.getElementById('reg-del-rate-label');
  const rateInput = document.getElementById('reg-del-rate-input');
  const rateSuffix = document.getElementById('reg-del-rate-suffix');
  const rateExample = document.getElementById('reg-del-example');

  // Reset styles
  [btnFree, btnPerKm, btnFixed].forEach(btn => {
    btn.style.border = '1.5px solid #CBD5E1';
    btn.style.background = '#fff';
    btn.style.color = '#475569';
  });

  if (type === 'free') {
    btnFree.style.border = '2px solid #1A3C8F';
    btnFree.style.background = '#EFF6FF';
    btnFree.style.color = '#1A3C8F';
    typeInput.value = 'fixed';
    rateWrapper.style.display = 'none';
    rateInput.value = '0';
  } else if (type === 'perkm') {
    btnPerKm.style.border = '2px solid #1A3C8F';
    btnPerKm.style.background = '#EFF6FF';
    btnPerKm.style.color = '#1A3C8F';
    typeInput.value = 'dynamic';
    rateWrapper.style.display = 'block';
    rateLabel.innerText = 'Rate per KM (₹)';
    rateInput.value = '8';
    rateSuffix.innerText = '/ km';
    rateExample.innerText = 'Example: 3 km × ₹8 = ₹24 delivery charge';
  } else if (type === 'fixed') {
    btnFixed.style.border = '2px solid #1A3C8F';
    btnFixed.style.background = '#EFF6FF';
    btnFixed.style.color = '#1A3C8F';
    typeInput.value = 'fixed';
    rateWrapper.style.display = 'block';
    rateLabel.innerText = 'Fixed Delivery Charge (₹)';
    rateInput.value = '20';
    rateSuffix.innerText = 'flat';
    rateExample.innerText = 'Flat rate applies regardless of distance.';
  }
}
</script>

<style>
  .switch-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }
  input:checked + .switch-slider {
    background-color: #1A3C8F;
  }
  input:checked + .switch-slider:before {
    transform: translateX(20px);
  }
</style>
@endsection
