@extends('layouts.app')

@section('content')
<div class="screen" style="max-width: 600px; margin: 0 auto; width: 100%;">
  <!-- Header -->
  <div class="hdr-gradient" style="padding-bottom: 24px; margin-bottom: 20px;">
    <div class="hdr-circle"></div>
    <div class="hdr-circle2"></div>

    <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:1;">
      <a href="{{ url('/profile') }}" class="nav-btn" style="background:rgba(255,255,255,0.15); border-radius:12px; width:40px; height:40px; color:#fff; font-size:18px; display:flex; align-items:center; justify-content:center; padding:0; text-decoration:none;">←</a>
      <h2 style="color:#fff; font-weight:900; font-size:20px; margin:0;">🔔 Notifications</h2>
    </div>
  </div>

  <div class="scroll" style="flex:1;">
    <div style="display:flex; flex-direction:column; gap:14px;">
      
      @foreach($notifications as $notif)
        <div style="background:#fff; border-radius:16px; padding:16px; border:1px solid #E2E8F0; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
            <strong style="font-weight:800; font-size:13.5px; color:#1A202C;">{{ $notif['title'] }}</strong>
            <span style="font-size:11px; color:#94A3B8;">{{ $notif['time'] }}</span>
          </div>
          <p style="font-size:12.5px; color:#475569; line-height:1.5; margin:0;">{{ $notif['text'] }}</p>
        </div>
      @endforeach

    </div>
  </div>
</div>
@endsection
