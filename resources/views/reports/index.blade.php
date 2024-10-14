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

    <div class="mt-6">
        <h2 class="text-2xl font-semibold text-gray-900">Gestión de Reportes de Técnicos</h2>

        <!-- Botón para crear nuevo reporte -->
        <div class="mt-4">
            <a href="{{ route('reports.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Crear Nuevo Reporte
            </a>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="mb-4 mt-4 flex items-center space-x-4">
            <form action="{{ route('reports.index') }}" method="GET" class="flex space-x-2">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Buscar reporte:</label>
                    <input type="text" name="search" id="search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Buscar por código o descripción" value="{{ request('search') }}">
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
                                @can('profile.update')
                                    <a href="{{ route('reports.edit', $report->id_report) }}"
                                       class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('profile.destroy')
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
                                        name="delete-modal-{{ $report->id_report }}">
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
                                            />
                                        </form>
                                    </x-bladewind::modal>

                                    <x-bladewind::modal
                                        icon="document-text"
                                        icon_css="bg-blue-500 text-white p-2.5 rounded-full"
                                        title="Generar PDF del Reporte"
                                        name="generate-pdf-modal-{{ $report->id_report }}"
                                        ok_button_label="Generar PDF"
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
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modales para las imágenes -->
        @foreach($reports as $report)
            <x-bladewind::modal
                name="image-report-{{ $report->id_report }}"
                title="Imagen del Reporte">
                <img src="{{ $report->image_report }}" alt="Imagen del Reporte">
            </x-bladewind::modal>

            <x-bladewind::modal
                name="signature-report-{{ $report->id_report }}"
                title="Firma del Técnico">
                <img src="{{ $report->signature_report }}" alt="Firma del Técnico">
            </x-bladewind::modal>

            <x-bladewind::modal
                name="image-before-{{ $report->id_report }}"
                title="Imagen Antes">
                <img src="{{ $report->image_before_report }}" alt="Imagen Antes">
            </x-bladewind::modal>

            <x-bladewind::modal
                name="signature-client-{{ $report->id_report }}"
                title="Firma del Cliente">
                <img src="{{ $report->signature_client_report }}" alt="Firma del Cliente">
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
