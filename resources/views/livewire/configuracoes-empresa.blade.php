<!-- Basic with Icons -->
<div class="col-xxl">
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Informações da Empresa</h5>
            
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <!-- Logo and Name Section -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-name">Nome</label>
                    <div class="col-sm-8">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-name2" class="input-group-text"><i
                                    class="bx bx-user"></i></span>
                            <input type="text" wire:model="name" class="form-control" id="basic-icon-default-name"
                                placeholder="MindSeat" aria-label="MindSeat"
                                aria-describedby="basic-icon-default-name2" />
                        </div>
                        @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-sm-2 text-center">
                        <div class="bg-light rounded p-2 mb-2"
                            style="width: 100px; height: 100px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                            <span class="text-muted">Logo</span>
                        </div>
                        <button type="button" class="btn btn-dark btn-sm">Alterar</button>
                    </div>
                </div>

                <!-- NIF -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-nif">NIF</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge" style="max-width: 300px;">
                            <span id="basic-icon-default-nif2" class="input-group-text"><i
                                    class="bx bx-id-card"></i></span>
                            <input type="text" wire:model="nif" class="form-control" id="basic-icon-default-nif"
                                placeholder="123456789" aria-label="NIF" aria-describedby="basic-icon-default-nif2" />
                        </div>
                        @error('nif') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Telefone -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-telefone">Telefone</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-telefone2" class="input-group-text"><i
                                    class="bx bx-phone"></i></span>
                            <input type="text" wire:model="telefone" class="form-control"
                                id="basic-icon-default-telefone" placeholder="923 456 789" aria-label="Telefone"
                                aria-describedby="basic-icon-default-telefone2" />
                        </div>
                        @error('telefone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="email" wire:model="email" class="form-control" id="basic-icon-default-email"
                                placeholder="exemplo@email.com" aria-label="Email"
                                aria-describedby="basic-icon-default-email2" />
                        </div>
                        @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Website -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-website">Website</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-website2" class="input-group-text"><i
                                    class="bx bx-globe"></i></span>
                            <input type="text" wire:model="website" class="form-control" id="basic-icon-default-website"
                                placeholder="www.exemplo.com" aria-label="Website"
                                aria-describedby="basic-icon-default-website2" />
                        </div>
                        @error('website') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Nome do Banco -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-banco">Nome do Banco</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-banco2" class="input-group-text"><i
                                    class="bx bx-buildings"></i></span>
                            <input type="text" wire:model="banco" class="form-control" id="basic-icon-default-banco"
                                placeholder="Banco BFA" aria-label="Banco"
                                aria-describedby="basic-icon-default-banco2" />
                        </div>
                        @error('banco') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- IBAN -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-iban">IBAN</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-iban2" class="input-group-text"><i
                                    class="bx bx-credit-card"></i></span>
                            <input type="text" wire:model="iban" class="form-control" id="basic-icon-default-iban"
                                placeholder="AO06 0000 0000 0000 0000 0000 0" aria-label="IBAN"
                                aria-describedby="basic-icon-default-iban2" />
                        </div>
                        @error('iban') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Cidade -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-cidade">Cidade</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-cidade2" class="input-group-text"><i
                                    class="bx bx-map"></i></span>
                            <input type="text" wire:model="cidade" class="form-control" id="basic-icon-default-cidade"
                                placeholder="Luanda" aria-label="Cidade"
                                aria-describedby="basic-icon-default-cidade2" />
                        </div>
                        @error('cidade') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Rua -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-rua">Rua</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-rua2" class="input-group-text"><i
                                    class="bx bx-street-view"></i></span>
                            <input type="text" wire:model="rua" class="form-control" id="basic-icon-default-rua"
                                placeholder="Rua Principal" aria-label="Rua"
                                aria-describedby="basic-icon-default-rua2" />
                        </div>
                        @error('rua') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Edifício -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-edificio">Edifício</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-edificio2" class="input-group-text"><i
                                    class="bx bx-building"></i></span>
                            <input type="text" wire:model="edificio" class="form-control"
                                id="basic-icon-default-edificio" placeholder="Edifício ABC" aria-label="Edifício"
                                aria-describedby="basic-icon-default-edificio2" />
                        </div>
                        @error('edificio') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Localização -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-localizacao">Localização</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge" style="max-width: 300px;">
                            <span id="basic-icon-default-localizacao2" class="input-group-text"><i
                                    class="bx bx-current-location"></i></span>
                            <input type="text" wire:model="localizacao" class="form-control"
                                id="basic-icon-default-localizacao" placeholder="Talatona" aria-label="Localização"
                                aria-describedby="basic-icon-default-localizacao2" />
                        </div>
                        @error('localizacao') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Regime -->
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-icon-default-regime">Regime</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge" style="max-width: 300px;">
                            <span id="basic-icon-default-regime2" class="input-group-text"><i
                                    class="bx bx-file"></i></span>
                            <input type="text" wire:model="regime" class="form-control" id="basic-icon-default-regime"
                                placeholder="Geral" aria-label="Regime" aria-describedby="basic-icon-default-regime2" />
                        </div>
                        @error('regime') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Salvar Informações</button>
                    </div>
                </div>

                @if (session()->has('message'))
                <div class="row mt-3">
                    <div class="col-sm-10 offset-sm-2">
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>