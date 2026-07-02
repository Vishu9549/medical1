@extends('layouts.app')

@section('content')
<div class="screen" style="background:#F8FAFC;">
  <!-- Header -->
  <div style="background:#00B29A; padding:20px; text-align:center; border-radius:0 0 24px 24px; color:#fff; position:relative; overflow:hidden;">
    <div style="font-size:46px; margin-bottom:8px; animation: scaleUp 0.4s ease-out;">🎉</div>
    <h2 style="font-weight:900; font-size:18px; margin:0 0 4px;">Upload Successful!</h2>
    <p style="color:rgba(255,255,255,0.85); font-size:12px; margin:0;">Prescription request has been placed</p>
  </div>

  <div class="scroll" style="flex:1; padding:20px 16px;">
    <!-- Main Status Card -->
    <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 16px rgba(0,0,0,0.04); border:1px solid #E2E8F0; margin-bottom:20px; text-align:center;">
      <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#888; font-weight:800; margin-bottom:6px;">Prescription Reference ID</div>
      <div style="font-size:16px; font-weight:900; color:#1A3C8F; margin-bottom:12px;">#RX-{{ str_pad($prescription->id, 5, '0', STR_PAD_LEFT) }}</div>
      
      <!-- Progress Tracker -->
      <div style="background:#ECFDF5; border-radius:12px; padding:10px 14px; border:1px solid #10B981; display:inline-flex; align-items:center; gap:8px;">
        <span style="width:8px; height:8px; border-radius:50%; background:#10B981; display:block;"></span>
        <span style="font-size:12px; font-weight:800; color:#065F46;">Status: {{ $prescription->status }}</span>
      </div>
    </div>

    <!-- Assigned Store Card -->
    @if($prescription->shop)
      <div style="background:#fff; border-radius:20px; padding:16px; box-shadow:0 4px 16px rgba(0,0,0,0.04); border:1px solid #E2E8F0; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
        <div style="width:44px; height:44px; background:#EEF2FF; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px;">🏥</div>
        <div style="flex:1;">
          <div style="font-size:10px; font-weight:800; color:#888; text-transform:uppercase;">Assigned Store</div>
          <div style="font-size:13.5px; font-weight:900; color:#1A1A1A; margin-top:2px;">{{ $prescription->shop->name }}</div>
          <div style="font-size:11px; color:#666; margin-top:1px;">📍 {{ $prescription->shop->area }}</div>
        </div>
      </div>
    @endif

    <!-- Request Details Card -->
    <div style="background:#fff; border-radius:20px; padding:18px; box-shadow:0 4px 16px rgba(0,0,0,0.04); border:1px solid #E2E8F0; margin-bottom:20px; display:flex; flex-direction:column; gap:12px;">
      <h3 style="font-weight:900; font-size:13px; color:#1A1A1A; margin:0; border-bottom:1px solid #F1F5F9; padding-bottom:8px;">Patient Details</h3>
      
      <div style="display:flex; justify-content:space-between; font-size:12px;">
        <span style="color:#666;">Patient Name:</span>
        <strong style="color:#1A1A1A;">{{ $prescription->patient_name }}</strong>
      </div>
      <div style="display:flex; justify-content:space-between; font-size:12px;">
        <span style="color:#666;">Patient Age:</span>
        <strong style="color:#1A1A1A;">{{ $prescription->patient_age ?? 'N/A' }} Yrs</strong>
      </div>
      <div style="display:flex; justify-content:space-between; font-size:12px;">
        <span style="color:#666;">Contact Number:</span>
        <strong style="color:#1A1A1A;">{{ $prescription->patient_phone }}</strong>
      </div>
      <div style="display:flex; flex-direction:column; gap:2px; font-size:12px; border-top:1px solid #F1F5F9; padding-top:8px;">
        <span style="color:#666;">Delivery Address:</span>
        <strong style="color:#1A1A1A; line-height:1.4; margin-top:2px;">{{ $prescription->delivery_address }}</strong>
      </div>

      @if($prescription->notes)
        <div style="display:flex; flex-direction:column; gap:2px; font-size:12px; background:#F8FAFC; border-radius:10px; padding:10px; border:1px solid #E2E8F0;">
          <span style="color:#666; font-weight:700;">Instructions:</span>
          <span style="color:#475569; margin-top:2px;">{{ $prescription->notes }}</span>
        </div>
      @endif

      <div style="border-top:1px solid #F1F5F9; padding-top:10px; text-align:center;">
        <span style="font-size:10px; color:#888; font-weight:800; text-transform:uppercase; display:block; margin-bottom:6px;">Uploaded Prescription Document</span>
        <a href="{{ $prescription->image_path }}" target="_blank" style="display:inline-block;">
          <img src="{{ $prescription->image_path }}" style="max-width:120px; border-radius:10px; border:2px solid #E2E8F0; box-shadow:0 2px 6px rgba(0,0,0,0.06);" alt="Prescription">
        </a>
      </div>
    </div>

    <!-- Info Notice Box -->
    <div style="background:#EFF6FF; border:1px solid #BFDBFE; border-radius:16px; padding:14px; display:flex; gap:10px; align-items:flex-start; margin-bottom:20px;">
      <span style="font-size:18px;">💡</span>
      <div style="font-size:11.5px; color:#1E40AF; line-height:1.5;">
        <strong>Agla Kadam:</strong> Pharmacy aapse contact karegi, prescription check karegi aur total bill verify karake delivery / pick up shuru karegi.
      </div>
    </div>

    <!-- Back to Home Action Button -->
    <a href="{{ url('/') }}" class="btn-blue" style="display:block; text-align:center; padding:14px; border-radius:14px; font-weight:900; font-size:13.5px; color:#fff; text-decoration:none; background:#00B29A; box-shadow:0 4px 12px rgba(0,178,154,0.3);">
      Go Back to Home
    </a>
  </div>
</div>
@endsection
