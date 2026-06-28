@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
      <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">❓ Help & Support</h2>
    </div>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="background:#fff; border-radius:20px; padding:20px; border:1px solid #E2E8F0; box-shadow:0 4px 16px rgba(0,0,0,0.04); display:flex; flex-direction:column; gap:14px;">
      
      <div>
        <h4 style="font-weight:800; font-size:14px; color:#1A202C; margin-top:0; margin-bottom:6px;">📧 Email Support</h4>
        <p style="font-size:13px; color:#475569; margin:0;">Aap humein email bhej sakte hain:<br><a href="mailto:support@dawalo.com" style="color:#1A3C8F; font-weight:700; text-decoration:none;">support@dawalo.com</a></p>
      </div>

      <div style="border-top:1px solid #F1F5F9; padding-top:14px;">
        <h4 style="font-weight:800; font-size:14px; color:#1A202C; margin-top:0; margin-bottom:6px;">📞 Phone Support</h4>
        <p style="font-size:13px; color:#475569; margin:0;">Humein toll-free call karein:<br><strong style="color:#1A3C8F;">1800-123-4567</strong></p>
      </div>

      <div style="border-top:1px solid #F1F5F9; padding-top:14px;">
        <h4 style="font-weight:800; font-size:14px; color:#1A202C; margin-top:0; margin-bottom:6px;">🕒 Business Hours</h4>
        <p style="font-size:13px; color:#475569; margin:0;">Hum 24/7 online pharmacies match karne ke liye available hain.</p>
      </div>

    </div>
  </div>
</div>
@endsection
