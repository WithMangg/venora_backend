<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Opnicare</title>
    <!-- CSS files -->

    <link rel="shortcut icon" href="{{ asset('dist/img/icon.png') }}" type="image/x-icon">

    <link href="./dist/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }

      .btn-gr {
            background-color: #CAAC14;
            color: white;
        }

        .btn-outline-gr {
            --tblr-btn-color: #CAAC14;
            --tblr-btn-bg: transparent;
            --tblr-btn-border-color: #CAAC14;
            --tblr-btn-hover-color: var(--tblr-primary-fg);
            --tblr-btn-hover-border-color: transparent;
            --tblr-btn-hover-bg: #CAAC14;
            --tblr-btn-active-color: var(--tblr-primary-fg);
            --tblr-btn-active-bg: #CAAC14;
            --tblr-btn-disabled-color: #CAAC14;
            --tblr-btn-disabled-border-color: #CAAC14;
        }
    </style>
  </head>
  <body  class=" d-flex flex-column bg-white">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="row g-0 flex-fill">
      <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
        <div class="container container-tight my-5 px-lg-5">
            <x-auth-session-status class="mb-4" :status="session('status')" />
          <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark"><img src="{{ asset('images/venora_logo.png') }}" height="100" alt=""></a>
          </div>
          <h2 class="h3 text-center mb-3">
            Login to your account
          </h2>
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>
            <div class="mb-2">
              <label class="form-label">
                Password
                <span class="form-label-description">
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                </span>
              </label>
              <div class="input-group input-group-flat">
                <input id="password"
                type="password"
                name="password"
                required autocomplete="current-password" class="form-control" >
                <span class="input-group-text">
                  <a href="#" class="link-secondary" onclick="togglePassword()" title="Show password" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                  </a>
                </span>
              </div>
              <script>
                function togglePassword() {
                  var x = document.getElementById("password");
                  if (x.type === "password") {
                    x.type = "text";
                  } else {
                    x.type = "password";
                  }
                }
              </script>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>
            <div class="mb-2">
              <label class="form-check">
                <input id="remember_me" type="checkbox" name="remember" class="form-check-input"/>
                <span class="form-check-label">Remember me on this device</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-gr w-100">Sign in</button>
            </div>
          </form>
          <div class="text-center text-secondary mt-3">
            Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
        <!-- Photo -->
        <div class="bg-cover h-100 min-vh-100" style="background-image: url({{ asset('dist/img/login.jpg') }})"></div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./dist/js/demo.min.js?1692870487" defer></script>
  </body>
</html>
