@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- === HEADER === -->
  <div class="hdr-gradient">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="margin-bottom:18px; position:relative; z-index:1;">
      <h1 style="color:#fff; font-size:32px; font-weight:900; line-height:1.2; margin:0 0 8px;">
        Dawai dhundo,<br><span style="color:#93C5FD">5 km ke andar</span> 💊
      </h1>
      <p style="color:rgba(255,255,255,0.75); font-size:14px; margin:0;">Nearby medical shops ka live stock — order karo ghar se</p>
    </div>

    <!-- Search Form -->
    <form action="{{ url('/search') }}" method="GET" class="search-box" style="position:relative; z-index:1;">
      <input name="q" class="search-input" placeholder="Medicine ka naam likhein..." type="text" value="{{ request('q') }}">
      <button type="submit" class="search-btn">🔍 Search</button>
    </form>

    <!-- Popular/Pills -->
    <div style="display:flex; gap:8px; margin-top:12px; overflow-x:auto; padding-bottom:4px; position:relative; z-index:1;">
      @foreach($pills as $pill)
        <a href="{{ url('/search?q='.$pill) }}" class="pill">{{ $pill }}</a>
      @endforeach
    </div>
  </div>

  <!-- === MAIN CONTENT AREA === -->
  <div class="scroll" style="flex:1; padding-bottom:8px;">
    
    <!-- Stats Row -->
    <div style="display:flex; gap:10px; margin-bottom:20px;">
      <div class="stat-card">
        <div style="font-size:18px; margin-bottom:2px;">🏪</div>
        <div style="font-size:22px; font-weight:900; color:#1A3C8F;">{{ $shopsCount }}</div>
        <div style="font-size:11px; color:#888; font-weight:600; margin-top:2px;">Medical Shops</div>
      </div>
      <div class="stat-card">
        <div style="font-size:18px; margin-bottom:2px;">🟢</div>
        <div style="font-size:22px; font-weight:900; color:#059669;">{{ $onlineShopsCount }}</div>
        <div style="font-size:11px; color:#888; font-weight:600; margin-top:2px;">Online Now</div>
      </div>
      <div class="stat-card">
        <div style="font-size:18px; margin-bottom:2px;">📍</div>
        <div style="font-size:22px; font-weight:900; color:#D97706;">5km</div>
        <div style="font-size:11px; color:#888; font-weight:600; margin-top:2px;">Coverage</div>
      </div>
    </div>

    <!-- Banners -->
    <div class="banner-wrap" style="margin-bottom:20px;">
      <div class="banner-card" id="banner-carousel" style="background:#F97316; transition:background 0.5s ease;">
        <div class="banner-circle"></div>
        <div class="banner-emoji" id="banner-emoji">🎉</div>
        <div class="banner-text">
          <div id="banner-title" style="color:#fff; font-weight:900; font-size:16px; line-height:1.3; margin-bottom:4px;">Pehla Order Free Delivery! 🎉</div>
          <div id="banner-sub" style="color:rgba(255,255,255,0.85); font-size:12px; font-weight:600;">Naye users ke liye special offer</div>
        </div>
      </div>
    </div>

    <!-- Categories -->
    <div style="padding:12px 0 24px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
        <div style="font-weight:800; font-size:16px; color:#1A1A1A;">Categories</div>
        <div style="font-size:13px; color:#2563EB; font-weight:700;">See all</div>
      </div>
      <div class="cat-row">
        @foreach($categories as $cat)
          <a href="{{ url('/search?q='.$cat['label']) }}" class="cat-item">
            <div style="width:40px; height:40px; border-radius:12px; background:{{ $cat['color'] }}; display:flex; align-items:center; justify-content:center; font-size:20px;">
              {{ $cat['icon'] }}
            </div>
            <div style="font-size:11px; font-weight:700; color:#444;">{{ $cat['label'] }}</div>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Smart Cart Banner -->
    <div style="padding:0 0 20px;">
      <a href="{{ url('/smartcart') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg,#7C3AED,#A855F7); border-radius:20px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 20px rgba(124,58,237,0.3);">
          <div>
            <div style="color:#fff; font-weight:800; font-size:15px; margin-bottom:4px;">🛒 Smart Cart</div>
            <div style="color:rgba(255,255,255,0.8); font-size:12px;">Multiple medicines — best pharmacy auto-match</div>
          </div>
          <div style="color:#fff; font-size:24px;">→</div>
        </div>
      </a>
    </div>

    <!-- Nearby Pharmacies -->
    <div style="padding:0 0 20px;">
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
        <div style="width:10px; height:10px; border-radius:50%; background:#22C55E; box-shadow:0 0 0 4px rgba(34,197,94,0.3);"></div>
        <div style="font-weight:800; font-size:16px; color:#1A1A1A;">Open Nearby Pharmacies</div>
      </div>
      
      <div class="responsive-grid" id="shops-list-container">
        @foreach($shops as $shop)
          <div class="shop-card-wrapper" 
               data-id="{{ $shop->id }}" 
               data-lat="{{ $shop->latitude ?? 0 }}" 
               data-lng="{{ $shop->longitude ?? 0 }}"
               data-dist="{{ $shop->distance_km }}"
               style="border: {{ $shop->is_top ? '2px solid #DBEAFE' : '2px solid transparent' }}; background:#fff; border-radius:20px; padding:16px; box-shadow:0 2px 16px rgba(0,0,0,0.07); margin-bottom:0;">
            <div style="display:flex; gap:14px; align-items:center;">
              <div style="width:56px; height:56px; border-radius:16px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; font-size:26px; flex-shrink:0;">🏥</div>
              <div style="flex:1;">
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:2px;">
                  <span style="font-weight:800; font-size:14px; color:#1A1A1A;">{{ $shop->name }}</span>
                  @if($shop->is_top)
                    <div style="background:#DBEAFE; color:#1D4ED8; font-size:10px; font-weight:700; padding:2px 8px; border-radius:6px;">⭐ Top</div>
                  @endif
                </div>
                <div style="color:#888; font-size:12px; margin-bottom:4px;">📍 {{ $shop->area }}</div>
                <div style="display:flex; gap:8px; align-items:center;">
                  <span style="font-size:12px; color:#F59E0B; font-weight:700;">★ {{ number_format($shop->rating, 1) }}</span>
                  <span style="font-size:11px; color:#aaa;">({{ $shop->reviews }})</span>
                  <span style="font-size:11px; color:#888; font-weight:800;" class="shop-distance-text">{{ $shop->distance_km }} km</span>
                </div>
              </div>
              <div>
                <a href="{{ url('/search?shop_id='.$shop->id) }}" class="btn-blue" style="font-size:12px; padding:8px 12px;">Order</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Interactive Location Map -->
    <div style="padding:0 0 24px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <div style="font-weight:800; font-size:16px; color:#1A1A1A; display:flex; align-items:center; gap:8px;">
          <span>🗺️</span> Interactive Shops Map
        </div>
        <button onclick="requestGeolocation()" class="btn-outline" style="font-size:11px; padding:6px 10px; border-radius:8px; display:flex; align-items:center; gap:4px; border:1px solid #1A3C8F;">
          📍 Get Location
        </button>
      </div>
      <div style="position: relative;">
        <div id="home-map" style="height: 300px; width: 100%; border-radius: 18px; border: 2px solid #E5E7EB; box-shadow: 0 4px 16px rgba(0,0,0,0.06); z-index: 1;"></div>
        <!-- Floating Selected Shop Badge on Map -->
        <div id="map-selected-shop-overlay" style="display: none; position: absolute; top: 12px; left: 50%; transform: translateX(-50%); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border: 1.5px solid #1A3C8F; border-radius: 12px; padding: 6px 14px; font-weight: 800; font-size: 12px; color: #1A3C8F; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.12); align-items: center; gap: 6px;">
          🏪 <span id="map-selected-shop-name">Sharma Medical Store</span>
        </div>
      </div>
    </div>

  </div>

  <!-- Cart floating bar -->
  @if($cartCount > 0)
    <div class="cart-bar" style="position: sticky; bottom: 20px; z-index: 99;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="background:#fff; border-radius:10px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; color:#1A3C8F;">
          {{ $cartCount }}
        </div>
        <div>
          <div style="color:#fff; font-weight:800; font-size:13px;">Cart mein {{ $cartCount }} item{{ $cartCount > 1 ? 's' : '' }}</div>
          <div style="color:rgba(255,255,255,0.7); font-size:11px;">Pharmacy auto-match hogi</div>
        </div>
      </div>
      <a href="{{ url('/smartcart') }}" class="btn-outline" style="background:#fff; color:#1A3C8F; border:none; padding:10px 16px; font-size:13px;">Checkout →</a>
    </div>
  @endif

  <!-- Mobile Bottom Nav -->
  <div class="bottom-nav">
    <a href="{{ url('/') }}" class="nav-btn">
      <span style="font-size:22px;">🏠</span>
      <span style="font-size:11px; font-weight:700; color:#1A3C8F;">Home</span>
      <div class="nav-dot"></div>
    </a>
    <a href="{{ url('/profile') }}" class="nav-btn">
      <span style="font-size:22px;">👤</span>
      <span style="font-size:11px; font-weight:700; color:#aaa;">Profile</span>
    </a>
  </div>
</div>

<script>
  // Simple JS slider for banners
  const banners = [
    {title:"Pehla Order Free Delivery! 🎉", sub:"Naye users ke liye special offer", bg:"#F97316", emoji:"🛵"},
    {title:"24x7 Emergency Medicines 🚨", sub:"Raat ko bhi dawai milegi", bg:"#7C3AED", emoji:"🌙"},
    {title:"Apni Dukan List Karein 🏪", sub:"Free mein register karo", bg:"#059669", emoji:"📈"}
  ];
  let bannerIdx = 0;
  setInterval(() => {
    bannerIdx = (bannerIdx + 1) % banners.length;
    const b = banners[bannerIdx];
    const el = document.getElementById('banner-carousel');
    if (el) {
      el.style.background = b.bg;
      document.getElementById('banner-emoji').innerText = b.emoji;
      document.getElementById('banner-title').innerText = b.title;
      document.getElementById('banner-sub').innerText = b.sub;
    }
  }, 4000);

  // Map Integration using Leaflet
  let map;
  let userMarker;
  const initialLat = 26.1209;
  const initialLng = 85.3647; // Muzaffarpur Center

  const shopsData = [
    @foreach($shops as $shop)
      {
        id: {{ $shop->id }},
        name: "{{ $shop->name }}",
        lat: {{ $shop->latitude ?? 0 }},
        lng: {{ $shop->longitude ?? 0 }},
        url: "{{ url('/search?shop_id='.$shop->id) }}"
      },
    @endforeach
  ];

  let markers = {};

  function initMap() {
    map = L.map('home-map').setView([initialLat, initialLng], 14);

    const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    });

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    streetMap.addTo(map);

    const baseMaps = {
      "Street View 🗺️": streetMap,
      "Satellite View 🛰️": satelliteMap
    };

    L.control.layers(baseMaps).addTo(map);

    // Place pharmacy markers on the map
    shopsData.forEach(shop => {
      if (shop.lat && shop.lng) {
        const marker = L.marker([shop.lat, shop.lng])
          .addTo(map)
          .bindPopup(`<strong>${shop.name}</strong><br><a href="${shop.url}" class="btn-blue" style="font-size:11px; padding:4px 8px; text-decoration:none; display:inline-block; color:#fff; border-radius:6px; margin-top:6px;">Order Now</a>`);
        markers[shop.id] = marker;
      }
    });

    setupCardClickHandlers();
  }

  function setupCardClickHandlers() {
    document.querySelectorAll('.shop-card-wrapper').forEach(card => {
      if (card.dataset.hasListener) return;
      card.dataset.hasListener = "true";
      card.style.cursor = 'pointer';
      
      card.addEventListener('click', function(e) {
        const id = this.getAttribute('data-id');
        window.location.href = "{{ url('/search') }}?shop_id=" + id;
      });
    });
  }

  // Haversine formula to compute distance in km
  function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of Earth in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
      Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return parseFloat((R * c).toFixed(1));
  }

  function requestGeolocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(position => {
        const uLat = position.coords.latitude;
        const uLng = position.coords.longitude;

        // Center map on user location
        map.setView([uLat, uLng], 14);

        if (userMarker) {
          userMarker.setLatLng([uLat, uLng]);
        } else {
          // Custom blue icon for User Location
          const blueIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
          });
          userMarker = L.marker([uLat, uLng], { icon: blueIcon }).addTo(map).bindPopup("<b>Aap Yahan Hain! 📍</b>").openPopup();
        }

        // Calculate and update distances dynamically
        const shopWrappers = Array.from(document.querySelectorAll('.shop-card-wrapper'));
        
        shopWrappers.forEach(wrap => {
          const sLat = parseFloat(wrap.getAttribute('data-lat'));
          const sLng = parseFloat(wrap.getAttribute('data-lng'));
          
          if (sLat && sLng) {
            const distance = calculateDistance(uLat, uLng, sLat, sLng);
            wrap.setAttribute('data-dist', distance);
            wrap.querySelector('.shop-distance-text').innerText = `${distance} km`;
          }
        });

        // Re-sort the shops dynamically in the DOM
        const container = document.getElementById('shops-list-container');
        shopWrappers.sort((a, b) => {
          return parseFloat(a.getAttribute('data-dist')) - parseFloat(b.getAttribute('data-dist'));
        });

        // Clear and re-append sorted items
        container.innerHTML = "";
        shopWrappers.forEach(wrap => container.appendChild(wrap));

        setupCardClickHandlers();

      }, error => {
        console.warn("Geolocation permission denied:", error);
      });
    }
  }

  // Initialize Map and request location automatically on page load
  window.addEventListener('DOMContentLoaded', () => {
    initMap();
    requestGeolocation();
  });
</script>
@endsection
