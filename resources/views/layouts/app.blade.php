<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-blue-50">
<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-blue-50 font-roboto">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'block' : 'hidden'" @click.away="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-blue-100 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
        <div class="flex items-center justify-center mt-8">
            <div class="flex items-center">
                <span class="text-2xl font-semibold text-blue-800">Dashboard</span>
            </div>
        </div>

        <nav class="mt-10">
            <a class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 text-blue-700 hover:text-blue-900" href="#">
                <i class="fas fa-tachometer-alt mr-3"></i>
                DASHBOARD
            </a>
            <a class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 text-blue-700 hover:text-blue-900" href="#">
                <i class="fas fa-users mr-3"></i>
                USUARIOS
            </a>
            <a class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 text-blue-700 hover:text-blue-900" href="#">
                <i class="fas fa-box mr-3"></i>
                PRODUCTOS
            </a>
            <a class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 text-blue-700 hover:text-blue-900" href="#">
                <i class="fas fa-clipboard-list mr-3"></i>
                ORDENES DE TRABAJO
            </a>
            <a class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 text-blue-700 hover:text-blue-900" href="#">
                <i class="fas fa-calendar mr-3"></i>
                CALENDARIO
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex items-center mt-4 py-2 px-6 hover:bg-blue-200 text-blue-700 hover:text-blue-900">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    SALIR
                </button>
            </form>

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
                    <button @click="dropdownOpen = ! dropdownOpen" class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                        <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1528892952291-009c663ce843?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=296&q=80" alt="Your avatar">
                    </button>

                    <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"></div>

                    <div x-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Perfil</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Configuración</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">Salir</a>
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
</html>
