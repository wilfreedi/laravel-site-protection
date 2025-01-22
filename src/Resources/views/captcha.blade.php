@if (Session::has('error'))
    <div class="alert alert-danger">
        <p style="margin-bottom: 0;">{{ Session::get('error') }}</p>
    </div>
@endif

<form method="POST" action="{{ route('site-protection.captcha.verify') }}">
    @csrf

    @if($provider == 'recaptcha')
        <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
    @endif

    <button type="submit">Verify</button>

</form>

@if($provider == 'recaptcha')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
