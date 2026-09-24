<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Vendas</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('sales.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
            + Nova Venda
        </a>

        <table class="w-full bg-white shadow rounded">
            <thead>
                <tr class="text-left border-b">
                    <th class="p-3">Cliente</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr class="border-b">
                        <td class="p-3">{{ $sale->customer->name }}</td>
                        <td class="p-3">R$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                        <td class="p-3">{{ ucfirst($sale->status) }}</td>
                        <td class="p-3">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $sales->links() }}
    </div>
</x-app-layout>