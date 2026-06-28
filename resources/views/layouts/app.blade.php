<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Dawalo 💊 - Aggregator & Delivery</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent;}
body{background:#F0F4FF;display:flex;flex-direction:column;min-height:100vh;font-family:'Segoe UI','Nunito',sans-serif;color:#1A1A1A;}

/* Navbar and Footer Wrappers (Full Width Backgrounds) */
.navbar-wrapper {
  background: linear-gradient(135deg,#1A3C8F,#2563EB);
  width: 100%;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(37,99,235,0.15);
}
.navbar-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 14px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #fff;
}
.brand-logo {
  font-size: 24px;
  font-weight: 800;
  color: #fff;
  cursor: pointer;
  text-decoration: none;
}
.brand-logo span {
  color: #60A5FA;
}
.nav-links {
  display: none;
  gap: 24px;
  align-items: center;
}
.nav-link {
  color: rgba(255,255,255,0.85);
  text-decoration: none;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: color 0.2s;
}
.nav-link:hover, .nav-link.active {
  color: #fff;
}
.nav-right {
  display: flex;
  align-items: center;
  gap: 14px;
}

/* App Container (Centered Content Width) */
#app{
  width:100%;
  max-width:1400px;
  min-height:75vh;
  margin: 0 auto;
  position:relative;
  display:flex;
  flex-direction:column;
  padding: 24px 20px;
}
.screen{flex:1;display:flex;flex-direction:column;overflow:hidden;}
.scroll{flex:1;overflow-y:auto;-webkit-overflow-scrolling:touch;}
.scroll::-webkit-scrollbar{width:0;}

/* Header */
.hdr-gradient{background:linear-gradient(135deg,#1A3C8F,#2563EB,#3B82F6,#60A5FA);padding:30px 24px 70px;position:relative;overflow:hidden;border-radius: 20px;margin-bottom: 24px;box-shadow: 0 4px 20px rgba(37,99,235,0.08);}
.hdr-circle{position:absolute;top:-60px;right:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,0.08);}
.hdr-circle2{position:absolute;bottom:10px;left:-50px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.06);}

/* Responsive grids */
.responsive-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
@media (min-width: 768px) {
  .responsive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
  }
  .nav-links {
    display: flex;
  }
}

/* Cards */
.card{background:#fff;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.07);}
.stat-grid{display:flex;gap:10px;padding:0 14px;margin-top:-50px;position:relative;z-index:10;}
.stat-card{flex:1;background:#fff;border-radius:14px;padding:13px 8px;text-align:center;box-shadow:0 6px 24px rgba(37,99,235,0.12);border:1px solid #F0F4FF;}

/* Buttons */
.btn-blue{background:#1A3C8F;border:none;border-radius:12px;color:#fff;font-weight:800;cursor:pointer;font-family:inherit;padding: 10px 16px;text-decoration:none;display:inline-block;text-align:center;}
.btn-outline{background:#fff;border:1.5px solid #1A3C8F;border-radius:12px;color:#1A3C8F;font-weight:800;cursor:pointer;font-family:inherit;padding: 10px 16px;text-decoration:none;display:inline-block;text-align:center;}
.btn-green{background:linear-gradient(135deg,#059669,#10B981);border:none;border-radius:12px;color:#fff;font-weight:800;cursor:pointer;font-family:inherit;padding: 10px 16px;text-decoration:none;display:inline-block;text-align:center;}
.btn-danger{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;color:#DC2626;font-weight:800;cursor:pointer;font-family:inherit;padding: 10px 16px;text-decoration:none;display:inline-block;text-align:center;}

/* Bottom Nav */
.bottom-nav{background:#fff;border-top:1px solid #E5E7EB;display:flex;justify-content:space-around;padding:10px 0 14px;flex-shrink:0;box-shadow:0 -4px 20px rgba(0,0,0,0.08);margin-top: 20px;border-radius: 12px;}
.nav-btn{display:flex;flex-direction:column;align-items:center;gap:3px;background:none;border:none;cursor:pointer;font-family:inherit;padding:4px 16px;text-decoration:none;}
.nav-dot{width:4px;height:4px;border-radius:50%;background:#1A3C8F;}

/* Search */
.search-box{background:#fff;border-radius:14px;display:flex;align-items:center;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.15);max-width: 600px;}
.search-input{flex:1;border:none;outline:none;padding:13px 14px;font-size:14px;color:#1A1A1A;background:transparent;font-family:inherit;}
.search-btn{background:#1A3C8F;border:none;padding:10px 18px;margin:4px;border-radius:10px;cursor:pointer;color:#fff;font-weight:700;font-size:14px;font-family:inherit;}

/* Pills */
.pill-row{display:flex;gap:8px;margin-top:12px;overflow-x:auto;padding-bottom:4px;}
.pill-row::-webkit-scrollbar{height:0;}
.pill{font-size:12px;font-weight:700;padding:6px 14px;border-radius:20px;white-space:nowrap;cursor:pointer;border:1px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.18);color:#fff;display:inline-block;text-decoration:none;}
.pill.active{background:#fff;color:#1A3C8F;}

/* Banner */
.banner-wrap{padding:16px 0 0;}
.banner-card{position:relative;border-radius:20px;overflow:hidden;height:110px;transition:background 0.6s;}
.banner-circle{position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.15);}
.banner-emoji{position:absolute;right:16px;top:50%;transform:translateY(-50%);font-size:56px;opacity:0.9;}
.banner-text{position:absolute;left:18px;top:50%;transform:translateY(-50%);max-width:70%;}
.banner-dots{position:absolute;bottom:10px;left:18px;display:flex;gap:6px;}

/* Category */
.cat-row{display:flex;gap:10px;overflow-x:auto;padding-bottom:4px;}
.cat-row::-webkit-scrollbar{height:0;}
.cat-item{display:flex;flex-direction:column;align-items:center;gap:6px;background:#fff;border-radius:16px;padding:13px 14px;min-width:68px;box-shadow:0 2px 12px rgba(0,0,0,0.06);cursor:pointer;text-decoration:none;}

/* Shop card */
.shop-card{background:#fff;border-radius:20px;padding:16px;box-shadow:0 2px 16px rgba(0,0,0,0.07);margin-bottom:0;}

/* Medicine list (search results) */
.med-row{display:flex;border-bottom:1px solid #F3F4F6;padding:16px;background:#fff;text-decoration:none;color:inherit;}
.med-img{width:110px;height:110px;border-radius:12px;background:#F8FAFF;border:1px solid #E5E7EB;display:flex;align-items:center;justify-content:center;font-size:52px;flex-shrink:0;position:relative;overflow:hidden;}
.bestseller{position:absolute;top:6px;left:0;background:#FEF3C7;color:#D97706;font-size:9px;font-weight:800;padding:2px 7px;border-radius:0 6px 6px 0;}
.add-btn{width:100%;padding:11px 0;background:#fff;border:1.5px solid #1A3C8F;border-radius:10px;color:#1A3C8F;font-weight:900;font-size:15px;cursor:pointer;font-family:inherit;letter-spacing:1px;text-align:center;display:block;text-decoration:none;}
.qty-row{display:flex;align-items:center;border:1.5px solid #1A3C8F;border-radius:10px;overflow:hidden;}
.qty-btn{flex:1;padding:11px 0;background:#fff;border:none;font-size:20px;font-weight:900;color:#1A3C8F;cursor:pointer;display:inline-block;text-align:center;text-decoration:none;}
.qty-num{flex:1;text-align:center;font-weight:900;font-size:16px;color:#1A3C8F;background:#EEF2FF;padding:11px 0;}

/* Cart bar */
.cart-bar{background:linear-gradient(135deg,#1A3C8F,#2563EB);border-radius:18px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 8px 28px rgba(37,99,235,0.45);margin:8px 0;}

/* Order modal */
.overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.55);z-index:500;display:flex;flex-direction:column;justify-content:center;align-items:center;padding: 20px;}
.sheet{background:#fff;border-radius:20px;padding:24px;max-width:500px;width:100%;max-height:90%;overflow-y:auto;box-shadow: 0 10px 40px rgba(0,0,0,0.25);}
.handle{width:40px;height:4px;background:#E5E7EB;border-radius:4px;margin:0 auto 18px;display:none;}

/* Smart Cart */
.cart-item-card{background:#fff;border-radius:16px;padding:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);margin-bottom:9px;display:flex;gap:11px;align-items:center;}

/* Dashboard */
.dash-tab{flex-shrink:0;padding:10px 10px;border:none;border-radius:14px;cursor:pointer;font-weight:800;font-size:11px;font-family:inherit;display:flex;flex-direction:column;align-items:center;gap:3px;text-decoration:none;}

/* Admin */
.admin-tab{flex-shrink:0;padding:8px 10px;border:none;border-radius:12px;cursor:pointer;font-weight:800;font-size:10px;font-family:inherit;display:flex;flex-direction:column;align-items:center;gap:2px;position:relative;text-decoration:none;}

/* Quick setup */
.qs-card{background:#fff;border-radius:14px;padding:11px 12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);margin-bottom:0;display:flex;align-items:center;gap:12px;}
.qs-check{width:26px;height:26px;border-radius:7px;flex-shrink:0;border:2px solid #D1D5DB;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;text-decoration:none;}
.qs-check.checked{background:#16A34A;border:none;color:#fff;font-weight:900;font-size:14px;}

/* Form inputs */
.form-input{width:100%;padding:12px 14px;border:1.5px solid #E5E7EB;border-radius:12px;font-size:14px;outline:none;color:#1A1A1A;font-family:inherit;background:#fff;transition:border 0.2s;}
.form-input:focus{border-color:#1A3C8F;}
.form-label{display:block;font-size:12px;font-weight:700;color:#666;margin-bottom:6px;}

/* Toggle switch */
.toggle-wrap{width:50px;height:28px;border-radius:99px;position:relative;cursor:pointer;transition:background 0.3s;flex-shrink:0;}
.toggle-dot{width:22px;height:22px;background:#fff;border-radius:50%;position:absolute;top:3px;transition:left 0.3s;box-shadow:0 1px 3px rgba(0,0,0,0.2);}

/* Delivery bar */
.due-bar-track{height:6px;background:#F3F4F6;border-radius:99px;overflow:hidden;margin-top:7px;}
.due-bar-fill{height:100%;border-radius:99px;}

/* Footer */
.footer-wrapper {
  background: #1F2937;
  color: #9CA3AF;
  width: 100%;
  margin-top: auto;
  border-top: 1px solid #374151;
}
.footer-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 20px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
}
@media (min-width: 768px) {
  .footer-content {
    grid-template-columns: 2fr 1fr 1fr;
  }
}
.footer-col h3 {
  color: #fff;
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 16px;
}
.footer-col p {
  font-size: 13.5px;
  line-height: 1.6;
}
.footer-col ul {
  list-style: none;
}
.footer-col ul li {
  margin-bottom: 10px;
}
.footer-col ul li a {
  color: #9CA3AF;
  text-decoration: none;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
}
.footer-col ul li a:hover {
  color: #fff;
}
.footer-bottom {
  border-top: 1px solid #374151;
  text-align: center;
  padding: 20px;
  font-size: 12px;
  color: #6B7280;
  margin-top: 20px;
}

/* Transitions */
.screen{animation:fadeIn 0.15s ease;}
@keyframes fadeIn{from{opacity:0;transform:translateY(4px);}to{opacity:1;transform:translateY(0);}}
::-webkit-scrollbar{width:0;height:0;}
.badge{font-size:10px;font-weight:800;padding:3px 10px;border-radius:8px;display:inline-block;}

/* Hide mobile bottom nav on desktop screens */
@media (min-width: 768px) {
  .bottom-nav {
    display: none;
  }
}

/* Fix mobile bottom nav on mobile screens */
@media (max-width: 767px) {
  .bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    margin: 0;
    border-radius: 0;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
  }
  body {
    padding-bottom: 80px;
  }
}
</style>
</head>
<body>

<!-- Global Navbar (Full Width) -->
<div class="navbar-wrapper">
  <div class="navbar-content">
    <a href="{{ url('/') }}" class="brand-logo">Dawa<span>lo</span> 💊</a>
    <div class="nav-links">
      <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') || Request::is('search') ? 'active' : '' }}">Home</a>
      @if(Auth::check() && Auth::user()->shop)
        <a href="{{ url('/shop/dashboard') }}" class="nav-link {{ Request::is('shop*') ? 'active' : '' }}">My Store</a>
      @elseif(Auth::check() && Auth::user()->role === 'shop_owner')
        <a href="{{ url('/profile') }}?modal=shopForm" class="nav-link">+ List Shop</a>
      @endif
      <a href="{{ url('/profile') }}" class="nav-link {{ Request::is('profile') ? 'active' : '' }}">Profile</a>
      @if(Auth::check() && Auth::user()->role === 'admin')
        <a href="{{ url('/admin') }}" class="nav-link {{ Request::is('admin*') ? 'active' : '' }}">Admin</a>
      @endif
      @if(Auth::check())
        <form action="{{ url('/logout') }}" method="POST" style="display:inline; margin:0; padding:0;">
          @csrf
          <button type="submit" class="nav-link" style="background:none; border:none; font-family:inherit; font-size:14px; font-weight:700; cursor:pointer; color: rgba(255,255,255,0.85); transition: color 0.2s;">Logout ({{ Auth::user()->name }})</button>
        </form>
      @else
        <a href="{{ url('/login') }}" class="nav-link {{ Request::is('login') ? 'active' : '' }}">Login</a>
      @endif
    </div>
    <div class="nav-right">
      <span style="font-size:13px; font-weight:700; color:#60A5FA; cursor:pointer;" onclick="openGlobalLocationModal()">📍 {{ session('user_location', 'Muzaffarpur') }}</span>
      <div style="background:rgba(255,255,255,0.15); border-radius:12px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:16px; cursor:pointer;">🔔</div>
    </div>
  </div>
</div>

<!-- Main App Screen Container (Centered Grid Bounds) -->
<div id="app">


  @yield('content')
</div>

<!-- Global Footer (Full Width) -->
<div class="footer-wrapper">
  <div class="footer-content">
    <!-- Col 1: About -->
    <div class="footer-col">
      <h3>Dawalo 💊</h3>
      <p>{{ session('user_location', 'Muzaffarpur') }}'s primary online medicine aggregator. Finding and checking live pharmacy stock in 5 km coverage. Order directly and get deliveries in under 45 mins.</p>
    </div>
    <!-- Col 2: Navigation -->
    <div class="footer-col">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="{{ url('/') }}">Home Search</a></li>
        <li><a href="{{ url('/smartcart') }}">Smart Cart Auto Match</a></li>
        <li><a href="{{ url('/profile') }}?modal=shopForm">Store Dashboard Registration</a></li>
        <li><a href="{{ url('/admin') }}">Admin Operations Panel</a></li>
      </ul>
    </div>
    <!-- Col 3: Support -->
    <div class="footer-col">
      <h3>Get In Touch</h3>
      <ul>
        <li><span style="font-size:13.5px;">📧 support@dawalo.in</span></li>
        <li><span style="font-size:13.5px;">📞 +91 9876543210</span></li>
        <li><span style="font-size:13.5px;">📍 {{ session('user_location', 'Muzaffarpur') }}, Bihar</span></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    © 2026 Dawalo App. All rights reserved. Persistent DB powered by Laravel & MySQL.
  </div>
</div>

<!-- Global Location Selection Modal -->
<div id="global-location-modal" class="overlay" style="display:none; justify-content:center; align-items:center;">
  <div class="sheet" style="max-width:400px; text-align:center;">
    <h3 style="font-weight:900; font-size:18px; margin-bottom:12px; color:#1A202C;">Select City</h3>
    <p style="font-size:12px; color:#718096; margin-bottom:20px;">Apni city select karein pharmacies aur items dekhne ke liye:</p>
    <div style="display:flex; flex-direction:column; gap:10px;">
      <a href="{{ url('/set-location?city=Muzaffarpur') }}" class="btn-outline" style="text-decoration:none; padding:12px; display:block;">Muzaffarpur</a>
      <a href="{{ url('/set-location?city=Patna') }}" class="btn-outline" style="text-decoration:none; padding:12px; display:block;">Patna</a>
      <a href="{{ url('/set-location?city=Jaipur') }}" class="btn-outline" style="text-decoration:none; padding:12px; display:block;">Jaipur</a>
      <a href="{{ url('/set-location?city=Darbhanga') }}" class="btn-outline" style="text-decoration:none; padding:12px; display:block;">Darbhanga</a>
    </div>
    <button onclick="closeGlobalLocationModal()" class="btn-danger" style="margin-top:20px; width:100%; border:none; padding:12px; font-size:13px; cursor:pointer;">Cancel</button>
  </div>
</div>

<script>
function openGlobalLocationModal() {
  document.getElementById('global-location-modal').style.display = 'flex';
}
function closeGlobalLocationModal() {
  document.getElementById('global-location-modal').style.display = 'none';
}
</script>

</body>
</html>
