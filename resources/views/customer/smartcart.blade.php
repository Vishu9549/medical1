@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Header -->
  <div class="hdr-gradient" id="cart-header-gradient" style="padding-bottom: 24px; margin-bottom: 20px; transition: all 0.3s ease-in-out;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div id="cart-header-title-block" style="display:flex; align-items:center; gap:12px; margin-bottom:14px; position:relative; z-index:1; transition: all 0.3s ease-in-out; max-height: 100px; opacity: 1; overflow: hidden;">
      <a href="{{ url('/') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0;">←</a>
      <div style="flex:1;">
        <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">🛒 Smart Cart</h2>
        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin:0;">Best pharmacy auto-match hogi</p>
      </div>
      <div id="header-cart-badge" style="background:#fff; border-radius:14px; padding:8px 14px; display:{{ $cartCount > 0 ? 'flex' : 'none' }}; align-items:center; gap:6px;">
        <span>🛒</span>
        <strong style="font-weight:900; font-size:14px; color:#1A3C8F;">{{ $cartCount }}</strong>
      </div>
    </div>

    <!-- Search in Smart Cart -->
    <form action="{{ url('/smartcart') }}" method="GET" class="search-box" id="cart-search-form" style="position:relative; z-index:99; margin-bottom:0;" onsubmit="event.preventDefault(); triggerCartSearch();">
      <input name="q" id="cart-search-input" class="search-input" placeholder="Medicine ya category likhein..." type="text" autocomplete="off" oninput="debouncedCartSearchSuggestions(this.value)">
      <button type="submit" class="search-btn">Filter</button>
      
      <!-- Autocomplete Dropdown suggestions list -->
      <div id="cart-search-autocomplete" style="display:none; position:absolute; left:0; right:0; top:100%; background:#fff; border-radius:14px; margin-top:8px; box-shadow:0 10px 25px rgba(0,0,0,0.15); border:1px solid #E5E7EB; max-height:260px; overflow-y:auto; z-index:99999;"></div>
    </form>
  </div>

  <!-- Medicine Catalog Selection -->
  <div class="scroll" id="cart-scroll-container" style="flex:1;">
    <div class="responsive-grid">
      @include('customer.smartcart_items_inner')
    </div>
  </div>

  <!-- Checkout Button Container -->
  <div id="smartcart-checkout-bar" style="background:#fff; border-top:1px solid #E5E7EB; padding:12px 16px 20px; flex-shrink:0; border-radius:14px; margin-top:16px; {{ $cartCount > 0 ? 'display:block;' : 'display:none;' }}">
    <a href="{{ url('/smartcart/results') }}" class="btn-blue" style="width:100%; padding:15px; background:linear-gradient(135deg,#1A3C8F,#2563EB); border:none; border-radius:14px; color:#fff; font-weight:900; font-size:15px; display:block; text-align:center; text-decoration:none;">
      🔍 Best Pharmacy Dhundho — <span id="checkout-item-count">{{ $cartCount }}</span> items →
    </a>
  </div>

  <!-- Floating Action Button (FAB) for quick checkout -->
  <a href="{{ url('/smartcart/results') }}" id="smartcart-fab" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; border-radius: 50%; width: 56px; height: 56px; display: {{ $cartCount > 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center; background: linear-gradient(135deg,#1A3C8F,#2563EB); color: #fff; font-size: 22px; box-shadow: 0 8px 24px rgba(37,99,235,0.4); text-decoration: none; border: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1.0)'">
    🛒
  </a>
</div>

<script>
  function attachCartSubmitHandlers(container) {
    container.querySelectorAll('.cart-form').forEach(form => {
      if (form.dataset.hasListener) return;
      form.dataset.hasListener = "true";

      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const url = this.getAttribute('action');
        const formData = new FormData(this);

        fetch(url, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const medControls = form.closest('.cart-controls');
            const cardEl = form.closest('.cart-item-card');
            
            const addForm = medControls.querySelector('.add-form-el');
            const qtyControl = medControls.querySelector('.qty-control-el');
            const qtyDisplay = medControls.querySelector('.qty-display');
            const qtyInputDec = medControls.querySelector('.qty-input-dec');
            const qtyInputInc = medControls.querySelector('.qty-input-inc');

            if (data.qty === 0) {
              addForm.style.display = 'block';
              qtyControl.style.display = 'none';
              if (cardEl) cardEl.style.borderColor = 'transparent';
            } else {
              addForm.style.display = 'none';
              qtyControl.style.display = 'flex';
              qtyDisplay.innerText = data.qty;
              qtyInputDec.value = data.qty - 1;
              qtyInputInc.value = data.qty + 1;
              if (cardEl) cardEl.style.borderColor = '#BFDBFE';
            }

            // Update checkout button count
            const checkoutBar = document.getElementById('smartcart-checkout-bar');
            const checkoutCountSpan = document.getElementById('checkout-item-count');
            const checkoutFab = document.getElementById('smartcart-fab');
            
            if (data.cartCount > 0) {
              if (checkoutBar) checkoutBar.style.display = 'block';
              if (checkoutCountSpan) checkoutCountSpan.innerText = data.cartCount;
              if (checkoutFab) checkoutFab.style.display = 'flex';
            } else {
              if (checkoutBar) checkoutBar.style.display = 'none';
              if (checkoutFab) checkoutFab.style.display = 'none';
            }

            // Update top header count badge
            let headerBadge = document.getElementById('header-cart-badge');
            if (headerBadge) {
              if (data.cartCount > 0) {
                headerBadge.style.display = 'flex';
                headerBadge.querySelector('strong').innerText = data.cartCount;
              } else {
                headerBadge.style.display = 'none';
              }
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          this.submit();
        });
      });
    });
  }

  window.addEventListener('DOMContentLoaded', () => {
    attachCartSubmitHandlers(document);
  });

  // Autocomplete Suggestions
  let autocompleteTimeout;
  function debouncedCartSearchSuggestions(query) {
    clearTimeout(autocompleteTimeout);
    const dropdown = document.getElementById('cart-search-autocomplete');
    const q = query.trim().toLowerCase();

    if (q.length === 0) {
      dropdown.style.display = 'none';
      restoreCartHeader();
      filterCatalogItems('');
      return;
    }

    // Shrink header instantly on first character
    shrinkCartHeader();
    filterCatalogItems(q);

    autocompleteTimeout = setTimeout(() => {
      fetch(`{{ url('/medicines/search') }}?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
          dropdown.innerHTML = '';
          if (data.length === 0) {
            dropdown.style.display = 'none';
            return;
          }
          dropdown.style.display = 'block';
          data.forEach(item => {
            const row = document.createElement('div');
            row.style.padding = '12px 16px';
            row.style.cursor = 'pointer';
            row.style.borderBottom = '1px solid #F3F4F6';
            row.style.fontSize = '13px';
            row.style.fontWeight = '700';
            row.style.color = '#1A1A1A';
            row.style.display = 'flex';
            row.style.alignItems = 'center';
            row.style.gap = '8px';
            row.innerHTML = `<span style="font-size:18px;">${item.emoji || '💊'}</span> <span>${item.name}</span> <span style="font-size:11px; color:#888; margin-left:auto; font-weight:normal;">in ${item.category}</span>`;
            row.addEventListener('click', () => {
              document.getElementById('cart-search-input').value = item.name;
              dropdown.style.display = 'none';
              triggerCartSearch();
            });
            dropdown.appendChild(row);
          });
        })
        .catch(err => console.error(err));
    }, 150);
  }

  let currentPage = 1;
  let isPageLoading = false;
  let hasMorePages = true;
  let activeSearchQuery = '';

  const scrollContainer = document.getElementById('cart-scroll-container');
  const itemsGrid = scrollContainer.querySelector('.responsive-grid');

  scrollContainer.addEventListener('scroll', () => {
    if (isPageLoading || !hasMorePages) return;
    
    // Check if scrolled near bottom
    const threshold = 150;
    const position = scrollContainer.scrollHeight - scrollContainer.scrollTop - scrollContainer.clientHeight;
    
    if (position < threshold) {
      loadNextBundle();
    }
  });

  function loadNextBundle() {
    isPageLoading = true;
    currentPage++;
    
    const loadingIndicator = document.createElement('div');
    loadingIndicator.id = 'infinite-scroll-loading';
    loadingIndicator.style.width = '100%';
    loadingIndicator.style.textAlign = 'center';
    loadingIndicator.style.padding = '15px';
    loadingIndicator.style.fontSize = '12px';
    loadingIndicator.style.color = '#888';
    loadingIndicator.innerText = '📦 Loading more medicines...';
    itemsGrid.appendChild(loadingIndicator);

    const url = `{{ url('/smartcart') }}?page=${currentPage}&q=${encodeURIComponent(activeSearchQuery)}`;
    
    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(res => res.json())
      .then(data => {
        const oldLoader = document.getElementById('infinite-scroll-loading');
        if (oldLoader) oldLoader.remove();
        
        if (data.html && data.html.trim().length > 0) {
          const temp = document.createElement('div');
          temp.innerHTML = data.html;
          while (temp.firstChild) {
            itemsGrid.appendChild(temp.firstChild);
          }
          attachCartSubmitHandlers(itemsGrid);
        }
        
        hasMorePages = data.hasMore;
        isPageLoading = false;
      })
      .catch(err => {
        console.error(err);
        const oldLoader = document.getElementById('infinite-scroll-loading');
        if (oldLoader) oldLoader.remove();
        isPageLoading = false;
      });
  }

  let searchRequestTimeout;
  function triggerServerSearch(q) {
    clearTimeout(searchRequestTimeout);
    searchRequestTimeout = setTimeout(() => {
      activeSearchQuery = q;
      currentPage = 1;
      hasMorePages = true;
      isPageLoading = true;
      
      const url = `{{ url('/smartcart') }}?page=1&q=${encodeURIComponent(q)}`;
      fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(res => res.json())
        .then(data => {
          itemsGrid.innerHTML = data.html || '';
          attachCartSubmitHandlers(itemsGrid);
          hasMorePages = data.hasMore;
          isPageLoading = false;
        })
        .catch(err => {
          console.error(err);
          isPageLoading = false;
        });
    }, 200);
  }

  function shrinkCartHeader() {
    const titleBlock = document.getElementById('cart-header-title-block');
    const headerGradient = document.getElementById('cart-header-gradient');

    if (titleBlock) {
      titleBlock.style.maxHeight = '0';
      titleBlock.style.opacity = '0';
      titleBlock.style.marginBottom = '0';
    }
    if (headerGradient) {
      headerGradient.style.paddingBottom = '12px';
      headerGradient.style.marginBottom = '12px';
    }
  }

  function restoreCartHeader() {
    const titleBlock = document.getElementById('cart-header-title-block');
    const headerGradient = document.getElementById('cart-header-gradient');

    if (titleBlock) {
      titleBlock.style.maxHeight = '100px';
      titleBlock.style.opacity = '1';
      titleBlock.style.marginBottom = '14px';
    }
    if (headerGradient) {
      headerGradient.style.paddingBottom = '24px';
      headerGradient.style.marginBottom = '20px';
    }
  }

  function triggerCartSearch() {
    const q = document.getElementById('cart-search-input').value.trim();
    shrinkCartHeader();
    triggerServerSearch(q);
  }

  // Close suggestions list on click outside
  document.addEventListener('click', function(e) {
    if (e.target.id !== 'cart-search-input') {
      const results = document.getElementById('cart-search-autocomplete');
      if (results) results.style.display = 'none';
    }
  });
</script>
@endsection
