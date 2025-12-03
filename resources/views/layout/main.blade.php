<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Faturation System</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="stylesheet" href="../../assets//vendor/fonts/iconify-icons.css" />
    <!-- CDN Lucide -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    {{-- boxicon cdn--}}
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>

    {{-- livewire --}}
    @livewireStyles

    {{-- alpiines --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .menu-icon.tf-icons {
            width: 18px;
            height: 18px;
            stroke-width: 1.5;
        }

        .menu-link:hover .menu-icon.tf-icons {
            stroke-width: 2;
        }

        .menu-sub .menu-link {
            padding-left: 3rem;
        }

        .menu-header {
            padding: 0.75rem 1rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #a1a5b7;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical border menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                    <path
                                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                        id="path-1"></path>
                                    <path
                                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                        id="path-3"></path>
                                    <path
                                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                        id="path-4"></path>
                                    <path
                                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                        id="path-5"></path>
                                </defs>
                                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                <mask id="mask-2" fill="white">
                                                    <use xlink:href="#path-1"></use>
                                                </mask>
                                                <use fill="#696cff" xlink:href="#path-1"></use>
                                                <g id="Path-3" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-3"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                </g>
                                                <g id="Path-4" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-4"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                </g>
                                            </g>
                                            <g id="Triangle"
                                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                <use fill="#696cff" xlink:href="#path-5"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text ms-2"
                            style="text-transform: uppercase; font-weight: 700; font-size: 25px;">OMINIFINANCE</span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="{{ route('admin.dashboard') }}" class="menu-link">
                            <i data-lucide="layout-dashboard" class="menu-icon tf-icons"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <!-- Cadastros -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Cadastros</span>
                    </li>

                    <!-- Pessoas -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i data-lucide="users" class="menu-icon tf-icons"></i>
                            <div data-i18n="Pessoas">Pessoas</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('admin.clientes') }}" class="menu-link">
                                    <div data-i18n="Clientes">Clientes</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.fornecedores') }}" class="menu-link">
                                    <div data-i18n="Fornecedores">Fornecedores</div>
                                </a>
                            </li>
                            @auth
                            @if(auth()->user()->typeUser->value === 'admin')
                            <li class="menu-item">
                                <a href="{{ route('Admin.cadastrar') }}" class="menu-link">
                                    <div data-i18n="Usuários">Usuários</div>
                                </a>
                            </li>
                            @endif
                            @endauth
                        </ul>
                    </li>

                    <!-- Produtos & Serviços -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i data-lucide="package" class="menu-icon tf-icons"></i>
                            <div data-i18n="Produtos & Serviços">Produtos & Serviços</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('admin.categoria') }}" class="menu-link">
                                    <div data-i18n="Categorias">Categorias</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.produtos') }}" class="menu-link">
                                    <div data-i18n="Produtos">Produtos</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.servicos') }}" class="menu-link">
                                    <div data-i18n="Serviços">Serviços</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Fiscal -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Fiscal</span>
                    </li>

                    <!-- Tributação -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i data-lucide="receipt" class="menu-icon tf-icons"></i>
                            <div data-i18n="Tributação">Tributação</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('admin.impostos') }}" class="menu-link">
                                    <div data-i18n="Impostos">Impostos</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.isencao') }}" class="menu-link">
                                    <div data-i18n="Motivos Isenção">Motivos de Isenção</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Documentos -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Documentos</span>
                    </li>

                    <!-- Faturação -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i data-lucide="file-text" class="menu-icon tf-icons"></i>
                            <div data-i18n="Faturação">Faturação</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('admin.faturas') }}" class="menu-link">
                                    <div data-i18n="Faturas">Faturas</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.recibos') }}" class="menu-link">
                                    <div data-i18n="Recibos">Recibos</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{ route('admin.notas-credito') }}" class="menu-link">
                                    <div data-i18n="Notas Crédito">Notas de Crédito</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Vendas -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Vendas</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('admin.pov') }}" class="menu-link">
                            <i data-lucide="shopping-cart" class="menu-icon tf-icons"></i>
                            <div data-i18n="Ponto de Venda">Ponto de Venda</div>
                        </a>
                    </li>

                    <!-- Relatórios -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Relatórios & Exportação</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('admin.exportar-saft') }}" class="menu-link">
                            <i data-lucide="download" class="menu-icon tf-icons"></i>
                            <div data-i18n="Exportar SAFT">Exportar SAFT</div>
                        </a>
                    </li>

                    <!-- Sistema -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Sistema</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('admin.configuracoes') }}" class="menu-link">
                            <i data-lucide="settings" class="menu-icon tf-icons"></i>
                            <div data-i18n="Configurações">Configurações</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);"
                    class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="bx bx-menu icon-base"></i>
                    <i class="bx bx-chevron-right icon-base"></i>
                </a>
            </div>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar navbar navbar-expand-xl align-items-center border px-4" id="layout-navbar"
                    style="width: 100%; margin-top: 0;">

                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <!-- <i class="icon-base bx bx-menu icon-md"></i> -->
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end " id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-sun icon-md theme-icon-active"></i>
                                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="nav-theme-text">
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center active"
                                            data-bs-theme-value="light" aria-pressed="false">
                                            <span><i class="icon-base bx bx-sun icon-md me-3"
                                                    data-icon="sun"></i>Light</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center"
                                            data-bs-theme-value="dark" aria-pressed="true">
                                            <span><i class="icon-base bx bx-moon icon-md me-3"
                                                    data-icon="moon"></i>Dark</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item align-items-center"
                                            data-bs-theme-value="system" aria-pressed="false">
                                            <span><i class="icon-base bx bx-desktop icon-md me-3"
                                                    data-icon="desktop"></i>System</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src=" {{ asset($profile->foto ?? 'assets/img/avatars/default.png') }}"
                                            alt="FP"
                                            class="profile-photo-big rounded-circle mb-3 shadow d-flex flex-column align-items-center justify-content-center"
                                            width="90" height="90">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('Admin.perfil') }}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src=" {{ asset($profile->foto ?? 'assets/img/avatars/default.png') }}"
                                                            alt="FP"
                                                            class="profile-photo-big rounded-circle mb-3 shadow d-flex flex-column align-items-center justify-content-center"
                                                            width="
            90" height="90">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                                    <small class="text-body-secondary">{{
                                                        auth()->user()->typeUser->value }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('Admin.perfil') }}">
                                            <i class="icon-base bx bx-user icon-md me-3"></i>
                                            <span>Meu Perfil</span>
                                        </a>
                                    </li>
                                    @auth
                                    @if(auth()->user()->typeUser->value === 'admin') <li>
                                        <a class="dropdown-item" href="{{ route('Admin.cadastrar') }}">
                                            <i class="bx bx-user-plus icon-md me-3"></i>
                                            <span>Criar Usuário</span>
                                        </a>
                                    </li>
                                    @endif
                                    @endauth
                                    <!-- <li>
                                        <a class="dropdown-item" href="{{ route('Admin.lista') }}">
                                            <i class="icon-base bx bx-cog icon-md me-3"></i><span>Configurações</span>
                                        </a>
                                    </li> -->
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li class="p-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <i class="bx bx-power-off me-3"></i>
                                            <a href="#"
                                                onclick="event.preventDefault(); this.closest('form').submit();">Sair</a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class=" flex-grow-1 p-3 bg-white ">
                        <!-- Layout Demo -->
                        @yield('content')
                        <!--/ Layout Demo -->
                    </div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    @livewireScripts
    @stack('scripts')

    <!-- Scripts no final do body -->
    <script>
        // Inicializar ícones Lucide quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    </script>
</body>

</html>