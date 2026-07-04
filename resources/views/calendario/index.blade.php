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
                        Historial de Consultas
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
            // Validación de teléfono
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

            // Validación de dirección
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

            // Actualizar imagen del cliente
            const clientImage = document.getElementById('client-image');
            if (data.client.cli_image) {
                clientImage.src = data.client.cli_image;
            } else {
                clientImage.src = generateAvatarUrl(data.client.cli_name);
            }

            clientImage.onerror = function() {
                this.src = generateAvatarUrl(data.client.cli_name);
            }

            // Actualizar resto de datos
            document.getElementById('wo-code').textContent = data.wo_order_code;
            document.getElementById('wo-client').textContent = data.client.cli_name;
            // QUITAR ESTAS DOS LÍNEAS que están sobrescribiendo los valores:
            // document.getElementById('wo-client-phone').textContent = data.client.cli_phone;
            // document.getElementById('wo-client-address').textContent = data.client.cli_address;
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
