@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <div class="scroll" style="padding:20px 0;">
    
    <!-- User Profile Header Card -->
    <div style="background:linear-gradient(135deg,#1A3C8F,#2563EB,#3B82F6); border-radius:20px; padding:24px 20px; text-align:center; margin-bottom:20px; box-shadow:0 8px 32px rgba(37,99,235,0.2);">
      <div style="width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 10px;">👤</div>
      @if(Auth::check())
        <div style="color:#fff; font-weight:800; font-size:17px;">{{ Auth::user()->name }}</div>
        <div style="color:rgba(255,255,255,0.85); font-size:13px; margin-top:2px;">📞 {{ Auth::user()->phone ?? 'No Phone' }}</div>
        <span style="background:rgba(255,255,255,0.2); color:#fff; font-size:10px; font-weight:800; padding:3px 10px; border-radius:12px; display:inline-block; margin-top:8px; text-transform:uppercase;">
          🛡️ {{ Auth::user()->role }}
        </span>
      @else
        <div style="color:#fff; font-weight:800; font-size:16px;">Guest User</div>
        <div style="color:rgba(255,255,255,0.7); font-size:12px; margin-top:2px;">Login/Register directly with your phone number</div>
      @endif
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

          <!-- Shop Timings -->
          <div style="display:flex; gap:10px; margin-bottom:14px;">
            <div style="flex:1;">
              <label class="form-label">Opening Time *</label>
              <input type="time" name="opens_at" class="form-input" value="09:00" required>
            </div>
            <div style="flex:1;">
              <label class="form-label">Closing Time *</label>
              <input type="time" name="closes_at" class="form-input" value="21:00" required>
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
              <input type="checkbox" name="delivery_enabled" value="1" checked style="opacity:0; width:0; height:0;" onchange="toggleProfileDeliverySection(this.checked)">
              <span class="switch-slider" style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#10B981; transition:.4s; border-radius:24px;"></span>
            </label>
          </div>

          <!-- Delivery Charge Options Container -->
          <div id="profile-delivery-charges-container" style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:14px; padding:14px; margin-bottom:14px;">
            <div style="font-weight:800; font-size:13px; color:#1A1A1A; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
              💰 Delivery Charge
            </div>
            <div style="font-size:11px; color:#666; margin-bottom:12px;">Aap decide karein customer se kitna delivery charge lena hai</div>

            <!-- Buttons Selector -->
            <div style="display:flex; gap:8px; margin-bottom:12px;">
              <button type="button" id="btn-del-free" onclick="setProfileDeliveryType('free')" style="flex:1; padding:8px; border:1.5px solid #CBD5E1; background:#fff; border-radius:10px; font-weight:700; font-size:12px; color:#475569; cursor:pointer; outline:none;">🎁 Free</button>
              <button type="button" id="btn-del-perkm" onclick="setProfileDeliveryType('perkm')" style="flex:1; padding:8px; border:2px solid #1A3C8F; background:#EFF6FF; border-radius:10px; font-weight:700; font-size:12px; color:#1A3C8F; cursor:pointer; outline:none;">🖊️ Per KM</button>
              <button type="button" id="btn-del-fixed" onclick="setProfileDeliveryType('fixed')" style="flex:1; padding:8px; border:1.5px solid #CBD5E1; background:#fff; border-radius:10px; font-weight:700; font-size:12px; color:#475569; cursor:pointer; outline:none;">📦 Fixed</button>
            </div>

            <!-- Value Input -->
            <input type="hidden" name="delivery_charge_type" id="profile-del-type" value="dynamic">
            
            <div id="profile-del-rate-wrapper">
              <label class="form-label" id="profile-del-rate-label" style="font-size:11.5px; font-weight:700; color:#555; margin-bottom:4px; display:block;">Rate per KM (₹)</label>
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-weight:800; color:#1A1A1A; font-size:14px;">₹</span>
                <input type="number" step="0.5" name="delivery_charge_rate" id="profile-del-rate-input" class="form-input" style="flex:1; padding:8px 10px; font-size:13px;" value="8">
                <span style="font-size:12px; color:#666;" id="profile-del-rate-suffix">/ km</span>
              </div>
              <div style="font-size:11px; color:#888; margin-top:6px;" id="profile-del-example">Example: 3 km &times; ₹8 = ₹24 delivery charge</div>
            </div>
          </div>

          <!-- Discount Offers (Customer Savings) -->
          <div style="background:#FFFDF5; border:1px solid #FEF3C7; border-radius:14px; padding:14px; margin-bottom:14px;">
            <div style="font-weight:800; font-size:13px; color:#B45309; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
              🎁 Discount Offers (Customer Savings)
            </div>
            <div style="display:flex; gap:10px;">
              <div style="flex:1;">
                <label class="form-label" style="font-size:11.5px; font-weight:700; color:#92400E; margin-bottom:4px; display:block;">Min Bill Amount (₹)</label>
                <input type="number" step="any" name="offer_min_bill" class="form-input" style="padding:8px 10px; font-size:13px;" value="0.00">
              </div>
              <div style="flex:1;">
                <label class="form-label" style="font-size:11.5px; font-weight:700; color:#92400E; margin-bottom:4px; display:block;">Discount Percentage (%)</label>
                <input type="number" step="any" name="offer_discount_pct" class="form-input" style="padding:8px 10px; font-size:13px;" value="0.00">
              </div>
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

        function toggleProfileDeliverySection(checked) {
          const container = document.getElementById('profile-delivery-charges-container');
          if (checked) {
            container.style.display = 'block';
          } else {
            container.style.display = 'none';
          }
        }

        function setProfileDeliveryType(type) {
          const btnFree = document.getElementById('btn-del-free');
          const btnPerKm = document.getElementById('btn-del-perkm');
          const btnFixed = document.getElementById('btn-del-fixed');
          const typeInput = document.getElementById('profile-del-type');
          const rateWrapper = document.getElementById('profile-del-rate-wrapper');
          const rateLabel = document.getElementById('profile-del-rate-label');
          const rateInput = document.getElementById('profile-del-rate-input');
          const rateSuffix = document.getElementById('profile-del-rate-suffix');
          const rateExample = document.getElementById('profile-del-example');

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
    @endif

    <!-- Profile Menu List -->
    <div style="background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
      @php
        $menuItems = [
          ['icon' => '📋', 'label' => 'My Orders', 'url' => url('/profile/orders')],
          ['icon' => '📍', 'label' => 'Saved Addresses', 'url' => url('/profile/addresses')],
          ['icon' => '❤️', 'label' => 'Favourite Shops', 'url' => url('/profile/favourites')],
          ['icon' => '🔔', 'label' => 'Notifications', 'url' => url('/profile/notifications')],
          ['icon' => '⚙️', 'label' => 'Settings', 'url' => url('/profile/settings')],
          ['icon' => '❓', 'label' => 'Help & Support', 'url' => url('/profile/help')]
        ];
      @endphp
      @foreach($menuItems as $idx => $item)
        <a href="{{ $item['url'] }}" style="display:flex; align-items:center; gap:14px; padding:16px 18px; border-bottom:1px solid #F3F4F6; cursor:pointer; text-decoration:none;">
          <span style="font-size:20px;">{{ $item['icon'] }}</span>
          <div style="flex:1; font-weight:700; font-size:14px; color:#1A1A1A;">{{ $item['label'] }}</div>
          <div style="color:#ccc;">›</div>
        </a>
      @endforeach
      @if(Auth::check())
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="display:flex; align-items:center; gap:14px; padding:16px 18px; cursor:pointer; text-decoration:none;">
          <span style="font-size:20px; color:#DC2626;">🚪</span>
          <div style="flex:1; font-weight:700; font-size:14px; color:#DC2626;">Logout</div>
          <div style="color:#DC2626;">›</div>
        </a>
      @endif
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
