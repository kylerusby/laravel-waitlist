@if(config('waitlist.turnstile.enabled'))
<!-- Cloudflare Turnstile Script -->
<script
  src="https://challenges.cloudflare.com/turnstile/v0/api.js"
  async
  defer
></script>

<!-- Turnstile Widget -->
<div>
    <div 
        class="cf-turnstile" 
        data-sitekey="{{ config('waitlist.turnstile.site_key') }}"
        data-theme="{{ config('waitlist.turnstile.theme') }}"
        data-size="{{ config('waitlist.turnstile.size') }}"
        @if(config('waitlist.turnstile.callback'))
        data-callback="{{ config('waitlist.turnstile.callback') }}"
        @endif
    ></div>
    
    @error('cf-turnstile-response')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
@endif

