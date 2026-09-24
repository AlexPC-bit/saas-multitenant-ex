<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Novo Produto</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        <form action="{{ route('products.store') }}" method="POST" class="bg-white shadow rounded p-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="name" value="Nome" />
                <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" value="Descrição" />
                <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="price" value="Preço" />
                <x-text-input id="price" name="price" type="number" step="0.01" class="block mt-1 w-full" :value="old('price')" required />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-600">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
            </div>
        </form>
    </div>
</x-app-layout>