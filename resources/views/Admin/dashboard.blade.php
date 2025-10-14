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
    <div id='cards' class='flex gap-5 justify-center'>
        <!-- Clientes -->
        <div class='bg-white text-black w-1/4 p-3 border-gray-300 gap-15 border-1  rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Clientes</span>
                <span class="p-2 rounded-lg bg-[#EBF2FE]">
                    <!-- ícone -->
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
                    <!-- ícone -->
                </span>
            </header>
            <div id="total-fornecedores" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>

        <!-- Produtos -->
        <div class='bg-white text-black w-1/4 gap-10 p-3 border-gray-300 border-1 rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Produtos</span>
                <span class="p-2 rounded-lg bg-[#e6f6fd]">
                    <!-- ícone -->
                </span>
            </header>
            <div id="total-produtos" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>

        <!-- Serviços -->
        <div class='bg-white text-black w-1/4 gap-10 p-3 border-gray-300 border-1 rounded-xl'>
            <header class='flex items-center justify-between p-1 '>
                <span class='font-medium text-[15px]'>Serviços</span>
                <span class="p-2 rounded-lg bg-[#fef5e6]">
                    <!-- ícone -->
                </span>
            </header>
            <div id="total-servicos" class='p-1 text-3xl font-bold'>—</div>
            <div class='p-1 text-[14px] text-gray-500'>Total Registrado</div>
        </div>
    </div>

</div>
<script src="{{ asset('../js/Admin/dashboard.js') }}"></script>

@endsection