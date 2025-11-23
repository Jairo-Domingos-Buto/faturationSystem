<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Token vindo da rota --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email vindo pela query string --}}
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="block">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full" type="email" value="{{ request('email') }}" disabled />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Nova Senha" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirmar Senha" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    Redefinir Senha
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>