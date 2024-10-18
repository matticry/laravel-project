@extends('layouts.app')

@section('title', 'Gestión de Reportes de Técnicos')

@section('header', 'Reportes de Técnicos')

@section('content')
    <div x-data="{
        activeTab: 'reportes',
        }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                @can('view.index.calendar')
                    <a href="{{ route('calendario.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                        Calendario
                    </a>
                @endcan
                @can('view.index.ordenes')
                    <a href="{{ route('calendario.ordenes') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                        Órdenes de Trabajo
                    </a>
                @endcan
                <a @click.prevent="activeTab = 'reportes'" :class="{'border-blue-500 text-blue-800': activeTab === 'reportes'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Reporte de Técnicos
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

    <div x-show="activeTab === 'reportes'" class="mt-6">
        <h2 class="text-2xl font-semibold text-gray-900">Gestión de Reportes de Técnicos</h2>

        <div class="mt-4">
        @can('button.create.reports')
            <a href="{{ route('reports.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Reporte
            </a>
        @endcan
        </div>

        <!-- Formulario de búsqueda -->
        <div class="mb-4 mt-4 flex items-center space-x-4">
            <form action="{{ route('reports.index') }}" method="GET" class="flex space-x-2">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Buscar reporte:</label>
                    <input type="text" name="search" id="search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Buscar por código" value="{{ request()->get('wo_order_code') }}">
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de reportes -->
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                <tr class="text-left">
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Número del Reporte</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Descripción del Técnico</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Código de Orden</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Imagen Antes de la Órden</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Imagen Despues de la Órden</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Firma del Técnico</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Firma del Cliente</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Fecha de Creación</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">PDF del Reporte</th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $report->id_report }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $report->description_report }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            @if($report->workOrder)
                                {{ $report->workOrder->wo_order_code }}
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> Sin Orden</span>

                            @endif
                        </td>

                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <x-bladewind::button
                                color="cyan"
                                show_close_icon="true"
                                onclick="showModal('image-before-{{ $report->id_report }}')">
                                Ver Imagen Antes
                            </x-bladewind::button>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <x-bladewind::button
                                color="cyan"
                                show_close_icon="true"
                                onclick="showModal('image-report-{{ $report->id_report }}')">
                                Ver Imagen Despues
                            </x-bladewind::button>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <x-bladewind::button
                                color="cyan"
                                show_close_icon="true"
                                onclick="showModal('signature-report-{{ $report->id_report }}')">
                                Ver Firma del Técnico
                            </x-bladewind::button>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <x-bladewind::button
                                color="cyan"
                                show_close_icon="true"
                                onclick="showModal('signature-client-{{ $report->id_report }}')">
                                Ver Firma Cliente
                            </x-bladewind::button>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            {{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('DD [de] MMM [del] YYYY, HH:mm') }}
                        </td>


                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            @if($report->pdf_report)
                                <x-bladewind::button
                                    show_close_icon="true"
                                    onclick="showModal('pdf-report-{{ $report->id_report }}')">
                                    Ver PDF
                                </x-bladewind::button>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Sin PDF
                                </span>
                            @endif
                        </td>

                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <div class="flex space-x-2">
                                @can('reports.update')
                                    <button type="button"
                                            class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2"
                                            onclick="showModal('edit-modal-{{ $report->id_report }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                @endcan
                                    @can('reports.destroy')
                                        <button type="button"
                                                class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                onclick="showModal('delete-modal-{{ $report->id_report }}')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <form id="delete-form-{{ $report->id_report }}" action="{{ route('reports.destroy', $report->id_report) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <x-bladewind::modal
                                            type="error"
                                            title="Confirmar eliminación"
                                            ok_button_label="Sí, eliminar"
                                            cancel_button_label="Cancelar"
                                            name="delete-modal-{{ $report->id_report }}"
                                            ok_button_action="document.getElementById('delete-form-{{ $report->id_report }}').submit()">
                                            <p>¿Estás seguro de que quieres eliminar este reporte? Esto no se puede deshacer.</p>
                                        </x-bladewind::modal>
                                    @endcan

                                <!-- Botón para generar PDF -->
                                    <button type="button"
                                            class="inline-block bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-full p-2"
                                            onclick="showModal('generate-pdf-modal-{{ $report->id_report }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10h4v4h-4v-4z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 16h4"></path>
                                        </svg>
                                    </button>

                                    <button type="button"
                                            class="inline-block bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2"
                                            onclick="showModal('email-modal-{{ $report->id_report }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>

                                    <x-bladewind::modal
                                        type="info"
                                        title="Enviar correo electrónico"
                                        ok_button_label=""
                                        cancel_button_label="Cancelar"
                                        name="email-modal-{{ $report->id_report }}">
                                        <p>¿Estás seguro de que quieres enviar este reporte por correo electrónico al cliente?</p>
                                        <form action="{{ route('reports.send-email', $report->id_report) }}" method="POST" class="mt-4">
                                            @csrf
                                            @method('PATCH')
                                            <x-bladewind::button
                                                type="primary"
                                                can_submit="true"
                                                label="Enviar correo"
                                            >
                                                Enviar correo al cliente
                                            </x-bladewind::button>

                                        </form>
                                    </x-bladewind::modal>

                                    <x-bladewind::modal
                                        icon="document-text"
                                        icon_css="bg-blue-500 text-white p-2.5 rounded-full"
                                        title="Generar PDF del Reporte"
                                        name="generate-pdf-modal-{{ $report->id_report }}"
                                        ok_button_label=""
                                        class="w-full max-w-lg md:max-w-xl lg:max-w-2xl xl:max-w-3xl">
                                        <div class="p-4 sm:p-6 md:p-8">
                                            <p class="text-sm sm:text-base md:text-lg">¿Deseas generar el PDF para este reporte?</p>
                                            <form id="generate-pdf-form-{{ $report->id_report }}" method="POST" action="{{ route('generate.pdf', $report->id_report) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="mt-4">
                                                    <button type="button"
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm sm:text-base md:px-6 md:py-3 transition duration-300 ease-in-out"
                                                            onclick="document.getElementById('generate-pdf-form-{{ $report->id_report }}').submit();">
                                                        Generar PDF
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-bladewind::modal>
                            </div>
                            <x-bladewind::modal
                                name="edit-modal-{{ $report->id_report }}"
                                title="Editar Reporte"
                                ok_button_label=""
                                size="omg"
                                cancel_button_label="Cancelar">
                                <div class="max-h-[80vh] overflow-y-auto px-4">
                                    <form action="{{ route('reports.update', $report->id_report) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-4">
                                            <label for="description_report" class="block text-sm font-medium text-gray-700">Descripción del Reporte</label>
                                            <textarea id="description_report" placeholder="Ingresa la descripción del reporte" name="description_report" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $report->description_report }}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label for="image_before_report" class="block text-sm font-medium text-gray-700">Imagen Antes del Servicio</label>
                                            <div x-data="{ imageUrl: '{{ $report->image_before_report }}' }" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-md" x-cloak alt="Imagen antes del servicio">
                                                    <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="image_before_report" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span>Subir un archivo</span>
                                                            <input id="image_before_report" name="image_before_report" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                        <p class="pl-1">o arrastrar y soltar</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="image_report" class="block text-sm font-medium text-gray-700">Imagen Después del Servicio</label>
                                            <div x-data="{ imageUrl: '{{ $report->image_report }}' }" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-md" x-cloak alt="Imagen después del servicio">
                                                    <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="image_report" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span>Subir un archivo</span>
                                                            <input id="image_report" name="image_report" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                        <p class="pl-1">o arrastrar y soltar</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="signature_report" class="block text-sm font-medium text-gray-700">Firma del Técnico (encargado)</label>
                                            <div x-data="{ imageUrl: '{{ $report->signature_report }}' }" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-md" x-cloak alt="Firma del técnico">
                                                    <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="signature_report" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span>Subir un archivo</span>
                                                            <input id="signature_report" name="signature_report" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                        <p class="pl-1">o arrastrar y soltar</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="signature_client_report" class="block text-sm font-medium text-gray-700">Firma del Cliente</label>
                                            <div x-data="{ imageUrl: '{{ $report->signature_client_report }}' }" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <img x-show="imageUrl" :src="imageUrl" class="mx-auto h-32 w-32 object-cover rounded-md" x-cloak alt="Firma del cliente">
                                                    <svg x-show="!imageUrl" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="signature_client_report" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span>Subir un archivo</span>
                                                            <input id="signature_client_report" name="signature_client_report" type="file" class="sr-only" @change="imageUrl = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                        <p class="pl-1">o arrastrar y soltar</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <h3 class="text-lg font-semibold">Productos Usados</h3>
                                                <x-bladewind::button
                                                    size="tiny"
                                                    onclick="showModal('product-selection-modal')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Agregar Productos
                                                </x-bladewind::button>
                                            </div>
                                            <div id="hidden-products-container"></div>
                                            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                                                <table id="product-table" class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seleccionar</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Producto</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Usada</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                    @forelse($report->workOrder->usedProducts ?? [] as $usedProduct)
                                                        <tr class="hover:bg-gray-50" data-product-id="{{ $usedProduct->pro_id ?? '' }}">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" checked disabled>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm font-medium text-gray-900">{{ $usedProduct->product->pro_code ?? 'N/A' }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if($usedProduct->product->pro_image ?? false)
                                                                    <img src="{{ asset('storage/' . $usedProduct->product->pro_image) }}" alt="{{ $usedProduct->product->pro_name ?? 'Producto' }}" class="w-16 h-16 object-cover rounded-md">
                                                                @else
                                                                    <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-500">No imagen</div>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm font-medium text-gray-900">{{ $usedProduct->product->pro_name ?? 'N/A' }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm text-gray-500">{{ $usedProduct->product->pro_amount ?? 'N/A' }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <input type="number"
                                                                       name="product_quantity[{{ $usedProduct->pro_id ?? '' }}]"
                                                                       class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                       value="{{ $usedProduct->up_amount ?? '' }}"
                                                                       min="1"
                                                                       max="{{ $usedProduct->product->pro_amount ?? '' }}">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <button type="button"
                                                                        class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2"
                                                                        onclick="showModal('delete-product-modal-{{ $usedProduct->id_up ?? '' }}')">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                </button>
                                                            </td>

                                                            <!-- Modal de confirmación para eliminar producto -->
                                                            <x-bladewind::modal
                                                                name="delete-product-modal-{{ $usedProduct->id_up ?? '' }}"
                                                                title="Confirmar eliminación"
                                                                ok_button_label=""
                                                                type="error"
                                                                cancel_button_label="Cancelar">
                                                                <p>¿Estás seguro de que quieres eliminar este producto del reporte?</p>
                                                                <form action="{{ route('reports.remove-product', ['report' => $report->id_report ?? '', 'usedProduct' => $usedProduct->id_up ?? '']) }}" method="POST" class="mt-4">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <x-bladewind::button
                                                                        type="primary"
                                                                        can_submit="true"
                                                                        label="Sí, eliminar">
                                                                        Sí, eliminar
                                                                    </x-bladewind::button>
                                                                </form>
                                                            </x-bladewind::modal>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                                No hay productos usados para mostrar.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Actualizar Reporte
                                        </button>
                                    </form>
                                </div>
                            </x-bladewind::modal>
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
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modales para las imágeness -->
        @php
            $slimBaseUrl = 'https://slim-api-project-e1fc80b7d790.herokuapp.com';
        @endphp

        @foreach($reports as $report)
            <x-bladewind::modal
                name="image-report-{{ $report->id_report }}"
                title="Imagen del Reporte">
                <img src="{{ $slimBaseUrl . $report->image_report }}" alt="Imagen del Reporte">
            </x-bladewind::modal>

            <x-bladewind::modal
                name="signature-report-{{ $report->id_report }}"
                title="Firma del Técnico">
                <img src="{{ $slimBaseUrl . $report->signature_report }}" alt="Firma del Técnico">
            </x-bladewind::modal>

            <x-bladewind::modal
                name="image-before-{{ $report->id_report }}"
                title="Imagen Antes">
                <img src="{{ $slimBaseUrl . $report->image_before_report }}" alt="Imagen Antes">
            </x-bladewind::modal>

            <x-bladewind::modal
                name="signature-client-{{ $report->id_report }}"
                title="Firma del Cliente">
                <img src="{{ $slimBaseUrl . $report->signature_client_report }}" alt="Firma del Cliente">
            </x-bladewind::modal>
            <x-bladewind::modal
                name="pdf-report-{{ $report->id_report }}"
                title="PDF del Reporte"
                ok_button_label="Cerrar"
                size="omg">
                <iframe src="{{ route('serve.pdf', $report->id_report) }}" type="application/pdf" style="width: 100%; height: 500px;"></iframe>
            </x-bladewind::modal>
        @endforeach


    </div>
@endsection
<script>
    function addSelectedProductsToMainTable() {
        const modalTable = document.getElementById('modal-product-table');
        const mainTable = document.getElementById('product-table');
        const checkboxes = modalTable.querySelectorAll('input[type="checkbox"]:checked');
        const hiddenProductsContainer = document.getElementById('hidden-products-container');

        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            const productId = checkbox.value;
            const productCode = row.cells[1].innerText;
            const productImage = row.cells[2].querySelector('img').src;
            const productName = row.cells[3].innerText;
            const productStock = parseInt(row.cells[4].innerText);

            // Verificar si el producto ya existe en la tabla principal
            const existingRow = mainTable.querySelector(`tr[data-product-id="${productId}"]`);
            if (existingRow) {
                console.log('El producto ya existe en la tabla principal');
                return;
            }

            // Crear nueva fila en la tabla principal
            const newRow = mainTable.insertRow(-1);
            newRow.setAttribute('data-product-id', productId);
            newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" checked>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${productCode}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <img src="${productImage}" alt="${productName}" class="w-16 h-16 object-cover rounded-md">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${productName}</td>
            <td class="px-6 py-4 whitespace-nowrap">${productStock}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" name="used_products[${productId}][up_amount]" value="1" min="1" max="${productStock}" class="w-20 rounded">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button type="button" onclick="removeProduct(this)" class="inline-block bg-red-100 text-red-600 hover:bg-red-200 rounded-full p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        `;

            // Agregar campo oculto para el ID del producto
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `used_products[${productId}][pro_id]`;
            hiddenInput.value = productId;
            hiddenProductsContainer.appendChild(hiddenInput);
        });

        hideModal('product-selection-modal');
    }
</script>

