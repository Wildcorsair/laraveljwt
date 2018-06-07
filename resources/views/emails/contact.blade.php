<div style="padding: 25px;">
  <h3>You recived new message!</h3>

  <div style="margin: 5px 0;"><strong>Name:</strong> {{ $name }}</div>
  <div style="margin: 5px 0;"><strong>E-Mail:</strong> {{ $email }}</div>
  <div style="margin: 5px 0;"><strong>Residence:</strong> {{ $residence }}</div>
  <div style="margin: 5px 0;"><strong>Profile:</strong></div>
  <div style="margin: 5px 0;">
    <ul>
      @if (!is_null($seed_investor))
        <li>{{ $seed_investor }}</li>
      @endif
      @if (!is_null($service_provider))
        <li>{{ $service_provider }}</li>
      @endif
      @if (!is_null($retail_investor))
        <li>{{ $retail_investor }}</li>
      @endif
      @if (!is_null($institutional))
        <li>{{ $institutional }}</li>
      @endif
      @if (!is_null($government))
        <li>{{ $government }}</li>
      @endif
      @if (!is_null($media))
        <li>{{ $media }}</li>
      @endif
    </ul>
  </div>
  <div style="margin: 5px 0;"><strong>Message:</strong></div>
  <div style="border: 1px solid #ccc; border-radius: 6px; padding: 20px;">{{ $text }}</div>
</div>
