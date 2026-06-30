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
            <label class="form-label">City / Region Area Coverage</label>
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
              <label class="form-label">Latitude</label>
              <input type="number" step="any" name="latitude" id="lat-input" class="form-input" placeholder="26.1209" value="{{ old('latitude', '26.1209') }}" required>
            </div>
            <div style="flex:1;">
              <label class="form-label">Longitude</label>
              <input type="number" step="any" name="longitude" id="lng-input" class="form-input" placeholder="85.3647" value="{{ old('longitude', '85.3647') }}" required>
            </div>
          </div>
          
          <button type="button" onclick="detectGPSCoordinates(true)" class="btn-outline" style="width:100%; border:1px solid #1A3C8F; color:#1A3C8F; background:#fff; padding:10px; border-radius:10px; font-weight:800; font-size:12px; cursor:pointer; margin-bottom:12px;">
            📍 Auto-Detect GPS & Geocode Location
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
</script>
@endsection
