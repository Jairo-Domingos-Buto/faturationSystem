@extends('layout.main')

@section('content')
    <main class="flex-1 overflow-auto">
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Clientes</h1>
                    <p class="text-black text-[17px]">Gestão de clientes</p>
                </div>
                {{-- Novo Cliente --}}
                <button id="openModal" onclick="openModal()"
                    class="inline-flex items-center justify-center h-10 gap-2 px-4 py-2 text-sm font-medium rounded bg-[#3c83f6] text-white hover:bg-blue-400"
                    type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-:r0:" data-state="closed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 mr-2 lucide lucide-plus">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Novo Cliente
                </button>
                {{-- fim de botao --}}
            </div>


              <!-- Hoverable Table rows -->
              <div class="card">


                  <table class="table table-hover">
                    <thead class='p-1'>
                      <tr>
                        <th>NOME  </th>
                        <th>NIF</th>
                        <th>PROVÍNCIA</th>
                        <th>CIDADE</th>
                        <th>TELEFONE</th>
                           <th>AÇÕES</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                       <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Angular Project</strong></td>
                        <td>04934034034LA049</td>
                       <td>Luanda</td>
                        <td>Luanda</td>
                        <td>934543562</td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a onclick="openModal()" class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Hoverable Table rows -->

        {{-- modal --}}
        <div  id="modal-overlay" class="fixed w-full ml-[260px] hidden inset-0 z-40 items-start justify-center bg-black/50 p-4 sm:items-center sm:p-0">

            <div id="modal" class="fixed left-[55%] top-[50%] z-100 hidden w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border border-[#E1E7EF] bg-white p-6 shadow-lg duration-200 sm:rounded-lg"
                tabindex="-1" style="pointer-events: auto;">
                <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                    <h2 id="radix-:r1:" class="text-lg font-semibold leading-none tracking-tight">Novo Cliente</h2>
                    <p id="radix-:r2:" class="text-sm text-muted-foreground">Preencha os dados do cliente</p>
                </div>

                <form class="space-y-4" >
                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="name">Nome</label>
                        <input
                            class="flex  w-full h-10 px-3 py-2 text-base border rounded-md border-[#E1E7EF]  bg-background ring-offset-background file:border-0 -visible:outline-blue  disabled:opacity-50 md:text-sm"
                            id="name" required="" value="">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="nif">NIF</label>
                        <input
                            class="flex  w-full h-10 px-3 py-2 text-base border rounded-md border-[#E1E7EF]  bg-background ring-offset-background file:border-0 -visible:outline-blue  disabled:opacity-50 md:text-sm"
                            id="nif" required="" value="">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="province">Província</label>
                            <input
                                class="flex  w-full h-10 px-3 py-2 text-base border rounded-md border-[#E1E7EF]  bg-background ring-offset-background file:border-0 -visible:outline-blue  disabled:opacity-50 md:text-sm"
                                id="province" required="" value="">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="city">Cidade</label>
                            <input
                                class="flex  w-full h-10 px-3 py-2 text-base border rounded-md border-[#E1E7EF]  bg-background ring-offset-background file:border-0 -visible:outline-blue  disabled:opacity-50 md:text-sm"
                                id="city" required="" value="">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="location">Localização</label>
                        <input
                           class="flex  w-full h-10 px-3 py-2 text-base border rounded-md border-[#E1E7EF]  bg-background ring-offset-background file:border-0 -visible:outline-blue  disabled:opacity-50 md:text-sm"
                            id="location" required="" value="">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="phone">Telefone</label>
                        <input
                         class="flex  w-full h-10 px-3 py-2 text-base border rounded-md border-[#E1E7EF]  bg-background ring-offset-background file:border-0 -visible:outline-blue  disabled:opacity-50 md:text-sm"
                            id="phone" required="" value="">
                    </div>

                    <button
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full"
                        type="submit">Criar</button>
                </form>

                <button id="closeModal" type="button" onclick="closeModal()"
                    class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity data-[state=open]:bg-accent data-[state=open]:text-muted-foreground hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 lucide lucide-x">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                    <span class="sr-only">Close</span>
                </button>
            </div>
</div>
    </main>

    <script>
      let modal = document.getElementById("modal");
let modalOverlay = document.getElementById("modal-overlay");

function openModal() {
    modal.classList.remove("hidden");
    modal.classList.add("grid");

    modalOverlay.classList.remove("hidden");
    modalOverlay.classList.add("flex");
}

function closeModal() {
    modal.classList.remove("grid");
    modal.classList.add("hidden");

    modalOverlay.classList.remove("flex");
    modalOverlay.classList.add("hidden");
}

    </script>
@endsection
