<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Faturation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            display: flex;
        }
    </style>
</head>

<body class=''>
    {{-- Sidebar --}}
    <!-- Sidebar Container -->
    <div class='h-[100vh] w-[300px] bg-[#0F1729] text-white'>
        {{-- header --}}
        <header class='p-6 border-b border-gray-800 mb-2'>
            <h4 class='text-xl font-bold '>FaturaSys</h4>
            <p class='text-[13px] text-gray-400'>Sistema de Faturação</p>
        </header>
        {{-- menu --}}
        <div>
        </div>
        <div class="relative flex w-full min-w-0 flex-col p-2">
            <div
                class="flex h-8 items-center rounded-md px-2 text-xs font-medium text-gray-400 outline-none ring-sidebar-ring ">
                Principal</div>
            <div class="w-full text-sm">
                <ul class="flex w-full min-w-0 flex-col gap-1">
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/dashboard"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-layout-dashboard h-4 w-4">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg><span>Dashboard</span></a></li>
                </ul>
            </div>
        </div>
        {{-- gestao de terceiros --}}
        <div class="relative flex w-full min-w-0 flex-col p-2">

            <div
                class="flex h-8 items-center rounded-md px-2 text-xs font-medium text-gray-400 outline-none ring-sidebar-ring ">
                Gestão de Terceiros</div>
            <div class="w-full text-sm">
                <ul class="flex w-full min-w-0 flex-col gap-1">
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/clientes">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-users h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/fornecedores">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-truck h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                <path d="M15 18H9"></path>
                                <path
                                    d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                                </path>
                                <circle cx="17" cy="18" r="2"></circle>
                                <circle cx="7" cy="18" r="2"></circle>
                            </svg>
                            <span>Fornecedores</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        {{-- gerir usuer --}}
        <div class="relative flex w-full min-w-0 flex-col p-2">
            <div
                class="flex h-8 items-center rounded-md px-2 text-xs font-medium text-gray-400 outline-none ring-sidebar-ring ">
                Gestão De Usuários</div>
            <div class="w-full text-sm">
                <ul class="flex w-full min-w-0 flex-col gap-1">
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/usuarios">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-user-cog h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <circle cx="18" cy="15" r="3"></circle>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M10 15H6a4 4 0 0 0-4 4v2"></path>
                                <path d="m21.7 16.4-.9-.3"></path>
                                <path d="m15.2 13.9-.9-.3"></path>
                                <path d="m16.6 18.7.3-.9"></path>
                                <path d="m19.1 12.2.3-.9"></path>
                                <path d="m19.6 18.7-.4-1"></path>
                                <path d="m16.8 12.3-.4-1"></path>
                                <path d="m14.3 16.6 1-.4"></path>
                                <path d="m20.7 13.8 1-.4"></path>
                            </svg>
                            <span>Usuários</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        {{-- catalogo --}}
        <div class="relative flex w-full min-w-0 flex-col p-2">
            <div
                class="flex h-8 items-center rounded-md px-2 text-xs font-medium text-gray-400 outline-none ring-sidebar-ring ">
                Catálogo</div>
            <div class="w-full text-sm">
                <ul class="flex w-full min-w-0 flex-col gap-1">
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/produtos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <path
                                    d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                                </path>
                                <path d="M12 22V12"></path>
                                <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                <path d="m7.5 4.27 9 5.15"></path>
                            </svg>
                            <span>Produtos</span>
                        </a>
                    </li>
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/servicos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <path
                                    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                                </path>
                            </svg>
                            <span>Serviços</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        {{-- configuracoes fiscais --}}
        <div class="relative flex w-full min-w-0 flex-col p-2">
            <div
                class="flex h-8 items-center rounded-md px-2 text-xs font-medium text-gray-400 outline-none ring-sidebar-ring ">
                COnfigurações Fiscais</div>
            <div class="w-full text-sm">
                <ul class="flex w-full min-w-0 flex-col gap-1">
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/impostos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z">
                                </path>
                                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                                <path d="M12 17.5v-11"></path>
                            </svg>
                            <span>Impostos</span>
                        </a>
                    </li>
                    <li class="group/menu-item relative"><a
                            class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left hover:bg-[#1D2839]"
                            href="/isencao">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings h-4 w-4"
                                data-lov-id="src/components/layout/AppSidebar.tsx:111:28" data-lov-name="item.icon"
                                data-component-path="src/components/layout/AppSidebar.tsx" data-component-line="111"
                                data-component-file="AppSidebar.tsx" data-component-name="item.icon"
                                data-component-content="%7B%22className%22%3A%22h-4%20w-4%22%7D">
                                <path
                                    d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                                </path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <span>Motivo de Isenção</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    </div class="content">
    @yield('content')
    </div>
</body>

</html>
