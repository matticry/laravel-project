@extends('layouts.app')

@section('title', 'Historial Clínico')

@section('header', 'Historial Clínico')

@section('content')
    <div x-data="{ activeModal: null, pacienteData: {}, consultasData: [] }" class="mb-6">

        {{-- Alerta de éxito --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show"
                 class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md relative mb-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <strong class="font-bold">¡Éxito!</strong>
                </div>
                <span class="block mt-2">{{ session('success') }}</span>
                <div class="mt-3">
                    <button @click="show = false"
                            class="text-green-700 hover:bg-green-200 px-2 py-1 rounded transition duration-300">Cerrar
                    </button>
                </div>
            </div>
        @endif

        {{-- Búsqueda --}}
        <form action="{{ route('historial.index') }}" method="GET" class="flex flex-wrap gap-3 mb-6">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                <input type="text" name="nombre" id="nombre"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Filtrar por nombre" value="{{ request()->get('nombre') }}">
            </div>
            <div>
                <label for="dni" class="block text-sm font-medium text-gray-700">Cédula:</label>
                <input type="text" name="dni" id="dni"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Filtrar por cédula" value="{{ request()->get('dni') }}">
            </div>
            <div class="self-end">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Buscar
                </button>
            </div>
        </form>

        {{-- Tabla de pacientes --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                <tr class="text-left">
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        ID
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Nombre
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Apellido
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Cédula
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Email
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Consultas
                    </th>
                    <th class="bg-blue-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-blue-600 font-bold tracking-wider uppercase text-xs">
                        Acciones
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_id }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_name }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_lastName }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_dni }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $user->us_email }}</td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $user->consultas->count() }} consulta(s)
                            </span>
                        </td>
                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                            <div class="flex space-x-2">
                                {{-- Ver historial --}}
                                <a href="{{ route('historial.show', $user->us_id) }}"
                                   class="bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-full p-2"
                                   title="Ver historial clínico">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Ver consultas / editar --}}
                                @if($user->consultas->isNotEmpty())
                                    <button type="button"
                                            class="bg-green-100 text-green-600 hover:bg-green-200 rounded-full p-2 cursor-pointer"
                                            title="Ver y editar consultas"
                                            onclick="showModal('consultas-modal-{{ $user->us_id }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                @endif

                            </div>
                        </td>

                        {{-- Modal de consultas por paciente --}}
                        <td class="hidden">
                            <x-bladewind::modal
                                title="Consultas de {{ $user->us_name }} {{ $user->us_lastName }}"
                                ok_button_label=""
                                cancel_button_label=""
                                name="consultas-modal-{{ $user->us_id }}">

                                @if($user->consultas->isEmpty())
                                    <p class="text-gray-500 text-center py-4">Este paciente no tiene consultas registradas.</p>
                                @else
                                    <div class="space-y-4 max-h-96 overflow-y-auto">
                                        @foreach($user->consultas->sortByDesc('fecha_registro') as $consulta)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-sm font-semibold text-blue-700">
                                                            Consulta #{{ $consulta->id_co }}
                                                        </span>
                                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                                            {{ $consulta->estado === 'F' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                            {{ $consulta->estado === 'F' ? 'Finalizado' : 'Activo' }}
                                                        </span>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $consulta->fecha_registro ? $consulta->fecha_registro->format('d/m/Y H:i') : 'Sin fecha' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ route('historial.edit', $consulta->id_co) }}"
                                                           class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-3 rounded">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Editar
                                                        </a>
                                                        @if($consulta->estado === 'F')
                                                            <form action="{{ route('historial.send-email', $consulta->id_co) }}" method="POST" class="inline"
                                                                  onsubmit="return confirm('¿Enviar historial clínico a {{ $consulta->paciente->us_email }}?')">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="inline-flex items-center bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-1.5 px-3 rounded">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    Enviar PDF
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="text-sm text-gray-700 mb-2">
                                                    <span class="font-semibold">Motivo:</span> {{ $consulta->motivo }}
                                                </p>
                                                @if($consulta->historialClinico)
                                                    <div class="bg-gray-50 rounded p-3 mt-2">
                                                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Historial Clínico</p>
                                                        <p class="text-sm text-gray-700">
                                                            <span class="font-semibold">Motivo consulta:</span>
                                                            {{ $consulta->historialClinico->motivo_consulta }}
                                                        </p>
                                                        @if($consulta->historialClinico->antecedentes_patologicos_familiares)
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-semibold">Ant. pat. familiares:</span>
                                                                {{ $consulta->historialClinico->antecedentes_patologicos_familiares }}
                                                            </p>
                                                        @endif
                                                        @if($consulta->historialClinico->antecedentes_patologicos_personales)
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-semibold">Ant. pat. personales:</span>
                                                                {{ $consulta->historialClinico->antecedentes_patologicos_personales }}
                                                            </p>
                                                        @endif
                                                        @if($consulta->historialClinico->antecedentes_oculares_familiares)
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-semibold">Ant. oculares familiares:</span>
                                                                {{ $consulta->historialClinico->antecedentes_oculares_familiares }}
                                                            </p>
                                                        @endif
                                                        @if($consulta->historialClinico->antecedentes_oculares_personales)
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-semibold">Ant. oculares personales:</span>
                                                                {{ $consulta->historialClinico->antecedentes_oculares_personales }}
                                                            </p>
                                                        @endif
                                                        @if($consulta->historialClinico->utiliza_lentes)
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-semibold">Utiliza lentes:</span> Sí
                                                                @if($consulta->historialClinico->tipo_lente)
                                                                    ({{ $consulta->historialClinico->tipo_lente }})
                                                                @endif
                                                            </p>
                                                        @endif
                                                        @if($consulta->historialClinico->observaciones)
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-semibold">Observaciones:</span>
                                                                {{ $consulta->historialClinico->observaciones }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </x-bladewind::modal>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="border-dashed border-t border-gray-200 px-6 py-4 text-center text-gray-500">
                            No hay pacientes con el rol "Usuario P" registrados.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function hideModal(modalName) {
            Bladewind.closeModal(modalName);
        }
    </script>
@endsection
