<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Digite o código do seu app autenticador, ou use um código de recuperação.
    </div>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div>
            <x-input-label for="code" value="Código" />
            <x-text-input id="code" name="code" class="block mt-1 w-full" autofocus />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>Verificar</x-primary-button>
        </div>
    </form>
</x-guest-layout>