@extends('layouts.app')

@section('title', 'Buscar Usuario - Mascotas')

@section('header', 'Buscar Usuario y sus Mascotas')

@section('content')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="{ searchQuery: '{{ $search ?? '' }}' }">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Buscar Usuario para Ver sus Mascotas</h2>

            <!-- Formulario de búsqueda -->
            <form action="{{ route('pets.search-users') }}" method="GET" class="mb-8">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <input type="text"
                               name="search"
                               x-model="searchQuery"
                               placeholder="Buscar por nombre, apellido, email o DNI..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ $search ?? '' }}">
                    </div>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Buscar</span>
                    </button>
                    @if($search)
                        <a href="{{ route('pets.search-users') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            <!-- Resultados de búsqueda -->
            @if($users->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($users as $user)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6 border border-blue-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                    {{ strtoupper(substr($user->us_name, 0, 1)) }}{{ strtoupper(substr($user->us_lastName, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $user->us_name }} {{ $user->us_lastName }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $user->us_email }}</p>
                                    <p class="text-xs text-gray-500">DNI: {{ $user->us_dni }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $user->pets_count }}</div>
                                        <div class="text-xs text-gray-500">Mascotas</div>
                                    </div>
                                    @if($user->pets_count > 0)
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('pets.user-profile', $user->us_id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
                                    <span>Ver Mascotas</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        @if($search)
                            No se encontraron usuarios
                        @else
                            Realiza una búsqueda para encontrar usuarios
                        @endif
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        @if($search)
                            Intenta con otros términos de búsqueda
                        @else
                            Usa el buscador para encontrar usuarios y ver sus mascotas
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Botón para volver -->
        <div class="mt-6 text-center">
            <a href="{{ route('pets.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg inline-flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Volver a Mascotas</span>
            </a>
        </div>
    </div>

@endsection
