@extends('layouts.app')

@section('content')
<div class="screen">
  <!-- Header -->
  <div style="background:#00B29A; padding:20px; display:flex; align-items:center; gap:12px; border-radius: 0 0 20px 20px; flex-shrink:0; margin-bottom:16px;">
    <a href="{{ url('/') }}" class="nav-btn" style="background:rgba(255,255,255,0.2); border-radius:12px; width:38px; height:38px; color:#fff; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:18px;">←</a>
    <h2 style="color:#fff; font-weight:900; font-size:16px; margin:0;">Upload Prescription</h2>
  </div>

  <div class="scroll" style="flex:1; padding:0 16px 20px;">
    <!-- Info Section: What is a valid prescription? -->
    <div style="background:#fff; border-radius:20px; padding:16px; box-shadow:0 2px 14px rgba(0,0,0,0.04); border:1px solid #E5E7EB; margin-bottom:20px;">
      <h3 style="font-weight:900; font-size:14px; color:#1A1A1A; margin-top:0; margin-bottom:12px;">What is a valid prescription?</h3>
      
      <!-- Mockup Image Diagram -->
      <div style="background:#F3F4F6; border-radius:14px; padding:12px; display:flex; gap:12px; position:relative; overflow:hidden; border:1.5px dashed #CBD5E1;">
        <div style="width:50%; background:#fff; border-radius:8px; padding:10px; border:1px solid #E2E8F0; font-size:7px; color:#475569; display:flex; flex-direction:column; gap:6px;">
          <div style="border-bottom:1px solid #CBD5E1; padding-bottom:4px;">
            <strong style="font-size:8px; color:#0F172A; display:block;">Dr. Apurva Kumar</strong>
            <span>City Clinic, Patna</span>
          </div>
          <div>
            <strong style="display:block;">20-01-2025</strong>
          </div>
          <div>
            <strong style="display:block;">Patient: Meghna Raj (38)</strong>
          </div>
          <div style="display:flex; flex-direction:column; gap:2px; font-weight:bold; color:#0F172A;">
            <span>1. Paracetamol - 50mg</span>
            <span>2. Ibuprofen - 150mg</span>
          </div>
        </div>

        <div style="width:50%; display:flex; flex-direction:column; justify-content:center; gap:8px;">
          <div style="display:flex; align-items:center; gap:6px;">
            <span style="background:#D1FAE5; color:#065F46; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:8px; font-weight:900;">1</span>
            <span style="font-size:9.5px; font-weight:700; color:#334155;">Doctor's details</span>
          </div>
          <div style="display:flex; align-items:center; gap:6px;">
            <span style="background:#D1FAE5; color:#065F46; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:8px; font-weight:900;">2</span>
            <span style="font-size:9.5px; font-weight:700; color:#334155;">Date of prescription</span>
          </div>
          <div style="display:flex; align-items:center; gap:6px;">
            <span style="background:#D1FAE5; color:#065F46; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:8px; font-weight:900;">3</span>
            <span style="font-size:9.5px; font-weight:700; color:#334155;">Patient's details</span>
          </div>
          <div style="display:flex; align-items:center; gap:6px;">
            <span style="background:#D1FAE5; color:#065F46; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:8px; font-weight:900;">4</span>
            <span style="font-size:9.5px; font-weight:700; color:#334155;">Medicines details</span>
          </div>
        </div>
      </div>

      <!-- Guidelines list -->
      <ul style="font-size:11px; color:#666; margin:12px 0 0; padding-left:18px; line-height:1.6;">
        <li>Include details of doctor, patient & date of visit</li>
        <li>Supported formats: <strong>PNG, JPEG, JPG</strong></li>
        <li>File size limit: <strong>5 MB</strong></li>
      </ul>
    </div>

    <!-- Main Upload Form -->
    <form action="{{ url('/prescription/upload') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Upload Actions Grid -->
      <div style="background:#fff; border-radius:20px; padding:16px; box-shadow:0 2px 14px rgba(0,0,0,0.04); border:1px solid #E5E7EB; margin-bottom:20px;">
        <h4 style="font-weight:900; font-size:13.5px; color:#1A1A1A; margin-top:0; margin-bottom:12px;">Upload prescription using</h4>
        
        <input type="file" name="prescription_image" id="file-input" style="display:none;" accept="image/jpeg,image/png,image/jpg" required onchange="handleFileSelected(this)">
        <input type="file" id="camera-input" style="display:none;" accept="image/*" capture="environment" onchange="handleFileSelected(this)">
        
        <div style="display:flex; gap:10px;">
          <!-- Camera button -->
          <div onclick="triggerCameraInput()" style="flex:1; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:16px; padding:16px 8px; text-align:center; cursor:pointer; transition:all 0.2s;">
            <div style="font-size:26px; margin-bottom:6px;">📸</div>
            <div style="font-size:11px; font-weight:800; color:#475569;">Camera</div>
          </div>
          <!-- Gallery button -->
          <div onclick="triggerGalleryInput()" style="flex:1; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:16px; padding:16px 8px; text-align:center; cursor:pointer; transition:all 0.2s;">
            <div style="font-size:26px; margin-bottom:6px;">🖼️</div>
            <div style="font-size:11px; font-weight:800; color:#475569;">Gallery</div>
          </div>
          <!-- My Files button -->
          <div onclick="triggerGalleryInput()" style="flex:1; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:16px; padding:16px 8px; text-align:center; cursor:pointer; transition:all 0.2s;">
            <div style="font-size:26px; margin-bottom:6px;">📁</div>
            <div style="font-size:11px; font-weight:800; color:#475569;">My Files</div>
          </div>
        </div>

        <!-- Selected File Status Badge -->
        <div id="file-badge" style="display:none; background:#ECFDF5; border:1px solid #10B981; border-radius:10px; padding:8px 12px; margin-top:14px; align-items:center; gap:8px;">
          <span style="font-size:14px;">✅</span>
          <span id="file-name-text" style="font-size:11.5px; font-weight:700; color:#065F46; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1;">prescription.png</span>
        </div>
      </div>

      <!-- Details Card -->
      <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 2px 14px rgba(0,0,0,0.04); border:1px solid #E5E7EB; margin-bottom:20px; display:flex; flex-direction:column; gap:14px;">
        <h4 style="font-weight:900; font-size:13.5px; color:#1A1A1A; margin:0;">Patient & Delivery Details</h4>

        <!-- Patient Name -->
        <div>
          <label class="form-label" style="font-size:11.5px; margin-bottom:4px; font-weight:700; display:block; color:#555;">Patient Name</label>
          <input type="text" name="patient_name" class="form-input" style="padding:11px;" placeholder="Patient ka naam likhein" required value="{{ Auth::user()->name ?? '' }}">
        </div>

        <!-- Age & Phone -->
        <div style="display:flex; gap:12px;">
          <div style="width:80px;">
            <label class="form-label" style="font-size:11.5px; margin-bottom:4px; font-weight:700; display:block; color:#555;">Age</label>
            <input type="number" name="patient_age" class="form-input" style="padding:11px;" placeholder="Umar" min="1" max="120">
          </div>
          <div style="flex:1;">
            <label class="form-label" style="font-size:11.5px; margin-bottom:4px; font-weight:700; display:block; color:#555;">Mobile Number</label>
            <input type="tel" name="patient_phone" class="form-input" style="padding:11px;" placeholder="Contact number" required value="{{ Auth::user()->phone ?? '' }}">
          </div>
        </div>

        <!-- Delivery Address -->
        <div>
          <label class="form-label" style="font-size:11.5px; margin-bottom:6px; font-weight:700; display:block; color:#555;">Delivery Address</label>
          
          @if($pastAddresses->count() > 0)
            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px;">
              @foreach($pastAddresses as $index => $addr)
                <label style="display:flex; align-items:flex-start; gap:10px; background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:12px; padding:12px; cursor:pointer; transition:all 0.2s;" class="address-option" id="addr-card-{{ $index }}">
                  <input type="radio" name="selected_address_option" value="{{ $addr }}" style="margin-top:2px;" onclick="handleAddressSelect(this, '{{ $index }}')">
                  <div style="flex:1; font-size:11.5px; color:#334155; line-height:1.4;">
                    <strong>Delivery Address {{ $index + 1 }}:</strong>
                    <div>{{ $addr }}</div>
                  </div>
                </label>
              @endforeach

              <label style="display:flex; align-items:center; gap:10px; background:#ECFDF5; border:1.5px solid #00B29A; border-radius:12px; padding:12px; cursor:pointer; transition:all 0.2s;" class="address-option" id="addr-card-new">
                <input type="radio" name="selected_address_option" value="new" style="margin-top:2px;" onclick="handleAddressSelect(this, 'new')" checked>
                <div style="flex:1; font-size:12.5px; color:#065F46; font-weight:800;">
                  ➕ Enter New Address
                </div>
              </label>
            </div>
          @endif

          <div id="new-address-wrapper">
            <textarea name="delivery_address" id="delivery-address-textarea" class="form-input" style="padding:11px; height:60px; font-family:inherit; resize:none;" placeholder="Complete delivery address likhein" required>{{ Auth::user()->address ?? '' }}</textarea>
          </div>
        </div>

        <!-- Doctor Instructions / Notes -->
        <div>
          <label class="form-label" style="font-size:11.5px; margin-bottom:4px; font-weight:700; display:block; color:#555;">Instructions / Medicine Notes (Optional)</label>
          <textarea name="notes" class="form-input" style="padding:11px; height:60px; font-family:inherit; resize:none;" placeholder="Example: Din me 2 baar leni hai / Specific generic substitute chalega."></textarea>
        </div>
      </div>

      <!-- Action Button -->
      <button type="submit" class="btn-blue" style="width:100%; border:none; padding:14px; border-radius:14px; font-weight:900; font-size:14px; color:#fff; cursor:pointer; background:#00B29A; box-shadow:0 4px 14px rgba(0,178,154,0.3);">
        Continue to Upload Prescription
      </button>
    </form>
  </div>
</div>

<script>
  function triggerGalleryInput() {
    document.getElementById('file-input').click();
  }

  function triggerCameraInput() {
    // Try to trigger camera file capture
    document.getElementById('camera-input').click();
  }

  function handleFileSelected(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      
      // If we used camera input, synchronize files to primary input form so it submits
      if (input.id === 'camera-input') {
        const primaryInput = document.getElementById('file-input');
        primaryInput.files = input.files;
      }

      document.getElementById('file-name-text').textContent = file.name;
      document.getElementById('file-badge').style.display = 'flex';
    }
  }

  function handleAddressSelect(radio, key) {
    // Reset all options UI borders
    document.querySelectorAll('.address-option').forEach(card => {
      card.style.borderColor = '#E2E8F0';
      card.style.background = '#F8FAFC';
      // If child text exists, reset color
      const textDiv = card.querySelector('div');
      if (textDiv) textDiv.style.color = '#334155';
    });

    // Highlight selected card
    const card = document.getElementById('addr-card-' + key);
    if (card) {
      if (key === 'new') {
        card.style.borderColor = '#00B29A';
        card.style.background = '#ECFDF5';
        const textDiv = card.querySelector('div');
        if (textDiv) textDiv.style.color = '#065F46';
      } else {
        card.style.borderColor = '#1E40AF';
        card.style.background = '#EFF6FF';
        const textDiv = card.querySelector('div');
        if (textDiv) textDiv.style.color = '#1E40AF';
      }
    }

    const textarea = document.getElementById('delivery-address-textarea');
    const textareaWrapper = document.getElementById('new-address-wrapper');

    if (radio.value === 'new') {
      textarea.value = "{{ Auth::user()->address ?? '' }}";
      textarea.placeholder = 'Complete delivery address likhein';
      textareaWrapper.style.display = 'block';
      textarea.required = true;
    } else {
      textarea.value = radio.value;
      textareaWrapper.style.display = 'none';
      textarea.required = false;
    }
  }
</script>
@endsection
