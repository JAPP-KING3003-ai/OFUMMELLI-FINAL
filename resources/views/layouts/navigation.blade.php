<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('Panel de Control') }}">
                        <!-- Reemplazamos el logo de Laravel con el logo de Ofum Melli -->
                        <img src="{{ asset('images/logo-ofummelli-sin-fondo.png') }}" alt="Ofum Melli" class="block h-12 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->role === 'Admin')
                        <!-- Enlace al Panel de Control -->
                        <x-nav-link :href="route('Panel de Control')" :active="request()->routeIs('Panel de Control')">
                            {{ __('Panel de Control') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'Admin')
                        <x-nav-link :href="url('/inventarios')" :active="request()->is('inventarios')">
                            {{ __('Inventarios') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'Admin')
                        <x-nav-link :href="url('/movimientos')" :active="request()->is('movimientos')">
                            {{ __('Movimientos') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'Admin')
                        <x-nav-link :href="url('/productos')" :active="request()->is('productos')">
                            {{ __('Productos') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'Cajero' || Auth::user()->role === 'Admin')
                        <x-nav-link :href="url('/clientes')" :active="request()->is('clientes')">
                            {{ __('Clientes') }}
                        </x-nav-link>
                    @endif
                    
                    @if (Auth::user()->role === 'Cajero' || Auth::user()->role === 'Admin')
                        <x-nav-link :href="url('/cuentas')" :active="request()->is('cuentas')">
                            {{ __('Cuentas') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'Admin')
                        <x-nav-link :href="url('/cuentas/pagadas')" :active="request()->is('cuentas/pagadas')">
                            {{ __('Cuentas Pagadas') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a 1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                        <!-- Botón para cambiar de modo
                        <button id="toggle-dark-mode" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cambiar Modo
                        </button> -->

                    <!-- Botón de Cambio de Tema con Indicador de Estado -->
                    <div class="theme-switch-container">
                        <label class="theme-switch-minimal">
                            <input type="checkbox" class="theme-switch-minimal__checkbox" id="theme-switch">
                            <div class="theme-switch-minimal__icon"></div>
                        </label>
                        <span id="theme-indicator" class="theme-indicator">Modo Claro</span>
                    </div>                        
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('Panel de Control')" :active="request()->routeIs('Panel de Control')">
                {{ __('Panel de Control') }}
            </x-responsive-nav-link>

            <!-- Nuevos enlaces para móvil -->
            <x-responsive-nav-link :href="url('/inventarios')" :active="request()->is('inventarios')">
                {{ __('Inventarios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/movimientos')" :active="request()->is('movimientos')">
                {{ __('Movimientos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/productos')" :active="request()->is('productos')">
                {{ __('Productos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/clientes')" :active="request()->is('clientes')">
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/cuentas')" :active="request()->is('cuentas')">
                {{ __('Cuentas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/cuentas/pagadas')" :active="request()->is('cuentas/pagadas')">
                {{ __('Cuentas Pagadas') }}
            </x-responsive-nav-link>
        </div>
    </div>

<!-- ESTILOS PARA EL BOTON DE CAMBIO DE TEMA -->

<style>
/* Contenedor del Botón e Indicador */
.theme-switch-container {
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: center;
}

/* Diseño del Botón con Formas de Sol y Luna */
.theme-switch-minimal {
    position: relative;
    display: inline-block;
    width: 37px;
    height: 60px;
    cursor: pointer;
}

.theme-switch-minimal__checkbox {
  display: none;
}

.theme-switch-minimal__icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50px;
  height: 50px;
  transition: all 0.3s ease;
}

/* Sol con rayos */
.theme-switch-minimal__icon::before,
.theme-switch-minimal__icon::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border-radius: 50%;
  transition: all 0.3s ease;
}

.theme-switch-minimal__icon::before {
  background-color: #FFD700; /* Sol amarillo */
  width: 30px;
  height: 30px;
  box-shadow: 0 0 10px #FFD700;
}

.theme-switch-minimal__icon::after {
  width: 40px;
  height: 40px;
  border: 2px solid #FFD700;
  box-shadow: 0 0 5px #FFD700;
  clip-path: polygon(
    50% 0%, 60% 40%, 100% 50%, 60% 60%, 
    50% 100%, 40% 60%, 0% 50%, 40% 40%
  ); /* Rayos del sol */
  z-index: -1;
}

/* Luna creciente */
.theme-switch-minimal__checkbox:checked + .theme-switch-minimal__icon::before {
  background-color: #C4C9D1; /* Luna gris */
  width: 30px;
  height: 30px;
  border-radius: 50%;
  box-shadow: 0 0 10px #C4C9D1;
  clip-path: circle(50% at 30% 50%);
}

.theme-switch-minimal__checkbox:checked + .theme-switch-minimal__icon::after {
  width: 0;
  height: 0;
  border: none;
  box-shadow: none;
}

/* Indicador del Modo */
.theme-indicator {
  font-size: 16px;
  font-weight: bold;
  color: #333;
  transition: color 0.3s ease;
}

.dark .theme-indicator {
  color: #FFF;
}
</style>

<!-- JAVASCRIPT PARA CAMBIO DE TEMA -->


<script>
// Referencias al interruptor y al indicador
const themeSwitch = document.getElementById('theme-switch');
const themeIndicator = document.getElementById('theme-indicator');

// Cargar el tema guardado al iniciar la página
document.addEventListener('DOMContentLoaded', () => {
  const currentTheme = localStorage.getItem('theme') || 'light';
  if (currentTheme === 'dark') {
    document.documentElement.classList.add('dark');
    themeSwitch.checked = true;
    themeIndicator.textContent = 'Modo Oscuro';
  } else {
    document.documentElement.classList.remove('dark');
    themeSwitch.checked = false;
    themeIndicator.textContent = 'Modo Claro';
  }
});

// Manejar el cambio de tema
themeSwitch.addEventListener('change', () => {
  if (themeSwitch.checked) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
    themeIndicator.textContent = 'Modo Oscuro';
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
    themeIndicator.textContent = 'Modo Claro';
  }
});
</script>
    
</nav>