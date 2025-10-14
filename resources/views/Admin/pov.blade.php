@extends('layout.main')

@section('content')
<main class='bg-white w-[100%] p-4 rounded h-full flex flex-col'>

<header class='flex justify-between'>
  <div class='flex flex-col gap-0'>
    <h2 class='m-0 mb-0.5 font-bold'>Ponto de Venda</h2>
    <p>Crie e imprima faturas para seus clientes</p>
  </div>
  <div class='flex gap-2 items-center justify-center'>
    <button class='flex justify-center w-[150px] bg-[#3c83f6] text-white rounded p-2 items-center'>
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4" ><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
        <span>Guardar Fatura</span>
    </button>
     <button class='flex border-1 rounded roudend justify-center w-[150px] bg-white text-black p-2 items-center hover:bg-green-700'>
     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer mr-2 h-4 w-4" ><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"></path><rect x="6" y="14" width="12" height="8" rx="1"></rect>
    </svg>
        <span>Imprimir Fatura</span>
    </button>
  </div>
</header>
  {{-- corpo --}}
  <article class='flex gap-10 '>

<div id='ladoesquerdo' class='w-[60%]'>

<div class="rounded-lg border bg-card text-card-foreground ">
  <div class="flex flex-col space-y-1.5 p-6">
    <h3 class="text-2xl font-semibold leading-none tracking-tight">Informações do Cliente</h3>
  </div>
  <div  class="p-6 pt-0 space-y-4">
    <div >
      <label  class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Cliente</label>
      <select type="button" role="combobox" aria-controls="radix-:r3:" aria-expanded="false" aria-autocomplete="none" dir="ltr" class="flex h-10 w-full items-center justify-between rounded-md border border-input px-3 py-2 text-sm ">
        <span  style="pointer-events: none;">Selecione o cliente</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 opacity-50"  aria-hidden="true"><path d="m6 9 6 6 6-6"></path>

        </svg
        >
        <option value="">Selecione o Seu cliente</option>
      </select>
    </div>
  </div>
</div>

{{-- adicionando 1 item --}}
<div class="rounded-lg border bg-card text-card-foreground shadow-sm mt-3">
  <div class="flex flex-col space-y-1.5 p-6">
    <h3 class="text-2xl font-semibold leading-none tracking-tight">Adicionar Itens</h3>
  </div>
  <div class="p-6 pt-0 space-y-4">
    <div class="grid gap-4 md:grid-cols-2">
      <div>
        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Tipo</label>
       {{-- select --}}
        <select type="button" role="combobox" aria-controls="radix-:r4:" aria-expanded="false" aria-autocomplete="none" dir="ltr" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1">
          <span style="pointer-events: none;">Produto</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 opacity-50" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
          {{-- options --}}
          <option value="produto">Produto</option>
          <option value="servico">Servico</option>
          {{-- fim options --}}
        </select>
      </div>
      <div>
        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Produto</label>
        <select type="button" role="combobox" aria-controls="radix-:r5:" aria-expanded="false" aria-autocomplete="none" dir="ltr" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ">
          <span style="pointer-events: none;">Selecione o produto</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 opacity-50" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
          <option value="">Selecione o produto</option>
        </select>
      </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
      <div>
        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Quantidade</label>
        <input type="number" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" min="1" value="1">
      </div>
      <div>
        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Imposto</label>
        <select type="button" role="combobox" aria-controls="radix-:r6:" aria-expanded="false" aria-autocomplete="none" dir="ltr" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1">
          <span style="pointer-events: none;">Selecione</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 opacity-50" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
          <option value="">Nenhum</option>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Isenção</label>
        <select type="button" role="combobox" aria-controls="radix-:r7:" aria-expanded="false" aria-autocomplete="none" dir="ltr" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1">
          <span style="pointer-events: none;">Selecione</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 opacity-50" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
          <option value="">Sem motivo</option>
        </select>
      </div>
    </div>

    <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap  text-sm bg-[#3c83f6] p-2 text-white w-full rounded">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2 h-4 w-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>Adicionar Item
    </button>
  </div>
</div>

{{-- observacoes --}}
<div class="rounded-lg border bg-card text-card-foreground shadow-sm mt-4">
  <div class="flex flex-col space-y-1.5 p-6">
    <h3 class="text-2xl font-semibold leading-none tracking-tight">Observações</h3>
  </div>
  <div class="p-6 pt-0">
    <textarea class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Notas adicionais..." rows="3"></textarea>
  </div>
</div>

</div>
<div id='ladoDireito'>
  <div class=" w-[600px]">
    <div class="rounded-lg border bg-card shadow-sm ">
      <div class="flex flex-col p-6 ">
        <h3 class="text-2xl font-semibold leading-none tracking-tight print:text-center print:text-2xl">Pré-visualização da Fatura</h3>
      </div>
      <div class="p-6 pt-0 space-y-4"><div class="grid print:block space-y-2 border-b pb-4">
        <h2 class="text-xl font-bold">FATURA</h2>
        <p class="text-sm">Nº: FT-1760464137969</p>
        <p class="text-sm">Data: 14/10/2025</p>
      </div>

      <div class="space-y-2 border-t pt-4">

        <div class="flex justify-between text-sm">
          <span>Subtotal:</span>
          <span>0.00 Kz</span>
        </div>
        <div class="flex justify-between text-sm">
          <span>Impostos:</span>
          <span>0.00 Kz</span>
        </div>
        <div class="flex justify-between text-lg font-bold border-t pt-2">
          <span>Total:</span>
          <span>0.00 Kz</span>
        </div>
      </div>
    </div>
  </div>
</div>


</div>

 </article>

</main>

@endsection
