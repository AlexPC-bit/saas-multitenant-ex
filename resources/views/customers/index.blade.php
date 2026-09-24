<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Clientes</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('customers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
            + Novo Cliente
        </a>

        <table class="w-full bg-white shadow rounded">
            <thead>
                <tr class="text-left border-b">
                    <th class="p-3">Nome</th>
                    <th class="p-3">E-mail</th>
                    <th class="p-3">Telefone</th>
                    <th class="p-3">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr class="border-b">
                        <td class="p-3">{{ $customer->name }}</td>
                        <td class="p-3">{{ $customer->email }}</td>
                        <td class="p-3">{{ $customer->phone }}</td>
                        <td class="p-3">
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Remover cliente?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $customers->links() }}
    </div>
</x-app-layout>