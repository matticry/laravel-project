@extends('layouts.app')

@section('title', 'Gestión de Órdenes de Trabajo')

@section('header', 'Órdenes de Trabajo')

@section('content')
    <div x-data="{
        activeTab: 'ordenes',
        isModalOpen: false,
        }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                <a href="{{ route('calendario.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Calendario
                </a>
                <a @click.prevent="activeTab = 'ordenes'" :class="{'border-blue-500 text-blue-800': activeTab === 'ordenes'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Órdenes de Trabajo
                </a>
                <a href="{{ route('employees.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Reportes de Técnicos
                </a>
            </nav>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Oops!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Contenido de cada sección -->
    <div x-show="activeTab === 'ordenes'" class="mt-6">
        <h2 class="text-2xl font-semibold text-gray-900">Gestión de Órdenes de Trabajo</h2>
        <div class="mb-4 mt-4 flex items-center space-x-4">
            <!-- Formulario de búsqueda -->
            <form action="{{ route('employees.index') }}" method="GET" class="flex space-x-2">
                <!-- Campo para código de empleado -->
                <div>
                    <label for="code_emplo" class="block text-sm font-medium text-gray-700">Código de la órdenes:</label>
                    <input type="text" name="code_emplo" autocomplete="off" id="code_emplo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Filtrar por código" value="{{ request()->get('code_emplo') }}">
                </div>
                <!-- Botón para realizar la búsqueda -->
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de ordenes -->
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                <tr class="text-left">
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Código de la Órden</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Fecha de Inicio</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Fecha de Finalización</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre del Técnico</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre del Cliente</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre del Servicio </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Total del Servicio </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado de la Órden</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($workOrders as $workorder)
                    <tr>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->wo_order_code }}
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->wo_start_date }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->wo_final_date }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->user->us_name ?? 'N/A' }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->client->us_name ?? 'N/A' }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            {{ $workorder->services->first()->service->name_serv ?? 'N/A' }}
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">

                            @if($workorder->wo_total)
                                {{ $workorder->wo_total }}
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm">Sin Total</span>
                            @endif
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $workorder->wo_status == 'finalizado'  ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'  }}">
                                    {{ $workorder->wo_status == 'finalizado'  ? 'en_' : 'pendiente' }}
                                </span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <div class="flex space-x-2">
                                @can('profile.update')
                                    <a href="{{ route('profile.edit', $workorder->us_id) }}"
                                       class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('profile.destroy')
                                    <form action="{{ route('profile.destroy', $workorder->us_id) }}" method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                onclick="return confirm('¿Estás seguro de querer eliminar este empleado?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection


