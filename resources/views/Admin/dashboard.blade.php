@extends('layout.main')

@section('content')
<div class=''>

    {{-- boas vindas --}}
    <div class="mt-4 border bg-white text-black border-gray-300 rounded-2xl">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="flex items-center gap-2 text-2xl font-semibold leading-none tracking-tight">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="green" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5 lucide lucide-trending-up text-success">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                    <polyline points="16 7 22 7 22 13"></polyline>
                </svg>Bem-vindo ao FaturaSys
            </h3>
        </div>
        <div class="p-6 pt-0">
            <p class="text-gray-500">Sistema completo de gestão e faturação. Utilize o menu lateral para
                navegar entre os diferentes módulos do sistema.</p>
            
        </div>
    </div>


    <div class='p-2'>
        <!-- <h1 class="mb-2 text-3xl font-bold">Dashboard </h1>
            <p class='text-[17px] text-gray-500 text-'>Visão geral do sistema de faturação</p> -->
    </div>
    {{-- cards --}}
    <div id='cards' class='flex gap-3 justify-center'>
        <!-- Clientes -->
        <div class='bg-white text-black w-1/4 p-3 border-gray-300 gap-15 border-1  rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Clientes</span>
                <span class="p-2 rounded-lg bg-[#EBF2FE]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 lucide lucide-users text-primary">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg> </span>
                </span>
            </header>
            <div id="total-clientes" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>

        <!-- Fornecedores -->
        <div class='bg-white text-black w-1/4 gap-10 p-3 border-gray-300 border-1 rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Fornecedores</span>
                <span class="p-2 rounded-lg bg-[#E7F6EC]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="green" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 lucide lucide-truck text-accent">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                        <path d="M15 18H9"></path>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                        </path>
                        <circle cx="17" cy="18" r="2"></circle>
                        <circle cx="7" cy="18" r="2"></circle>
                    </svg> </span> </span>
            </header>
            <div id="total-fornecedores" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>

        <!-- Produtos -->
        <div class='bg-white text-black w-1/4 gap-10 p-3 border-gray-300 border-1 rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Produtos</span>
                <span class="p-2 rounded-lg bg-[#e6f6fd]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 lucide lucide-package text-info">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                        </path>
                        <path d="M12 22V12"></path>
                        <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                        <path d="m7.5 4.27 9 5.15"></path>
                    </svg> </span> </span>
            </header>
            <div id="total-produtos" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>

        <!-- Serviços -->
        <div class='bg-white text-black w-1/4 gap-10 p-3 border-gray-300 border-1 rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Serviços</span>
                <span class="p-2 rounded-lg bg-[#fef5e6]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="orange" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 lucide lucide-wrench text-warning">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                        </path>
                    </svg> </span> </span>
            </header>
            <div id="total-servicos" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>
    </div>

</div>
<script src="{{ asset('../js/Admin/dashboard.js') }}"></script>

@endsection
