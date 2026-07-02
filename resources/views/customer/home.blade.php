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
    <div style="display:flex; gap:6px; margin-bottom:12px; padding:0 2px;">
      <div style="flex:1; background:#fff; border-radius:10px; padding:5px 2px; text-align:center; box-shadow:0 1px 6px rgba(0,0,0,0.03); border:1px solid #F0F4FF;">
        <div style="font-size:12px; margin-bottom:0px;">🏪</div>
        <div style="font-size:15px; font-weight:900; color:#1A3C8F; line-height:1.1;">{{ $shopsCount }}</div>
        <div style="font-size:8.5px; color:#666; font-weight:700; margin-top:0px; white-space:nowrap;">Medical Shops</div>
      </div>
      <div style="flex:1; background:#fff; border-radius:10px; padding:5px 2px; text-align:center; box-shadow:0 1px 6px rgba(0,0,0,0.03); border:1px solid #F0F4FF;">
        <div style="font-size:12px; margin-bottom:0px;">🟢</div>
        <div style="font-size:15px; font-weight:900; color:#059669; line-height:1.1;">{{ $onlineShopsCount }}</div>
        <div style="font-size:8.5px; color:#666; font-weight:700; margin-top:0px; white-space:nowrap;">Online Now</div>
      </div>
      <div style="flex:1; background:#fff; border-radius:10px; padding:5px 2px; text-align:center; box-shadow:0 1px 6px rgba(0,0,0,0.03); border:1px solid #F0F4FF;">
        <div style="font-size:12px; margin-bottom:0px;">📍</div>
        <div style="font-size:15px; font-weight:900; color:#D97706; line-height:1.1;">5km</div>
        <div style="font-size:8.5px; color:#666; font-weight:700; margin-top:0px; white-space:nowrap;">Coverage</div>
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
    <div style="padding:0 0 16px;">
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

    <!-- Prescription Upload Banner -->
    <div style="padding:0 0 20px;">
      <a href="{{ url('/prescription/upload') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg,#1E40AF,#3B82F6); border-radius:20px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 20px rgba(37,99,235,0.25);">
          <div>
            <div style="color:#fff; font-weight:800; font-size:15px; margin-bottom:4px;">📋 Prescription hai?</div>
            <div style="color:rgba(255,255,255,0.85); font-size:12px;">Upload karo, shop dhundh denge</div>
          </div>
          <div style="background:#fff; color:#1E40AF; font-size:12.5px; font-weight:800; padding:8px 16px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); white-space:nowrap;">Upload ↑</div>
        </div>
      </a>
    </div>

    <!-- Nearby Pharmacies -->
    <div style="padding:0 0 20px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
        <div style="display:flex; align-items:center; gap:8px;">
          <div style="width:10px; height:10px; border-radius:50%; background:#22C55E; box-shadow:0 0 0 4px rgba(34,197,94,0.3);"></div>
          <div style="font-weight:800; font-size:16px; color:#1A1A1A;">Open Nearby Pharmacies</div>
        </div>
        <!-- Filter Controls -->
        <div style="display:flex; gap:6px;">
          <button onclick="filterShops('city')" id="btn-filter-city" class="btn-blue" style="font-size:11px; padding:6px 12px; border-radius:10px; border:none; cursor:pointer;">My City</button>
          <button onclick="filterShops('all')" id="btn-filter-all" class="btn-outline" style="font-size:11px; padding:6px 12px; border-radius:10px; border:none; cursor:pointer; background:#fff; color:#1A3C8F; border:1.5px solid #1A3C8F;">All Shops</button>
          <button onclick="sortShopsByDistance()" id="btn-sort-dist" class="btn-outline" style="font-size:11px; padding:6px 12px; border-radius:10px; border:none; cursor:pointer; background:#fff; color:#1A3C8F; border:1.5px solid #1A3C8F;">Near Me</button>
        </div>
      </div>
      
      <div class="responsive-grid" id="shops-list-container">
        @foreach($shops as $shop)
          <div class="shop-card-wrapper" 
               data-id="{{ $shop->id }}" 
               data-city="{{ stripos($shop->address, 'Patna') !== false ? 'Patna' : (stripos($shop->address, 'Jaipur') !== false ? 'Jaipur' : (stripos($shop->address, 'Darbhanga') !== false ? 'Darbhanga' : 'Muzaffarpur')) }}"
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
                <div style="font-size:11px; margin-top:4px;">
                  @php
                    $isOpen = $shop->isOpen();
                    $opens = date('h:i A', strtotime($shop->opens_at ?? '09:00'));
                    $closes = date('h:i A', strtotime($shop->closes_at ?? '21:00'));
                  @endphp
                  @if($isOpen)
                    <span style="color:#16A34A; font-weight:800;">🟢 Open</span> 
                    <span style="color:#666;">(Closes {{ $closes }})</span>
                  @else
                    <span style="color:#DC2626; font-weight:800;">🔴 Closed</span> 
                    <span style="color:#666;">(Opens {{ $opens }})</span>
                  @endif
                </div>
              </div>
              <div>
                @if($isOpen)
                  <a href="{{ url('/search?shop_id='.$shop->id) }}" class="btn-blue" style="font-size:12px; padding:8px 12px; text-decoration:none;">Order</a>
                @else
                  <span style="font-size:12px; padding:8px 12px; background:#F3F4F6; color:#9CA3AF; border-radius:10px; font-weight:800; display:inline-block; text-align:center;">Closed</span>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>



  </div>

  <!-- Cart floating bar -->
  @if($cartCount > 0)
    <div class="cart-bar" style="position: fixed; bottom: 84px; left: 50%; transform: translateX(-50%); width: calc(100% - 40px); max-width: 600px; z-index: 9999; margin: 0;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="background:#fff; border-radius:10px; width:32px; height:32px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; color:#1A3C8F;">
          {{ $cartCount }}
        </div>
        <div>
          <div style="color:#fff; font-weight:800; font-size:13px;">Cart mein {{ $cartCount }} item{{ $cartCount > 1 ? 's' : '' }}</div>
          <div style="color:rgba(255,255,255,0.7); font-size:11px;">Pharmacy auto-match hogi</div>
        </div>
      </div>
      <a href="{{ url('/smartcart') }}" class="btn-outline" style="background:#fff; color:#1A3C8F; border:none; padding:10px 16px; font-size:13px; text-decoration:none;">Checkout →</a>
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

        // Auto detect and update closest city if not set in sessionStorage
        if (!sessionStorage.getItem('location_auto_detected')) {
          const cities = [
            { name: 'Muzaffarpur', lat: 26.1209, lng: 85.3647 },
            { name: 'Patna', lat: 25.5941, lng: 85.1376 },
            { name: 'Jaipur', lat: 26.9124, lng: 75.7873 },
            { name: 'Darbhanga', lat: 26.1542, lng: 85.8918 }
          ];
          
          let closestCity = 'Muzaffarpur';
          let minDist = Infinity;
          
          cities.forEach(c => {
            const d = calculateDistance(uLat, uLng, c.lat, c.lng);
            if (d < minDist) {
              minDist = d;
              closestCity = c.name;
            }
          });
          
          sessionStorage.setItem('location_auto_detected', 'true');
          const currentSessionCity = "{{ session('user_location', 'Muzaffarpur') }}";
          if (closestCity !== currentSessionCity) {
            window.location.href = "{{ url('/set-location') }}?city=" + closestCity;
            return;
          }
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

  let activeFilter = 'city';

  function filterShops(type) {
    activeFilter = type;
    const currentCity = "{{ session('user_location', 'Muzaffarpur') }}";

    // Toggle active styles on buttons
    const btnCity = document.getElementById('btn-filter-city');
    const btnAll = document.getElementById('btn-filter-all');
    const btnSort = document.getElementById('btn-sort-dist');

    if (type === 'city') {
      btnCity.className = 'btn-blue';
      btnCity.style.background = '#1A3C8F';
      btnCity.style.color = '#fff';

      btnAll.className = 'btn-outline';
      btnAll.style.background = '#fff';
      btnAll.style.color = '#1A3C8F';

      btnSort.className = 'btn-outline';
      btnSort.style.background = '#fff';
      btnSort.style.color = '#1A3C8F';
    } else if (type === 'all') {
      btnCity.className = 'btn-outline';
      btnCity.style.background = '#fff';
      btnCity.style.color = '#1A3C8F';

      btnAll.className = 'btn-blue';
      btnAll.style.background = '#1A3C8F';
      btnAll.style.color = '#fff';

      btnSort.className = 'btn-outline';
      btnSort.style.background = '#fff';
      btnSort.style.color = '#1A3C8F';
    }

    document.querySelectorAll('.shop-card-wrapper').forEach(card => {
      const cardCity = card.getAttribute('data-city');
      if (type === 'city') {
        if (cardCity === currentCity) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      } else {
        card.style.display = 'block';
      }
    });
  }

  function sortShopsByDistance() {
    const btnCity = document.getElementById('btn-filter-city');
    const btnAll = document.getElementById('btn-filter-all');
    const btnSort = document.getElementById('btn-sort-dist');

    btnCity.className = 'btn-outline';
    btnCity.style.background = '#fff';
    btnCity.style.color = '#1A3C8F';

    btnAll.className = 'btn-outline';
    btnAll.style.background = '#fff';
    btnAll.style.color = '#1A3C8F';

    btnSort.className = 'btn-blue';
    btnSort.style.background = '#1A3C8F';
    btnSort.style.color = '#fff';

    requestGeolocation();
  }

  // Initialize and request location automatically on page load
  window.addEventListener('DOMContentLoaded', () => {
    requestGeolocation();
    filterShops('city');
  });
</script>
@endsection
