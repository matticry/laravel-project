@extends('layouts.app')

@section('title', 'Gestión de Calendario')

@section('header', 'Gestión de Calendario')

@section('content')
    <div x-data="{
        activeTab: 'calendario',
        isModalOpen: false,
        }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                <a @click.prevent="activeTab = 'calendario'" :class="{'border-blue-500 text-blue-800': activeTab === 'calendario'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Calendario
                </a>
                @can('view.index.ordenes')
                    <a href="{{ route('calendario.ordenes') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                        Ordenes de Trabajo
                    </a>
                @endcan
                @can('view.index.reports')
                    <a href="{{ route('reports.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                        Reportes de Técnicos
                    </a>
                @endcan
            </nav>
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

        <div class="flex justify-end mt-4">
            @can('button.create.ordenes')
                <button onclick="showModal('my-modal')" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear orden de trabajo
                </button>
            @endcan
        </div>
        <x-bladewind::modal
            name="my-modal"
            title="Orden de trabajo"
            ok_button_label=""
            cancel_button_label="Cancelar"
            size="xl"
            class="max-h-[90vh]">
            <form id="service-form" method="POST" enctype="multipart/form-data" action="{{ route('calendario.store') }}">
                @csrf
                @method('POST')
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
                    <div class="mt-4 mb-4 p-2">
                        <x-bladewind::alert
                            type="warning">
                            Nota: Esta orden tendrá una vigencia de 72 horas después de ser creada.
                        </x-bladewind::alert>
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
                        <input type="hidden" name="us_id" id="selected-employee-id">
                        <input type="hidden" name="cli_id" id="selected-client-id">

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
                                required
                            ></textarea>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number"
                                                   name="product_quantity[{{ $product->pro_id }}]"
                                                   class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   value="1"
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
                                                   data-tasks="{{ $service->tasks->pluck('id_task')->implode(',') }}">
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
                                            <input type="number" name="service_price[{{ $service->id_serv }}]" placeholder="Ingresa un monto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $service->price_serv }}" min="0" step="0.01">
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
                        Generar Orden de Trabajo
                    </button>
                </div>
            </form>
        </x-bladewind::modal>
        <x-bladewind::modal
            name="work-order-details"
            backdrop_can_close="true"
            ok_button_label="Cerrar"
            center_action_buttons="true">

            <!-- Header con imagen del cliente -->
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <h1 class="text-lg font-bold">Detalles de Orden de Trabajo</h1>
                </div>
                <div class="w-24 h-24 relative">
                    <img id="client-image"
                         alt="Foto del cliente"
                         class="w-full h-full object-cover rounded-full border-2 border-gray-200 shadow-lg"
                         loading="lazy">
                </div>
            </div>

            <!-- Contenido -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Columna izquierda -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Código de Orden</p>
                        <p id="wo-code" class="text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha Inicio</p>
                        <p id="wo-start-date" class="text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estado</p>
                        <p id="wo-status" class="text-sm text-gray-900"></p>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cliente</p>
                        <p id="wo-client" class="text-sm text-gray-900 font-semibold"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Teléfono</p>
                        <p id="wo-client-phone" class="text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dirección</p>
                        <p id="wo-client-address" class="text-sm text-gray-900"></p>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Descripción</p>
                <p id="wo-description" class="text-sm text-gray-900"></p>
            </div>

            <!-- Servicios -->
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Servicios</p>
                <div id="wo-services" class="mt-2"></div>
            </div>

            <!-- Total -->
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p id="wo-total" class="text-sm text-gray-900 font-bold"></p>
            </div>
        </x-bladewind::modal>

        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between mb-6 text-sm">
                <div class="flex items-center bg-blue-100 rounded-lg px-4 py-2">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="font-semibold text-gray-700">Total de Ordenes: {{$total}}  </span>
                </div>
                <div class="flex items-center bg-purple-100 rounded-lg px-4 py-2">
                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold text-gray-700">Ordenes Pendientes: {{$countPending}}</span>
                </div>
                <div class="flex items-center bg-yellow-50 rounded-lg px-4 py-2">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-semibold text-gray-700">Ordenes en Autorizadas: {{$countAssigned}} </span>
                </div>
                <div class="flex items-center bg-green-50 rounded-lg px-4 py-2">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold text-gray-700">Ordenes Finalizadas: {{$countCompleted}}</span>
                </div>
            </div>
            <div id="calendar" class="bg-blue-100 rounded-lg shadow-lg overflow-hidden"></div>
        </div>
    </div>
    <style>
        .upload-signature-btn {
            transition: all 0.3s ease;
        }
        .upload-signature-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        input:checked ~ .dot {
            transform: translateX(100%);
            background-color: #48bb78;
        }
        input:checked ~ .block {
            background-color: #48bb78;
        }
        .tempus-dominus-widget {
            z-index: 9999 !important;
        }
        .fc-timegrid-now-indicator-container {
            width: 80px !important;
        }
        .fc-header-toolbar {
            padding: 10px 0;
        }

        .fc-button-active {
            background-color: #ffffff !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .fc-prev-button, .fc-next-button, .fc-today-button {
            font-weight: bold;
        }
    </style>
    <script src="{{ asset('assets/js/datetimepicker.js') }}"defer></script>
    <script src="{{ asset('assets/js/filter_products_services.js') }}"defer></script>
    <script src="{{ asset('assets/js/config_dropdown.js') }}"defer></script>
    <script src="{{ asset('assets/js/filter-modal-products.js') }}"defer></script>
    <script src="{{ asset('assets/js/filter-modal-services.js') }}"defer></script>
    <script src="{{ asset('assets/js/total-input.js') }}"defer></script>


    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {

                eventClick: function(info) {
                    const workOrderId = info.event.id;

                    fetch(`/getWorkOrderById/${workOrderId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Actualizar el contenido del modal
                            updateModalContent(data);
                            // Abrir el modal usando Bladewind
                            showModal('work-order-details');
                        });
                },
                initialView: 'timeGridWeek',
                allDaySlot: false,
                slotMinTime: '06:00:00',
                height: 'auto',
                slotMaxTime: '20:00:00',
                slotDuration: '00:30:00',
                locale: 'es',
                headerToolbar: {
                    left: 'prev today next',
                    center: 'title',
                    right: ''
                },
                buttonText: {
                    today: 'Today',
                    prev: '<',
                    next: '>'
                },
                events: '{{ route('get.events') }}',
                eventDidMount: function(info) {
                    // Asignar color basado en el estado de la orden
                    switch(info.event.extendedProps.workOrderStatus) {
                        case 'pendiente':
                            info.el.style.backgroundColor = '#D8BFD8';
                            info.el.style.borderColor = '#D8BFD8';
                            break;
                        case 'en_proceso':
                            info.el.style.backgroundColor = '#f6e093';
                            info.el.style.borderColor = '#f6e093';
                            break;
                        case 'finalizado':
                            info.el.style.backgroundColor = '#9ff693';
                            info.el.style.borderColor = '#9ff693';
                            break;
                        default:
                            info.el.style.backgroundColor = '#E6E6FA';
                            info.el.style.borderColor = '#D8BFD8';
                    }
                    info.el.style.color = '#000000';
                },
                eventColor: '#E6E6FA',
                eventTextColor: '#000000',
                eventBorderColor: '#D8BFD8',
                dayHeaderFormat: { weekday: 'short' },
                views: {
                    timeGridWeek: {
                        dayHeaderContent: (args) => {
                            return {
                                html:
                                    '<div class="fc-col-header-cell-cushion" style="text-transform: uppercase;">' +
                                    args.date.toLocaleDateString('es', {weekday: 'short'}) +
                                    '</div>' +
                                    '<div class="fc-col-header-cell-cushion" style="font-size: 0.8em;">' +
                                    args.date.getDate() +
                                    '</div>'
                            };
                        }
                    }
                },
                nowIndicator: true,
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: 'short'
                }
            });
            calendar.render();

            applyCustomStyles();
        });
        function generateAvatarUrl(name) {
            const encodedName = encodeURIComponent(name);
            return `https://ui-avatars.com/api/?name=${encodedName}&background=random&size=128`;
        }

        function updateModalContent(data) {
            // Actualizar imagen del cliente
            const clientImage = document.getElementById('client-image');
            if (data.client.cli_image) {
                clientImage.src = data.client.cli_image;
            } else {
                // Si no hay imagen, usar el avatar generado
                clientImage.src = generateAvatarUrl(data.client.cli_name);
            }

            // Manejar errores de carga de imagen
            clientImage.onerror = function() {
                this.src = generateAvatarUrl(data.client.cli_name);
            }
            const phoneElement = document.getElementById('wo-client-phone');
            if (!data.client.cli_phone || data.client.cli_phone.trim() === '') {
                phoneElement.innerHTML = `
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-sm">
                No registró número
            </span>
        `;
            } else {
                phoneElement.textContent = data.client.cli_phone;
            }

            // Dirección con validación
            const addressElement = document.getElementById('wo-client-address');
            if (!data.client.cli_address || data.client.cli_address.trim() === '') {
                addressElement.innerHTML = `
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 italic text-sm">
                No registró dirección
            </span>
        `;
            } else {
                addressElement.textContent = data.client.cli_address;
            }

            // Resto de tu código actual
            document.getElementById('wo-code').textContent = data.wo_order_code;
            document.getElementById('wo-client').textContent = data.client.cli_name;
            document.getElementById('wo-client-phone').textContent = data.client.cli_phone;
            document.getElementById('wo-client-address').textContent = data.client.cli_address;
            document.getElementById('wo-start-date').textContent = data.wo_start_date;
            document.getElementById('wo-status').textContent = data.wo_status;
            document.getElementById('wo-description').textContent = data.wo_description;
            document.getElementById('wo-total').textContent = `$${data.wo_total}`;

            // Mostrar servicios
            const servicesContainer = document.getElementById('wo-services');
            servicesContainer.innerHTML = '';
            data.services.forEach(service => {
                const serviceElement = document.createElement('div');
                serviceElement.className = 'mt-2 p-2 border rounded';
                serviceElement.innerHTML = `
            <p class="font-medium">Servicio ID: ${service.service_id}</p>
            <p>Precio: $${service.price_service}</p>
            ${service.tasks.length > 0 ? '<p class="mt-1">Tareas:</p>' : ''}
            <ul class="list-disc ml-4">
                ${service.tasks.map(task => `
                    <li>Tarea ID: ${task.task_id} - Estado: ${task.task_status}</li>
                `).join('')}
            </ul>
        `;
                servicesContainer.appendChild(serviceElement);
            });
        }

        function applyCustomStyles() {
            document.querySelectorAll('.fc-col-header-cell').forEach(cell => {
                cell.style.borderRadius = '8px';
                cell.style.border = '1px solid #e5e7eb';
                cell.style.padding = '4px';
                cell.style.margin = '2px';
            });

            document.querySelectorAll('.fc-timegrid-slot').forEach(slot => {
                slot.style.height = '25px';
            });

            document.querySelectorAll('.fc-event').forEach(event => {
                event.style.borderRadius = '4px';
                event.style.border = '1px solid #D8BFD8';
                event.style.fontSize = '0.8em';
                event.style.padding = '2px 4px';
            });

            // Estilos para el indicador de tiempo actual
            document.querySelectorAll('.fc-timegrid-now-indicator-line').forEach(line => {
                line.style.borderColor = '#3778ff';
                line.style.borderWidth = '2px';
            });

            // Estilos para las etiquetas de tiempo
            document.querySelectorAll('.fc-timegrid-slot-label-cushion').forEach(label => {
                label.style.fontFamily = "'Helvetica Neue', Arial, sans-serif";
                label.style.color = '#9ca3af'; // gray-400
                label.style.fontSize = '0.875rem';
                label.style.fontWeight = '500';
            });
            document.querySelectorAll('.fc-button-group').forEach(group => {
                group.style.background = '#f3f4f6';
                group.style.borderRadius = '20px';
                group.style.padding = '2px';
            });

            document.querySelectorAll('.fc-button-primary').forEach(button => {
                button.style.background = 'transparent';
                button.style.border = 'none';
                button.style.color = '#4b5563';
                button.style.margin = '0 2px';
                button.style.padding = '5px 10px';
                button.style.borderRadius = '18px';
            });

            document.querySelector('.fc-prev-button').style.marginRight = '5px';
            document.querySelector('.fc-next-button').style.marginLeft = '5px';

            document.querySelector('.fc-today-button').style.background = '#3778ff';
            document.querySelector('.fc-today-button').style.borderRadius = '20px';
            document.querySelector('.fc-today-button').style.border = 'none';
            document.querySelector('.fc-today-button').style.color = '#000000';
            document.querySelector('.fc-today-button').style.fontWeight = 'bold';
            document.querySelector('.fc-today-button').style.padding = '5px 15px';
            document.querySelector('.fc-today-button').style.marginLeft = '10px';

            // Estilo para el título del mes
            document.querySelector('.fc-toolbar-title').style.fontSize = '1.2rem';
            document.querySelector('.fc-toolbar-title').style.fontWeight = 'bold';
            document.querySelector('.fc-toolbar-title').style.color = '#111827';
        }
    </script>

    <style>

    </style>
@endsection
