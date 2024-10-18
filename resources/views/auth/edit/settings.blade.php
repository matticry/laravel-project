@extends('layouts.app')

@section('title', 'Gestión de Perfil')

@section('header', 'Gestión de Perfil')

@section('content')
    <div x-data="{
        activeTab: 'edit-profile',
        }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                <a href="{{route('edit.profile')}}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Editar Perfil
                </a>
                <a @click.prevent="activeTab = 'settings-profile'" :class="{'border-blue-500 text-blue-800': activeTab === 'settings-profile'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Configuracion del Sistema
                </a>

            </nav>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md relative mb-4" role="alert">
            <button class="absolute top-2 right-2 text-green-700 hover:bg-green-200 p-1 rounded transition duration-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <strong class="font-bold">¡Éxito!</strong>
            </div>
            <span class="block mt-2">{{ session('success') }}</span>
            <div class="mt-3">
                <a href="{{ route('calendario.ordenes') }}" class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300 mr-3">Ver detalles</a>
                <button @click="show = false" class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300">Cerrar</button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <x-bladewind::alert type="error">
            <strong class="font-bold">¡Oops!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </x-bladewind::alert>
    @endif

    <div x-show="activeTab === 'settings-profile'" class="bg-blue-50 min-h-screen p-6">
@endsection
