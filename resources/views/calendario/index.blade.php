@extends('layouts.app')

@section('title', 'Gestión de Calendario')

@section('header', 'Gestión de Calendario')

@section('content')
    <div x-data="{
        activeTab: 'categories',
        isModalOpen: false,
    }" class="mb-6">
        <div class="border-b border-blue-200">
            <nav class="-mb-px flex">
                <a href="{{ route('products.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Calendario
                </a>
                <a href="{{ route('employees.index') }}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Ordenes de Trabajo
                </a>
                <a @click.prevent="activeTab = 'categories'" :class="{'border-blue-500 text-blue-800': activeTab === 'categories'}" class="cursor-pointer border-b-2 border-transparent py-4 px-6 inline-block font-medium text-sm leading-5 text-blue-600 hover:text-blue-800 hover:border-blue-300 focus:outline-none focus:text-blue-800 focus:border-blue-300">
                    Reporte de Técnicos
                </a>
            </nav>
        </div>
        <div class="container mx-auto px-4 py-8">

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
