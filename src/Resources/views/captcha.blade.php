<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Проверка</title>

        @if($theme == 'white')
            <style>
                :root {
                    --bg-color: #ffffff;
                    --section-bg-color: #f6f6f6;
                    --text-color: #212529;
                    --link-color: #0045f5;
                }
            </style>
        @else
            <style>
                :root {
                    --bg-color: #1c1c1c;
                    --section-bg-color: #282828;
                    --text-color: #ededed;
                    --link-color: #0045f5;
                }
            </style>
        @endif

        <style>
            :root {
                --border-radius: 10px;
                --transition-sec: .3;
            }
            body, html {
                background: var(--bg-color);
                color: var(--text-color);
                margin: 0;
                padding: 0;
                scrollbar-width: none;
                font-family: -apple-system, system-ui, "Helvetica Neue", Roboto, sans-serif;
                font-size: 16px;
                overflow: auto;
            }
            * {
                box-sizing: border-box;
            }
            section {
                max-width: 350px;
            }
            p {
                margin-top: 0;
            }
            body {
                min-width: 100vw;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .card {
                background-color: var(--section-bg-color);
                padding: 25px 27px;
                border-radius: var(--border-radius);
                min-width: 300px;
            }
            a {
                color: var(--link-color);
                text-decoration: none;
                transition: var(--transition-sec);
            }
            .alert {
                background-color: var(--section-bg-color);
                padding: 15px 20px;
                border-radius: var(--border-radius);
                margin-bottom: 20px;
            }
            .alert-danger {
                background-color: #eb4646;
                color: #fff;
            }
        </style>

        @if($provider == 'yandex')
            <script
                    src="https://smartcaptcha.yandexcloud.net/captcha.js?render=onload&onload=onloadFunction"
                    async
                    defer
            ></script>
            <script>
                function onloadFunction() {
                    if (window.smartCaptcha) {
                        const container = document.getElementById('captcha');
                        const widgetId = window.smartCaptcha.render(container, {
                            sitekey: '{{ $siteKey }}'
                        });

                        const submit = document.getElementById('submit');
                        window.smartCaptcha.subscribe(
                            widgetId,
                            'success',
                            () => {
                                submit.click();
                            }
                        );
                    }
                }
            </script>
        @elseif($provider == 'recaptcha')
            <script src="https://www.google.com/recaptcha/api.js?onload=onloadFunction&render=explicit"
                    async defer>
            </script>
            <script type="text/javascript">
                const verifyCallback = function(response) {
                    const submit = document.getElementById('submit');
                    submit.click();
                };
                const onloadFunction = function() {
                    grecaptcha.render('captcha', {
                        'sitekey' : '{{ $siteKey }}',
                        'callback' : verifyCallback,
                        'theme' : '{{ $theme }}'
                    });
                };
            </script>
        @endif

    </head>
    <body>
        <section>

            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif

            <div class="card">

                <form method="POST" action="{{ route('site-protection.captcha.verify') }}">
                    @csrf
                    @if($description)
                        {!! $description !!}
                    @endif

                    <div id="captcha"></div>


                    <button id="submit" type="submit" style="display: none">Проверить</button>

                </form>

            </div>



        </section>
    </body>
</html>