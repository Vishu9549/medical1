@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <div class="scroll" style="padding:20px 0;">
    
    <!-- User Profile Header Card -->
    <div style="background:linear-gradient(135deg,#1A3C8F,#2563EB,#3B82F6); border-radius:20px; padding:24px 20px; text-align:center; margin-bottom:20px; box-shadow:0 8px 32px rgba(37,99,235,0.2);">
      <div style="width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 10px;">👤</div>
      <div style="color:#fff; font-weight:800; font-size:16px;">Guest User</div>
      <div style="color:rgba(255,255,255,0.7); font-size:12px; margin-top:2px;">Login/Register directly with your phone number</div>
    </div>

    <!-- Shop Management card -->
    @if($registeredShop)
      <div style="background:#fff; border-radius:20px; padding:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:16px; display:flex; gap:14px; align-items:center;">
        <div style="width:50px; height:50px; border-radius:14px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0;">🏪</div>
        <div style="flex:1;">
          <div style="font-weight:800; font-size:15px; color:#1A1A1A;">{{ $registeredShop->name }}</div>
          <div style="font-size:12px; color:#888; margin-top:2px;">Aapki pharmacy listed hai. Manage karein:</div>
        </div>
        <a href="{{ url('/shop/dashboard') }}" class="btn-blue" style="font-size:12px; text-decoration:none; padding:8px 14px;">Dashboard</a>
      </div>
    @else
      <div style="background:#fff; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:16px;">
        <div style="display:flex; gap:14px; align-items:flex-start;">
          <div style="width:50px; height:50px; border-radius:14px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0;">🏪</div>
          <div style="flex:1;">
            <div style="font-weight:800; font-size:15px; color:#1A1A1A; margin-bottom:4px;">Medical Store Owner?</div>
            <div style="font-size:12px; color:#888; margin-bottom:12px; line-height:1.5;">Apni dukan Dawalo pe list karein aur nearby customers tak pahunchein. Bilkul free!</div>
            <a href="{{ url('/profile?showRegisterForm=1') }}" class="btn-blue" style="font-size:13px; text-decoration:none; display:inline-block; margin-bottom:12px;">+ Apni Dukan List Karein</a>
            
            <div style="border-top:1px solid #F3F4F6; padding-top:12px; margin-top:4px;">
              <div style="font-weight:700; font-size:13px; color:#444; margin-bottom:6px;">Already registered store?</div>
              <form action="{{ url('/shop/login-phone') }}" method="POST" style="display:flex; gap:8px;">
                @csrf
                <input type="text" name="phone" placeholder="Registered phone number..." class="form-input" style="padding:8px 10px; font-size:12.5px;" required>
                <button type="submit" class="btn-blue" style="font-size:12.5px; padding:8px 14px; white-space:nowrap;">Manage</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- If register store form is requested -->
    @if(request('showRegisterForm') || request('modal') === 'shopForm')
      <div style="background:#fff; border-radius:20px; padding:20px; box-shadow:0 6px 24px rgba(0,0,0,0.1); margin-bottom:16px; border:2px solid #BFDBFE;">
        <h3 style="font-weight:800; font-size:16px; color:#1A1A1A; margin-bottom:14px;">🏪 Register Your Pharmacy</h3>
        
        <form action="{{ url('/shop/register') }}" method="POST">
          @csrf
          <div style="margin-bottom:12px;">
            <label class="form-label">Shop Name *</label>
            <input type="text" name="name" class="form-input" placeholder="e.g. Verma Medical Hall" required>
          </div>
          <div style="margin-bottom:12px;">
            <label class="form-label">Owner Name *</label>
            <input type="text" name="owner" class="form-input" placeholder="e.g. Ramesh Verma" required>
          </div>
          <div style="margin-bottom:12px;">
            <label class="form-label">Phone Number *</label>
            <input type="text" name="phone" class="form-input" placeholder="e.g. 9876543210" required>
          </div>
          <div style="margin-bottom:12px;">
            <label class="form-label">Area (Muzaffarpur Coverage) *</label>
            <select name="area" class="form-input" required>
              <option value="">Select Area</option>
              <option value="Mithanpura">Mithanpura</option>
              <option value="Kalyani">Kalyani</option>
              <option value="Sutapatti">Sutapatti</option>
              <option value="Bairia">Bairia</option>
              <option value="Ahiyapur">Ahiyapur</option>
            </select>
          </div>
          <div style="margin-bottom:16px;">
            <label class="form-label">Complete Address *</label>
            <textarea name="address" class="form-input" style="height:60px; font-family:inherit; resize:none;" placeholder="Complete shop location address..." required></textarea>
          </div>
          
          <!-- Map Selector for Coordinates -->
          <div style="margin-bottom:12px;">
            <label class="form-label">Select Shop Location on Map *</label>
            <div id="register-map" style="height:220px; border-radius:12px; border:1px solid #E5E7EB; margin-bottom:12px; z-index:1;"></div>
            <div style="display:flex; gap:10px;">
              <input type="text" name="latitude" id="reg-lat" class="form-input" placeholder="Latitude" readonly required style="background:#F3F4F6;">
              <input type="text" name="longitude" id="reg-lng" class="form-input" placeholder="Longitude" readonly required style="background:#F3F4F6;">
            </div>
          </div>

          <div style="display:flex; gap:10px;">
            <a href="{{ url('/profile') }}" class="btn-outline" style="flex:1; text-decoration:none; padding:12px; font-size:14px; border-color:#E5E7EB; color:#666; text-align:center;">Cancel</a>
            <button type="submit" class="btn-blue" style="flex:1; padding:12px; font-size:14px;">Save & List Store</button>
          </div>
        </form>
      </div>

      <script>
        window.addEventListener('DOMContentLoaded', () => {
          const map = L.map('register-map').setView([26.1209, 85.3647], 13);
          
          const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
          });

          const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri'
          });

          streetMap.addTo(map);

          const baseMaps = {
            "Street View 🗺️": streetMap,
            "Satellite View 🛰️": satelliteMap
          };

          L.control.layers(baseMaps).addTo(map);

          let marker;

          // Attempt to get user current location to center map
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
              const lat = position.coords.latitude;
              const lng = position.coords.longitude;
              map.setView([lat, lng], 15);
              marker = L.marker([lat, lng]).addTo(map);
              document.getElementById('reg-lat').value = lat.toFixed(6);
              document.getElementById('reg-lng').value = lng.toFixed(6);
            });
          }

          map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            if (marker) {
              marker.setLatLng([lat, lng]);
            } else {
              marker = L.marker([lat, lng]).addTo(map);
            }
            
            document.getElementById('reg-lat').value = lat.toFixed(6);
            document.getElementById('reg-lng').value = lng.toFixed(6);
          });
        });
      </script>
    @endif

    <!-- Profile Menu List -->
    <div style="background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
      @php
        $menuItems = [
          ['icon' => '📋', 'label' => 'My Orders'],
          ['icon' => '📍', 'label' => 'Saved Addresses'],
          ['icon' => '❤️', 'label' => 'Favourite Shops'],
          ['icon' => '🔔', 'label' => 'Notifications'],
          ['icon' => '⚙️', 'label' => 'Settings'],
          ['icon' => '❓', 'label' => 'Help & Support']
        ];
      @endphp
      @foreach($menuItems as $idx => $item)
        <div style="display:flex; align-items:center; gap:14px; padding:16px 18px; border-bottom:{{ $idx < 5 ? '1px solid #F3F4F6' : 'none' }}; cursor:pointer;">
          <span style="font-size:20px;">{{ $item['icon'] }}</span>
          <div style="flex:1; font-weight:700; font-size:14px; color:#1A1A1A;">{{ $item['label'] }}</div>
          <div style="color:#ccc;">›</div>
        </div>
      @endforeach
    </div>

    <!-- Admin Panel Shortcut -->
    <div style="text-align:center; margin-top:24px; padding:8px;">
      <a href="{{ url('/admin') }}" style="font-size:12px; color:#aaa; text-decoration:none; font-weight:600;">🛡️ Admin Operations Login</a>
    </div>

  </div>

  <!-- Mobile Bottom Nav -->
  <div class="bottom-nav">
    <a href="{{ url('/') }}" class="nav-btn">
      <span style="font-size:22px;">🏠</span>
      <span style="font-size:11px; font-weight:700; color:#aaa;">Home</span>
    </a>
    <a href="{{ url('/profile') }}" class="nav-btn">
      <span style="font-size:22px;">👤</span>
      <span style="font-size:11px; font-weight:700; color:#1A3C8F;">Profile</span>
      <div class="nav-dot"></div>
    </a>
  </div>
</div>
@endsection
