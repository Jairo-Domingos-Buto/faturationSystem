<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | MindSeat</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

  <style>
    body {
      background: linear-gradient(135deg, #f8f9ff, #e9edff);
      font-family: 'Public Sans', sans-serif;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .btn-primary {
      background: #696cff;
      border: none;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #5b5fe0;
    }

    .app-brand-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: #444;
    }
  </style>
</head>

<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">


      <div class="authentication-inner">

        <div class="card">
          <div class="card-body">
            <div class="app-brand justify-content-center mb-4">
              <span class="app-brand-link gap-2 align-items-center">

                <span class="app-brand-text">MindSeat</span>
              </span>
            </div>

            <h4 class="mb-2 text-center fw-bold">Bem-vindo 👋</h4>
            <p class="text-center mb-4 text-muted">Acesse sua conta para continuar</p>

            {{-- Mensagens de erro --}}
          <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition>
            @if (session('error'))
            <div class="alert alert-danger mb-3" role="alert">
              {{ session('error') }}
            </div>
            @endif
          </div>
          <script src="//unpkg.com/alpinejs" defer></script>


            {{-- Mensagem de sessão --}}
            {{-- Mensagem padrão de sucesso ou status --}}
            @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
              {{ session('status') }}
            </div>
            @endif
            <form method="POST" action="{{ route('login') }}" id="formAuthentication">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email"
                  value="{{ old('email') }}" required autofocus />
              </div>

              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Senha</label>
                  @if (Route::has('password.request'))
                  <a href="{{ route('password.request') }}">
                    <small>Esqueceu sua senha?</small>
                  </a>
                  @endif
                </div>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="••••••••"
                    required />
                  <span class="input-group-text cursor-pointer" id="togglePassword">
                    <i class="bx bx-hide"></i>
                  </span>
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                  <label class="form-check-label" for="remember-me">Lembrar de mim</label>
                </div>
              </div>

              <button class="btn btn-primary d-grid w-100" type="submit">Entrar</button>
            </form>

            <p class="text-center mt-3">
              <span>Não tem uma conta?</span>
              <a href="{{ route('register') }}">Crie agora</a>
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script>
    const togglePassword = document.querySelector('#togglePassword i');
    const password = document.querySelector('#password');
    document.querySelector('#togglePassword').addEventListener('click', () => {
      const type = password.type === 'password' ? 'text' : 'password';
      password.type = type;
      togglePassword.classList.toggle('bx-show');
      togglePassword.classList.toggle('bx-hide');
    });
  </script>
</body>

</html>