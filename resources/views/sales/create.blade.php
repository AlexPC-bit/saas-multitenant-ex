<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nova Venda</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        <form action="{{ route('sales.store') }}" method="POST" class="bg-white shadow rounded p-6 space-y-4">
            @csrf

            <div>
                <x-input-label for="customer_id" value="Cliente" />
                <select id="customer_id" name="customer_id" class="block mt-1 w-full border-gray-300 rounded" required>
                    <option value="">Selecione...</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
            </div>

            <div id="items-wrapper">
                <label class="font-medium">Itens</label>
                <div class="item-row flex gap-2 mt-2">
                    <select name="items[0][product_id]" class="border-gray-300 rounded flex-1" required>
                        <option value="">Produto...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (R$ {{ number_format($product->price, 2, ',', '.') }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][quantity]" min="1" value="1" class="border-gray-300 rounded w-24" required>
                </div>
            </div>

            <button type="button" id="add-item" class="text-blue-600 text-sm">+ Adicionar item</button>

            <div class="flex justify-end gap-3">
                <a href="{{ route('sales.index') }}" class="px-4 py-2 text-gray-600">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Registrar Venda</button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function () {
            const wrapper = document.getElementById('items-wrapper');
            const firstRow = wrapper.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('select, input').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${itemIndex}]`);
                if (el.tagName === 'INPUT') el.value = 1;
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
            });

            wrapper.appendChild(newRow);
            itemIndex++;
        });
    </script>
</x-app-layout>