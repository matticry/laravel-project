<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.2.7/dist/css/tempus-dominus.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">





</head>

<body class="bg-blue-50">
<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-blue-50 font-roboto">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'block' : 'hidden'" @click.away="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
         class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-blue-100 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">

        <div class="flex items-center justify-center mt-8">
            <div class="flex items-center">
                <span class="text-2xl font-semibold text-blue-800">Sis. Citas Medicas</span>
            </div>
        </div>

        <nav class="mt-10">

            {{-- GENERAL --}}
            @can('view.index.dashboard')
                <a class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('dashboard') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt mr-3"></i> DASHBOARD
                </a>
            @endcan

            {{-- PACIENTES --}}
            <p class="px-6 mt-6 mb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Pacientes</p>

            @can('view.index.profile')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('profile.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('profile.index') }}">
                    <i class="fas fa-users mr-3"></i> USUARIOS / CONSULTAS
                </a>
            @endcan

            <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('historial.*') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('historial.index') }}">
                <i class="fas fa-notes-medical mr-3"></i> HISTORIAL CLÍNICO
            </a>

            @can('view.index.calendar')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('calendario.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('calendario.index') }}">
                    <i class="fas fa-calendar mr-3"></i> CALENDARIO
                </a>
            @endcan

            {{-- LABORATORIO --}}
            <p class="px-6 mt-6 mb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Laboratorio</p>

            @can('view.index.laboratorio')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('laboratorios.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('laboratorios.index') }}">
                    <i class="fas fa-microscope mr-3"></i> LABORATORIO
                </a>
            @endcan


            @can('view.index.calendar')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('calendario.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('calendario.index') }}">
                    <i class="fas fa-clipboard-list mr-3"></i> ÓRDENES LAB.
                </a>
            @endcan

            {{-- FINANZAS --}}
            <p class="px-6 mt-6 mb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Finanzas</p>

            @can('view.index.calendar')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('calendario.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('calendario.index') }}">
                    <i class="fas fa-dollar-sign mr-3"></i> INGRESOS
                </a>
            @endcan

            @can('view.index.calendar')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('calendario.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('calendario.index') }}">
                    <i class="fas fa-cash-register mr-3"></i> VENTAS Y COBROS
                </a>
            @endcan

            @can('view.index.calendar')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('calendario.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('calendario.index') }}">
                    <i class="fas fa-chart-line mr-3"></i> REPORTES FINANCIEROS
                </a>
            @endcan

            {{-- INVENTARIO --}}
            <p class="px-6 mt-6 mb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Inventario</p>

            @can('view.index.calendar')
                <a class="flex items-center py-2 px-6 hover:bg-blue-200 {{ request()->routeIs('calendario.index') ? 'bg-blue-200 text-blue-900' : 'text-blue-700 hover:text-blue-900' }}" href="{{ route('calendario.index') }}">
                    <i class="fas fa-box mr-3"></i> CONTROL DE ENVÍOS
                </a>
            @endcan

            {{-- SALIR --}}
            <div class="border-t border-blue-200 mt-6">
                <form action="{{ route('logout') }}" method="POST" class="flex items-center mt-2 py-2 px-6 hover:bg-red-100 text-red-500 hover:text-red-700">
                    @csrf
                    <button type="submit" class="flex items-center">
                        <i class="fas fa-sign-out-alt mr-3"></i> SALIR
                    </button>
                </form>
            </div>

        </nav>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-blue-200">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-blue-700 focus:outline-none lg:hidden">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = ! dropdownOpen" class="flex items-center space-x-2 relative block h-8 overflow-hidden shadow focus:outline-none">
                        <img class="h-8 w-8 rounded-full object-cover"
                             src="{{ Auth::user()->us_image ? asset('storage/' . Auth::user()->us_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->us_name) }}"
                             alt="Foto de perfil">
                        <span class="text-blue-700">{{ Auth::user()->us_name }}</span>
                    </button>

                    <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"></div>

                    <div x-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                        <a href="{{ route('edit.profile', ['id' => Auth::user()->us_id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Perfil</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Configuración</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Salir</button>
                        </form>
                    </div>
                </div>
            </div>

        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-blue-50">
            <div class="container mx-auto px-6 py-8">
                @yield('content')
            </div>
        </main>
    </div>
</div>
</body>
<script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.2.7/dist/js/tempus-dominus.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>


</html>
