@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Shop Header -->
  <div class="hdr-gradient" style="padding:24px 20px 24px; position:relative; overflow:hidden; flex-shrink:0; border-radius: 20px; margin-bottom:20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; position:relative; z-index:1;">
      <a href="{{ url('/shop/dashboard') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:17px; margin:0;">{{ $shop->name }}</h2>
        <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:0;">Quick Setup Inventory Setup</p>
      </div>
    </div>
  </div>

  <!-- Dashboard Navigation Menu Bar -->
  <div style="display:flex; background:#fff; padding:10px 10px; gap:6px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow-x:auto; flex-shrink:0; border-radius:14px; margin-bottom:20px;">
    <a href="{{ url('/shop/dashboard') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📊</span>Overview
    </a>
    <a href="{{ url('/shop/quicksetup') }}" class="dash-tab active" style="background:#1A3C8F; color:#fff; flex:1; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
      <span style="font-size:16px;">⚡</span>Quick Setup
    </a>
    <a href="{{ url('/shop/inventory') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📦</span>Inventory
    </a>
    <a href="{{ url('/shop/orders') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">📋</span>Orders
    </a>
    <a href="{{ url('/shop/settings') }}" class="dash-tab" style="background:#F3F4F6; color:#888; flex:1;">
      <span style="font-size:16px;">⚙️</span>Settings
    </a>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="background:linear-gradient(135deg,#1A3C8F,#2563EB); border-radius:12px; padding:10px 14px; margin-bottom:12px; color:#fff; display:flex; align-items:center; gap:8px;">
      <span style="font-size:18px;">🏪</span>
      <div>
        <div style="font-weight:900; font-size:13px;">Medicine Catalogue Selection</div>
        <div style="font-size:10.5px; opacity:0.85; margin-top:2px;">Select medicines, set price/stock, and save.</div>
      </div>
    </div>

    <!-- Search & Brand Filter Layout (GET Form for Server-side filtering) -->
    <form method="GET" action="{{ url('/shop/quicksetup') }}" id="search-filter-form" style="margin-bottom:16px;">
      <input type="hidden" name="category" value="{{ $category }}">
      
      <div style="background:#fff; border-radius:18px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.06); display:flex; flex-direction:column; gap:16px;">
        <div style="display:flex; flex-direction:column; align-items:stretch; width:100%;">
          <label class="form-label" style="margin-bottom:6px; font-size:13.5px; font-weight:800; color:#1A3C8F; display:block;">🔍 Search Medicine Name</label>
          <div style="display:flex; gap:10px; width:100%;">
            <input type="text" name="q" id="catalogue-search" value="{{ $search }}" class="form-input" style="padding:15px 16px; font-size:15px; border-radius:14px; flex:1; box-sizing:border-box;" placeholder="Type to search (e.g. Paracetamol)...">
            <button type="submit" class="btn-blue" style="border-radius:14px; padding:15px 24px; font-weight:900; font-size:14px; border:none; cursor:pointer; color:#fff;">Search</button>
          </div>
        </div>
        
        <div style="display:flex; flex-direction:column; align-items:stretch; width:100%;">
          <label class="form-label" style="margin-bottom:6px; font-size:13.5px; font-weight:800; color:#1A3C8F; display:block;">🏭 Filter by Company / Brand</label>
          <select name="company" id="company-filter" class="form-input" style="padding:15px 16px; font-size:15px; border-radius:14px; height:auto; width:100%; box-sizing:border-box;" onchange="document.getElementById('search-filter-form').submit()">
            <option value="All">All Companies</option>
            @foreach($allCompanies as $comp)
              <option value="{{ $comp }}" {{ $company === $comp ? 'selected' : '' }}>{{ $comp }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>

    <!-- Category Pills Filter -->
    @php
      $cats = ['All', 'Tablet', 'Liquid', 'Powder', 'Injection', 'Ointment/Cream'];
    @endphp
    <div style="display:flex; gap:8px; margin-bottom:14px; overflow-x:auto; padding-bottom:4px;">
      @foreach($cats as $c)
        <a href="{{ url('/shop/quicksetup?category='.urlencode($c).'&q='.urlencode($search).'&company='.urlencode($company)) }}" style="flex-shrink:0; padding:7px 16px; border-radius:20px; background:{{ $category === $c ? '#1A3C8F' : '#F3F4F6' }}; color:{{ $category === $c ? '#fff' : '#555' }}; font-weight:700; font-size:12px; text-decoration:none;">
          {{ $c }}
        </a>
      @endforeach
    </div>

    <!-- Form for Quick Setup inventory list -->
    <form action="{{ url('/shop/quicksetup') }}" method="POST">
      @csrf
      <input type="hidden" name="shop_id" value="{{ $shop->id }}">

      <div class="responsive-grid" id="catalogue-grid" style="display:flex; flex-direction:column; gap:12px;">
        @foreach($masterMedicines as $med)
          @php
            $hasInShop = $shopInventory->contains('medicine_id', $med->id);
            $shopPrice = $shopInventory->firstWhere('medicine_id', $med->id)?->price ?? $med->price;
            $shopQty = $shopInventory->firstWhere('medicine_id', $med->id)?->quantity ?? 50;
          @endphp
          <div class="qs-card catalogue-row-item" 
               data-name="{{ strtolower($med->name) }}" 
               data-generic="{{ strtolower($med->generic_name) }}" 
               data-company="{{ strtolower($med->company) }}" 
               style="border: {{ $hasInShop ? '2px solid #3B82F6' : '2px solid transparent' }}; display:flex; align-items:center; gap:12px; background:#fff; padding:14px; border-radius:16px; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
            
            <!-- Checkbox Select -->
            <div style="flex-shrink:0; display:flex; align-items:center;">
              <input type="checkbox" name="qs_sel[m{{ $med->id }}][has]" value="true" style="width:22px; height:22px; cursor:pointer;" {{ $hasInShop ? 'checked' : '' }} class="qs-toggle">
            </div>

            <!-- Medicine Icon & Details -->
            <div style="width:48px; height:48px; border-radius:12px; flex-shrink:0; background:#F8FAFF; display:flex; align-items:center; justify-content:center; font-size:24px; overflow:hidden;">
              @if(!empty($med->images))
                @php
                  $img = $med->images[0];
                  $src = (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) ? $img : asset($img);
                @endphp
                <img src="{{ $src }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                {{ $med->emoji }}
              @endif
            </div>
            
            <div style="flex:1; min-width:0;">
              <div style="font-weight:800; font-size:14px; color:#1A1A1A; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                {{ $med->name }}
                <span style="font-size:10px; background:#EFF6FF; color:#1E40AF; padding:2px 8px; border-radius:12px; font-weight:700;">{{ $med->strength }}</span>
              </div>
              <div style="font-size:11.5px; color:#555; margin-top:2px;">
                <strong>Generic:</strong> {{ $med->generic_name }}
              </div>
              <div style="font-size:11px; color:#888; margin-top:1px;">
                <strong>Mfg:</strong> {{ $med->company }} • MRP ₹{{ $med->mrp }}
              </div>
            </div>

            <!-- Price & Stock Level Inputs -->
            <div style="flex-shrink:0; display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
              <div style="display:flex; align-items:center; gap:4px;">
                <span style="font-size:11px; font-weight:700; color:#555;">₹</span>
                <input type="number" step="0.01" name="qs_sel[m{{ $med->id }}][price]" value="{{ $shopPrice }}" style="width:70px; padding:6px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; font-weight:800; text-align:center; outline:none;" class="qs-price" placeholder="Price">
              </div>
              <div style="display:flex; align-items:center; gap:4px;">
                <span style="font-size:10px; color:#888;">Qty:</span>
                <input type="number" name="qs_sel[m{{ $med->id }}][qty]" value="{{ $shopQty }}" style="width:70px; padding:6px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; font-weight:700; text-align:center; outline:none;" placeholder="Stock">
              </div>
            </div>

          </div>
        @endforeach
      </div>

      <!-- Custom Clean Pagination Controls -->
      <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; padding:10px 0;">
        @if($masterMedicines->onFirstPage())
          <span style="padding:10px 18px; background:#F3F4F6; color:#9CA3AF; border-radius:10px; font-weight:700; font-size:12px;">Previous</span>
        @else
          <a href="{{ $masterMedicines->previousPageUrl() }}" class="btn-outline" style="padding:10px 18px; font-size:12px; text-decoration:none; display:inline-block; border-radius:10px;">Previous</a>
        @endif

        <span style="font-weight:800; font-size:12.5px; color:#555;">
          Page {{ $masterMedicines->currentPage() }} / {{ $masterMedicines->lastPage() }}
        </span>

        @if($masterMedicines->hasMorePages())
          <a href="{{ $masterMedicines->nextPageUrl() }}" class="btn-blue" style="padding:10px 18px; font-size:12px; text-decoration:none; display:inline-block; border-radius:10px; color:#fff;">Next</a>
        @else
          <span style="padding:10px 18px; background:#F3F4F6; color:#9CA3AF; border-radius:10px; font-weight:700; font-size:12px;">Next</span>
        @endif
      </div>

      <div style="height:20px;"></div>
      <button type="submit" class="btn-blue" style="width:100%; padding:16px; font-weight:900; font-size:15px; border-radius:14px; box-shadow:0 4px 16px rgba(37,99,235,0.3); border:none; color:#fff; cursor:pointer;">
        💾 Add Selected Medicines
      </button>
    </form>
  </div>
</div>

<script>
  // Highlight selection dynamically
  document.querySelectorAll('.qs-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const card = this.closest('.qs-card');
      if (this.checked) {
        card.style.borderColor = '#3B82F6';
      } else {
        card.style.borderColor = 'transparent';
      }
    });
  });

  // Client-side instant filter catalogue list
  function filterCatalogueList() {
    const searchVal = document.getElementById('catalogue-search').value.toLowerCase().trim();
    const companyVal = document.getElementById('company-filter').value.toLowerCase().trim();

    document.querySelectorAll('.catalogue-row-item').forEach(row => {
      const name = row.getAttribute('data-name');
      const generic = row.getAttribute('data-generic');
      const company = row.getAttribute('data-company');

      // Search matches either Name, Generic Formula, or Manufacturer Company/Brand
      const matchesSearch = !searchVal || 
                            name.includes(searchVal) || 
                            generic.includes(searchVal) || 
                            company.includes(searchVal);

      const matchesCompany = companyVal === 'all' || company === companyVal;

      if (matchesSearch && matchesCompany) {
        row.style.display = 'flex';
      } else {
        row.style.display = 'none';
      }
    });
  }
</script>
@endsection
