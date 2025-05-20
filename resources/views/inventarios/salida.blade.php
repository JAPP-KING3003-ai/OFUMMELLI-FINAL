<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            Registrar salida de {{ $producto->nombre }}
        </h2>
    </x-slot>
    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('inventarios.salida', $producto->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-white mb-2">Cantidad a descontar</label>
                        <input type="number" name="cantidad" min="1" required class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-white mb-2">Motivo de la salida</label>
                        <input type="text" name="detalle" required class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" placeholder="Ej: Venta, Merma, Uso interno...">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Registrar salida</button>
                    <a href="{{ route('inventarios.index') }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>