@extends('layouts.app')

@section('title', 'Historial Clínico - ' . $user->us_name . ' ' . $user->us_lastName)

@section('header', 'Historial Clínico de ' . $user->us_name . ' ' . $user->us_lastName)

@section('content')
    <div class="mb-6">
        {{-- Botón volver --}}
        <div class="flex items-center mb-6">
            <a href="{{ route('historial.index') }}"
               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al listado
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block mt-1">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4">
                <strong class="font-bold">¡Error!</strong>
                <span class="block mt-1">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Datos del paciente --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Datos del Paciente</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nombre</p>
                    <p class="font-semibold text-gray-900">{{ $user->us_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Apellido</p>
                    <p class="font-semibold text-gray-900">{{ $user->us_lastName }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Cédula</p>
                    <p class="font-semibold text-gray-900">{{ $user->us_dni }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900">{{ $user->us_email }}</p>
                </div>
            </div>
        </div>

        {{-- Listado de consultas --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                Consultas ({{ $user->consultas->count() }})
            </h3>

            @if($user->consultas->isEmpty())
                <p class="text-gray-500 text-center py-8">Este paciente no tiene consultas registradas.</p>
            @else
                <div class="space-y-6">
                    @foreach($user->consultas->sortByDesc('fecha_registro') as $consulta)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            {{-- Encabezado de la consulta --}}
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-bold text-blue-700">
                                            Consulta #{{ $consulta->id_co }}
                                        </span>
                                        <span class="ml-3 text-sm text-gray-500">
                                            {{ $consulta->fecha_registro ? $consulta->fecha_registro->format('d/m/Y H:i') : 'Sin fecha' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-semibold text-gray-700">
                                            Motivo: {{ $consulta->motivo }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            {{ $consulta->estado === 'F' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $consulta->estado === 'F' ? 'Finalizado' : 'Activo' }}
                                        </span>
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
                                                    Enviar Examanes al Correo
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Detalle del historial clínico --}}
                            @if($consulta->historialClinico)
                                <div class="px-6 py-4">
                                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-3">
                                        Historial Clínico
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Motivo de consulta</p>
                                            <p class="text-sm text-gray-900">{{ $consulta->historialClinico->motivo_consulta }}</p>
                                        </div>
                                        @if($consulta->historialClinico->antecedentes_patologicos_familiares)
                                            <div>
                                                <p class="text-sm text-gray-500">Ant. patológicos familiares</p>
                                                <p class="text-sm text-gray-900">{{ $consulta->historialClinico->antecedentes_patologicos_familiares }}</p>
                                            </div>
                                        @endif
                                        @if($consulta->historialClinico->antecedentes_patologicos_personales)
                                            <div>
                                                <p class="text-sm text-gray-500">Ant. patológicos personales</p>
                                                <p class="text-sm text-gray-900">{{ $consulta->historialClinico->antecedentes_patologicos_personales }}</p>
                                            </div>
                                        @endif
                                        @if($consulta->historialClinico->antecedentes_oculares_familiares)
                                            <div>
                                                <p class="text-sm text-gray-500">Ant. oculares familiares</p>
                                                <p class="text-sm text-gray-900">{{ $consulta->historialClinico->antecedentes_oculares_familiares }}</p>
                                            </div>
                                        @endif
                                        @if($consulta->historialClinico->antecedentes_oculares_personales)
                                            <div>
                                                <p class="text-sm text-gray-500">Ant. oculares personales</p>
                                                <p class="text-sm text-gray-900">{{ $consulta->historialClinico->antecedentes_oculares_personales }}</p>
                                            </div>
                                        @endif
                                        @if($consulta->historialClinico->utiliza_lentes)
                                            <div>
                                                <p class="text-sm text-gray-500">Utiliza lentes</p>
                                                <p class="text-sm text-gray-900">
                                                    Sí
                                                    @if($consulta->historialClinico->tipo_lente)
                                                        — {{ $consulta->historialClinico->tipo_lente }}
                                                    @endif
                                                    @if($consulta->historialClinico->fecha_inicio_uso_lentes)
                                                        (desde {{ $consulta->historialClinico->fecha_inicio_uso_lentes->format('d/m/Y') }})
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                        @if($consulta->historialClinico->observaciones)
                                            <div class="col-span-2">
                                                <p class="text-sm text-gray-500">Observaciones</p>
                                                <p class="text-sm text-gray-900">{{ $consulta->historialClinico->observaciones }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Exámenes asociados --}}
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Exámenes asociados</p>
                                        <div class="flex flex-wrap gap-2">
                                            @if($consulta->historialClinico->contactologia)
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Contactología</span>
                                            @endif
                                            @if($consulta->historialClinico->evaluacionOftalmologica)
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Evaluación Oftalmológica</span>
                                            @endif
                                            @if($consulta->historialClinico->examenOptometrico)
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Examen Optométrico</span>
                                            @endif
                                            @if($consulta->historialClinico->examenesPreliminares)
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Exámenes Preliminares</span>
                                            @endif
                                            @if($consulta->historialClinico->lensometria)
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lensometría</span>
                                            @endif
                                            @if($consulta->historialClinico->rxFinalLente)
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">RX Final Lente</span>
                                            @endif
                                            @if(!$consulta->historialClinico->contactologia && !$consulta->historialClinico->evaluacionOftalmologica && !$consulta->historialClinico->examenOptometrico && !$consulta->historialClinico->examenesPreliminares && !$consulta->historialClinico->lensometria && !$consulta->historialClinico->rxFinalLente)
                                                <span class="text-xs text-gray-400">Sin exámenes registrados</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="px-6 py-4">
                                    <p class="text-sm text-gray-500 text-center">Esta consulta no tiene historial clínico asociado.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
