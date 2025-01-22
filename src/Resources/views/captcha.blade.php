<form method="POST" action="/captcha">
    @csrf
    @if(config('siteprotection.captcha.provider') === 'recaptcha')
        <div class="g-recaptcha" data-sitekey="{{ config('siteprotection.captcha.providers.recaptcha.site_key') }}"></div>
    @endif
    <button type="submit">Verify</button>
</form>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
