@extends('layouts.app')

@section('title', 'Gestión de Órdenes de Trabajo')

@section('header', 'Órdenes de Trabajo')

@section('content')
    <div x-data="{
        activeTab: 'ordenes',
        }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                @can('view.index.calendar')
                    <a href="{{ route('calendario.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                        Calendario
                    </a>
                @endcan
                <a @click.prevent="activeTab = 'ordenes'" :class="{'border-blue-500 text-blue-800': activeTab === 'ordenes'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Órdenes de Trabajo
                </a>
                    @can('view.index.reports')
                        <a href="{{ route('reports.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                            Reportes de Técnicos
                        </a>
                    @endcan
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
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Nombre del Servicio</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Total del Servicio (USD)</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Estado de la Órden</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($workOrders as $workorder)
                    <tr>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->wo_order_code }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->wo_start_date }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->wo_final_date }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->user->us_name ?? 'N/A' }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $workorder->client->us_name ?? 'N/A' }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($workorder->services as $service)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $service->service->name_serv ?? 'N/A' }}
                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            @if($workorder->wo_total)
                                ${{ number_format($workorder->wo_total, 2) }}
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm">Sin Total</span>
                            @endif
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            @php
                                $statusClass = [
                                    'pendiente' => 'bg-red-100 text-red-800',
                                    'en_proceso' => 'bg-yellow-100 text-yellow-800',
                                    'finalizado' => 'bg-green-100 text-green-800'
                                ][$workorder->wo_status] ?? 'bg-gray-100 text-gray-800';

                                $statusText = [
                                    'pendiente' => 'No autorizada',
                                    'en_proceso' => 'Autorizada en proceso',
                                    'finalizado' => 'Finalizada'
                                ][$workorder->wo_status] ?? 'Desconocido';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                    {{ $statusText }}
                </span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <div class="flex space-x-2">
                                @if($workorder->wo_status == 'pendiente')
                                    <form id="authorize-form-{{ $workorder->wo_id }}" action="{{ route('workorders.authorize', $workorder->wo_id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button"
                                                class="bg-yellow-100 text-yellow-600 hover:bg-yellow-200 rounded-full p-2"
                                                onclick="showModal('authorize-modal-{{ $workorder->wo_id }}')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <x-bladewind::modal
                                        type="warning"
                                        title="Confirmar autorización"
                                        ok-button-label=""
                                        cancel-button-label=""
                                        name="authorize-modal-{{ $workorder->wo_id }}">
                                        <p>¿Estás seguro de querer autorizar esta orden de trabajo?</p>
                                        <div class="mt-4">
                                            <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="hideModal('authorize-modal-{{ $workorder->wo_id }}')">Cancelar</button>
                                            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded" onclick="document.getElementById('authorize-form-{{ $workorder->wo_id }}').submit()">Sí, autorizar</button>
                                        </div>
                                    </x-bladewind::modal>
                                @endif
                                @can('workorders.edit')
                                    <button type="button"
                                            onclick="showOrderDetail('{{$workorder->wo_id}}', 'edit-modal')"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-green-100 text-green-600 hover:bg-green-200 rounded-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                @endcan
                                @can('workorders.destroy')
                                    <form action="{{ route('calendario.destroy', $workorder->wo_id) }}" method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                onclick="return confirm('¿Estás seguro de querer eliminar esta orden de trabajo?')">
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
                    <x-bladewind::modal
                        name="edit-modal"
                        title="Actualizar Orden de trabajo"
                        ok_button_label=""
                        cancel_button_label="Cancelar"
                        size="xl"
                        class="max-h-[90vh]">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">¡Oops!</strong>
                                <span class="block sm:inline">{{ $errors->first() }}</span>
                            </div>
                        @endif

                            <form id="service-form" method="POST" enctype="multipart/form-data" action="{{ route('calendario.update', ['workOrderId' => $workorder->wo_id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
                                    @can('button.signature')
                                        <div class="mt-6 mb-4">
                                            <h3 class="text-lg font-semibold mb-2">La orden irá firmada por:</h3>
                                            <div class="space-y-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="firma_cliente">
                                                    <span class="ml-2 text-gray-700">Firma del tecnico</span>
                                                </label>

                                                <label class="flex items-center">
                                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="firma_empleado">
                                                    <span class="ml-2 text-gray-700">Firma del administrador</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endcan

                                    <!-- Sección de botones -->
                                    <div class="absolute top-4 right-8 flex space-x-2">
                                        <button class="p-2 rounded-full bg-blue-500 hover:bg-blue-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out" title="Refrescar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                        @can('button.add.client')
                                            <button  class="p-2 rounded-full bg-blue-500 hover:bg-blue-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out" title="Insertar Cliente">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('button.add.service')
                                            <button class="p-2 rounded-full bg-green-500 hover:bg-green-600 text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-300 ease-in-out" title="Crear Servicio">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 012 0v6a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endcan
                                        <button class="p-2 rounded-full bg-blue-500 hover:bg-blue-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out" title="Buscar Cliente">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-4 mb-4 p-2 bg-yellow-100 rounded-md">
                                        <p class="text-sm text-yellow-700">Nota: Esta orden tendrá una vigencia de 72 horas después de ser creada.</p>
                                    </div>
                                    <!-- Contenedor flex para los dropdowns -->
                                    <div class="flex space-x-4">
                                        <!-- Primer Dropdown -->
                                        <div class="relative group flex-1">
                                            <button id="dropdown-button-1" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                                <span class="mr-2">Empleados</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div id="dropdown-menu-1" class="hidden absolute left-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-1 z-50">
                                                <input id="search-input-1" class="block w-full px-4 py-2 text-gray-800 border rounded-md border-gray-300 focus:outline-none" type="text" placeholder="Buscar empleados" autocomplete="off">
                                                <div class="dropdown-scroll mt-1 max-h-40 overflow-y-auto">
                                                    @foreach($employees as $employee)
                                                        <option class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md" value="{{ $employee->us_id }}">{{ $employee->us_name }}</option>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="us_id" id="selected-employee-id" value="{{ $workorder->us_id }}">
                                        <input type="hidden" name="cli_id" id="selected-client-id" value="{{ $workorder->cli_id }}">

                                        <!-- Segundo Dropdown -->
                                        <div class="relative group flex-1">
                                            <button id="dropdown-button-2" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                                <span class="mr-2">Clientes</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div id="dropdown-menu-2" class="hidden absolute left-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-1 z-50">
                                                <input id="search-input-2" class="block w-full px-4 py-2 text-gray-800 border rounded-md border-gray-300 focus:outline-none" type="text" placeholder="Buscar empleados" autocomplete="off">
                                                <div class="dropdown-scroll mt-1 max-h-40 overflow-y-auto">
                                                    @foreach($clients as $client)
                                                        <option class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md" value="{{ $client->us_id }}">{{ $client->us_name }}</option>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-4 mb-4 mt-5">
                                        <div class="flex-1">
                                            <div class="relative">
                                                <label for="start_datetime" class="block text-sm font-medium text-gray-700">Fecha y hora de inicio</label>
                                                <input type="text" id="start_datetime" name="wo_start_date" placeholder="dd/mm/yyyy hh:mm"
                                                           value="{{ $workorder->wo_start_date ? \Carbon\Carbon::parse($workorder->wo_start_date)->format('d/m/Y H:i') : '' }}"
                                                           class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-calendar text-gray-400 mt-5"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="relative">
                                                <label for="end_datetime" class="block text-sm font-medium text-gray-700">Fecha y hora de fin</label>
                                                <input type="text" id="end_datetime" name="wo_final_date" placeholder="dd/mm/yyyy hh:mm"
                                                       value="{{ $workorder->wo_final_date ? \Carbon\Carbon::parse($workorder->wo_final_date)->format('d/m/Y H:i') : '' }}"
                                                       class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-calendar text-gray-400 mt-5"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Descripción -->
                                    <div class="mb-6">
                                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Descripción</label>
                                        <textarea
                                            id="description"
                                            name="wo_description"
                                            rows="4"
                                            placeholder="Ingrese una descripción detallada aquí..."
                                            class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 resize-none transition duration-200 ease-in-out"
                                            required>{{ $workorder->wo_description }}</textarea>
                                    </div>

                                    <!-- Tabla de Productos -->
                                    <div class="mb-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <input type="text" id="product-filter" class="w-64 px-3 py-2 border rounded-md" placeholder="Filtrar por código de producto">
                                            <x-bladewind::button
                                                size="tiny"
                                                onclick="showModal('product-selection-modal')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                                Agregar Productos
                                            </x-bladewind::button>
                                        </div>
                                        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                            <table id="product-table" class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seleccionar</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Producto</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($products as $product)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded"
                                                                   name="selected_products[]" value="{{ $product->pro_id }}"
                                                                {{ in_array($product->pro_id, $workorder->details->pluck('pro_id')->toArray()) ? 'checked' : '' }}>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $product->pro_code }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <img src="{{ asset('storage/' . $product->pro_image) }}" alt="{{ $product->pro_name }}" class="w-16 h-16 object-cover rounded-md">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $product->pro_name }}</div>
                                                        </td>
                                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                                            @if($product->pro_amount > 0)
                                                                {{ $product->pro_amount }}
                                                            @else
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-gray-500 text-sm">Sin Stock</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="number"
                                                                   name="product_quantity[{{ $product->pro_id }}]"
                                                                   class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
                                                                   value="{{ $workorder->details->where('pro_id', $product->pro_id)->first()->dwo_amount ?? 1 }}"
                                                                   min="1"
                                                                   max="{{ $product->pro_amount }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Tabla de Servicios -->
                                    <div class="mb-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <input type="text" id="service-filter" class="w-64 px-3 py-2 border rounded-md" placeholder="Filtrar por nombre de servicio">
                                            <x-bladewind::button
                                                size="tiny"
                                                onclick="showModal('service-selection-modal')"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                                Agregar Servicios
                                            </x-bladewind::button>
                                        </div>
                                        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                            <table id="service-table" class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seleccionar</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Servicio</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tareas</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio (USD)</th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($services as $service)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="checkbox"
                                                                   class="form-checkbox h-5 w-5 text-blue-600"
                                                                   name="selected_services[]"
                                                                   value="{{ $service->id_serv }}"
                                                                   data-tasks="{{ $service->tasks->pluck('id_task')->implode(',') }}"
                                                                {{ in_array($service->id_serv, $workorder->services->pluck('service_id')->toArray()) ? 'checked' : '' }}>
                                                        </td>
                                                        <input type="hidden" name="service_tasks[{{ $service->id_serv }}]" value="{{ $service->tasks->pluck('id_task')->implode(',') }}">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $service->name_serv }}</div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach($service->tasks as $task)
                                                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $task->name_task }}</span>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="number" name="service_price[{{ $service->id_serv }}]"
                                                                   placeholder="Ingresa un monto"
                                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                   value="{{ $workorder->services->where('service_id', $service->id_serv)->first()->price_service ?? $service->price_serv }}"
                                                                   min="0" step="0.01">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Modal para seleccionar productos -->
                                    <x-bladewind::modal
                                        name="product-selection-modal"
                                        title="Seleccionar Productos"
                                        ok_button_label="Agregar Seleccionados"
                                        ok_button_action="addSelectedProductsToMainTable()"
                                        close_after_action="false"
                                        size="xl">

                                        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                            <div class="flex justify-between items-center mb-2">
                                                <input type="text" id="modal-product-filter" class="w-64 px-3 py-2 border rounded-md" placeholder="Filtrar por código de producto">
                                            </div>
                                            <table id="modal-product-table" class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seleccionar</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Producto</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($allProducts as $product)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" name="selected_products[]" value="{{ $product->pro_id }}">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $product->pro_code }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <img src="{{ asset('storage/' . $product->pro_image) }}" alt="{{ $product->pro_name }}" class="w-16 h-16 object-cover rounded-md">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $product->pro_name }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">{{ $product->pro_amount }}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </x-bladewind::modal>

                                    <!-- Modal para seleccionar servicios -->
                                    <x-bladewind::modal
                                        name="service-selection-modal"
                                        title="Seleccionar Servicios"
                                        ok_button_label="Agregar Seleccionados"
                                        ok_button_action="addSelectedServices()"
                                        close_after_action="false"
                                        size="xl">
                                        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                            <div class="flex justify-between items-center mb-2">
                                                <input type="text" id="modal-service-filter" class="w-64 px-3 py-2 border rounded-md" placeholder="Filtrar por nombre de servicio">
                                            </div>
                                            <table id="modal-service-table" class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seleccionar</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Servicio</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tareas</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio (USD)</th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($allServices as $service)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="selected_products[]" value="{{ $service->id_serv }}">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $service->name_serv }}</div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach($service->tasks as $task)
                                                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $task->name_task }}</span>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $service->price_serv }}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </x-bladewind::modal>
                                    <div class="mt-4">
                                        <p id="total-display" class="text-right font-bold text-2xl text-gray-800 bg-gray-100 px-4 py-2 rounded-md shadow-sm">Total: $ 0.00</p>
                                    </div>
                                    <input type="hidden" name="wo_total" id="wo_total" value="0">
                                    @can('button.upload.signature')
                                        <div class="mt-4">
                                            <button class="upload-signature-btn bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Subir Firma
                                            </button>
                                        </div>
                                    @endcan
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Actualizar Orden de Trabajo
                                    </button>
                                </div>
                            </form>
            </x-bladewind::modal>
        </div>
    </div>

@endsection
<script src="{{ asset('assets/js/datetimepicker.js') }}"defer></script>
<script src="{{ asset('assets/js/filter_products_services.js') }}"defer></script>
<script src="{{ asset('assets/js/config_dropdown.js') }}"defer></script>
<script src="{{ asset('assets/js/filter-modal-products.js') }}"defer></script>
<script src="{{ asset('assets/js/filter-modal-services.js') }}"defer></script>
<script src="{{ asset('assets/js/total-input.js') }}"defer></script>



