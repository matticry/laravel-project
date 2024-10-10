@extends('layouts.app')

@section('title', 'Gestión de Calendario')

@section('header', 'Gestión de Calendario')

@section('content')
    <div x-data="{ isModalOpen: false }" class="container mx-auto px-4 py-8">
        <div class="flex justify-end mb-4">
            <button @click="isModalOpen = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                CREAR ORDEN DE TRABAJO
            </button>
        </div>
        <!-- Modal -->
        <div x-show="isModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Crear Nueva Orden de Trabajo
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Aquí puedes agregar el formulario para crear una nueva orden de trabajo.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button @click="isModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-between mb-6 text-sm">
            <div class="flex items-center bg-blue-100 rounded-lg px-4 py-2">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-semibold text-gray-700">Ordenes de trabajo: 1247</span>
            </div>
            <div class="flex items-center bg-purple-100 rounded-lg px-4 py-2">
                <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-gray-700">Ordenes pendientes: 123</span>
            </div>
            <div class="flex items-center bg-green-50 rounded-lg px-4 py-2">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-gray-700">Active Posts: 4</span>
            </div>
            <div class="flex items-center bg-yellow-50 rounded-lg px-4 py-2">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-semibold text-gray-700">Scheduled Posts: 2</span>
            </div>
        </div>
        <div id="calendar" class="bg-blue-100 rounded-lg shadow-lg overflow-hidden"></div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
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
                events: [
                    {
                        title: 'Meet with Anne',
                        start: '2024-10-09T09:00:00',
                        end: '2024-10-09T09:30:00',
                        backgroundColor: '#E6E6FA',
                        borderColor: '#D8BFD8',
                        textColor: '#000000'
                    },
                    {
                        title: 'Daily Sync',
                        start: '2024-10-09T11:00:00',
                        end: '2024-10-09T11:30:00',
                        backgroundColor: '#E0FFFF',
                        borderColor: '#B0E0E6',
                        textColor: '#000000'
                    },
                    {
                        title: 'Discuss Animal Project',
                        start: '2024-10-09T12:00:00',
                        end: '2024-10-09T12:30:00',
                        backgroundColor: '#E0FFFF',
                        borderColor: '#B0E0E6',
                        textColor: '#000000'
                    }
                ],
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
@endsection
