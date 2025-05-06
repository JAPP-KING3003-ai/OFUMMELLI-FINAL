<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Editar Inventario') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('inventarios.update', $inventario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="producto" class="block text-gray-800 dark:text-gray-200">Producto:</label>
                        <input type="text" id="producto" value="{{ $inventario->producto->nombre }}" class="w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white" disabled>
                    </div>

                    <div class="mb-4">
                        <label for="cantidad_inicial" class="block text-gray-800 dark:text-gray-200">Cantidad Inicial:</label>
                        <input type="number" name="cantidad_inicial" id="cantidad_inicial" value="{{ old('cantidad_inicial', $inventario->cantidad_inicial) }}" class="w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label for="cantidad_actual" class="block text-gray-800 dark:text-gray-200">Cantidad Actual:</label>
                        <input type="number" name="cantidad_actual" id="cantidad_actual" value="{{ old('cantidad_actual', $inventario->cantidad_actual) }}" class="w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label for="precio_costo" class="block text-gray-800 dark:text-gray-200">Precio Costo:</label>
                        <input type="number" name="precio_costo" id="precio_costo" value="{{ old('precio_costo', $inventario->precio_costo) }}" step="0.01" class="w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>