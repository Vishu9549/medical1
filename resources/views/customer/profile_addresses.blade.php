@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
      <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">📍 Saved Addresses</h2>
    </div>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="display:flex; flex-direction:column; gap:14px;">
      
      @if($orders->isEmpty())
        <div style="background:#fff; border-radius:20px; padding:20px; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.05); color:#888;">
          <div style="font-size:36px; margin-bottom:8px;">🗺️</div>
          <div style="font-weight:700; font-size:14px;">Koi details nahi hain</div>
          <div style="font-size:12px; margin-top:2px;">Home delivery order setup karne par address auto-save ho jata hai.</div>
        </div>
      @else
        @foreach($orders as $address)
          <div style="background:#fff; border-radius:16px; padding:16px; border:1px solid #E2E8F0; box-shadow:0 2px 8px rgba(0,0,0,0.03); display:flex; gap:12px; align-items:flex-start;">
            <div style="font-size:20px; background:#F1F5F9; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center;">🏠</div>
            <div style="flex:1;">
              <div style="font-weight:800; font-size:13.5px; color:#1A202C;">Home Address</div>
              <p style="font-size:12px; color:#64748B; margin-top:4px; line-height:1.5;">{{ $address }}</p>
            </div>
          </div>
        @endforeach
      @endif

    </div>
  </div>
</div>
@endsection
