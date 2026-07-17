@extends('layouts.app')

@section('title', 'Editar Historial Clínico')

@section('header', 'Editar Historial Clínico — ' . $consulta->paciente->us_name . ' ' . $consulta->paciente->us_lastName)

@section('content')
    <div x-data="{ activeTab: 'historial' }" class="mb-6">

        {{-- Volver --}}
        <a href="{{ route('historial.show', $consulta->id_paciente) }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al historial
        </a>

        {{-- Alerta de éxito --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block mt-1">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4">
                <strong class="font-bold">¡Error!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('historial.update', $consulta->id_co) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Tabs --}}
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex flex-wrap">
                    <button type="button" @click="activeTab = 'historial'"
                            :class="activeTab === 'historial' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        Historial Clínico
                    </button>
                    <button type="button" @click="activeTab = 'lensometria'"
                            :class="activeTab === 'lensometria' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        Lensometría
                    </button>
                    <button type="button" @click="activeTab = 'examen_optometrico'"
                            :class="activeTab === 'examen_optometrico' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        Examen Optométrico
                    </button>
                    <button type="button" @click="activeTab = 'contactologia'"
                            :class="activeTab === 'contactologia' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        Contactología
                    </button>
                    <button type="button" @click="activeTab = 'evaluacion_oftalmologica'"
                            :class="activeTab === 'evaluacion_oftalmologica' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        Eval. Oftalmológica
                    </button>
                    <button type="button" @click="activeTab = 'examenes_preliminares'"
                            :class="activeTab === 'examenes_preliminares' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        Exámenes Preliminares
                    </button>
                    <button type="button" @click="activeTab = 'rx_final_lente'"
                            :class="activeTab === 'rx_final_lente' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="border-b-2 py-3 px-5 text-sm font-medium transition">
                        RX Final Lente
                    </button>
                </nav>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: Historial Clínico --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'historial'" class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Datos del Historial Clínico</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de consulta</label>
                        <input type="text" name="motivo_consulta"
                               value="{{ optional($consulta->historialClinico)->motivo_consulta }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ant. patológicos familiares</label>
                        <textarea name="antecedentes_patologicos_familiares" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ optional($consulta->historialClinico)->antecedentes_patologicos_familiares }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ant. patológicos personales</label>
                        <textarea name="antecedentes_patologicos_personales" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ optional($consulta->historialClinico)->antecedentes_patologicos_personales }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ant. oculares familiares</label>
                        <textarea name="antecedentes_oculares_familiares" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ optional($consulta->historialClinico)->antecedentes_oculares_familiares }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ant. oculares personales</label>
                        <textarea name="antecedentes_oculares_personales" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ optional($consulta->historialClinico)->antecedentes_oculares_personales }}</textarea>
                    </div>
                    <div class="flex items-center space-x-3">
                        <input type="hidden" name="utiliza_lentes" value="0">
                        <input type="checkbox" name="utiliza_lentes" value="1"
                               {{ optional($consulta->historialClinico)->utiliza_lentes ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 rounded">
                        <label class="text-sm font-medium text-gray-700">Utiliza lentes</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de lente</label>
                        <input type="text" name="tipo_lente"
                               value="{{ optional($consulta->historialClinico)->tipo_lente }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio uso lentes</label>
                        <input type="date" name="fecha_inicio_uso_lentes"
                               value="{{ optional($consulta->historialClinico)->fecha_inicio_uso_lentes?->format('Y-m-d') }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="observaciones" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ optional($consulta->historialClinico)->observaciones }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: Lensometría --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'lensometria'" class="bg-white rounded-lg shadow p-6">
                @php $lens = optional($consulta->historialClinico)->lensometria; @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">Lensometría</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha examen</label>
                        <input type="date" name="lensometria[fecha_examen]"
                               value="{{ $lens?->fecha_examen?->format('Y-m-d') }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                </div>
                <div class="mt-4">
                    <h4 class="text-sm font-bold text-blue-600 uppercase mb-2">OD (Ojo Derecho)</h4>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        @foreach(['od_esf'=>'ESF','od_cyl'=>'CYL','od_eje'=>'EJE','od_add'=>'ADD','od_prisma'=>'Prisma','od_dnp'=>'DNP','od_dp'=>'DP','od_alt'=>'ALT'] as $key => $label)
                            <div>
                                <label class="block text-xs text-gray-500">{{ $label }}</label>
                                <input type="text" name="lensometria[{{ $key }}]" value="{{ $lens->{$key} ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4">
                    <h4 class="text-sm font-bold text-blue-600 uppercase mb-2">OI (Ojo Izquierdo)</h4>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        @foreach(['oi_esf'=>'ESF','oi_cyl'=>'CYL','oi_eje'=>'EJE','oi_add'=>'ADD','oi_prisma'=>'Prisma','oi_dnp'=>'DNP','oi_dp'=>'DP','oi_alt'=>'ALT'] as $key => $label)
                            <div>
                                <label class="block text-xs text-gray-500">{{ $label }}</label>
                                <input type="text" name="lensometria[{{ $key }}]" value="{{ $lens->{$key} ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diseño lente</label>
                        <input type="text" name="lensometria[diseno_lente]" value="{{ $lens->diseno_lente ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                        <input type="text" name="lensometria[material]" value="{{ $lens->material ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento</label>
                        <input type="text" name="lensometria[tratamiento]" value="{{ $lens->tratamiento ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: Examen Optométrico --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'examen_optometrico'" class="bg-white rounded-lg shadow p-6">
                @php $eo = optional($consulta->historialClinico)->examenOptometrico; @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">Examen Optométrico</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha examen</label>
                        <input type="date" name="examen_optometrico[fecha_examen]"
                               value="{{ $eo?->fecha_examen?->format('Y-m-d') }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([
                        'queratometria_od'=>'Queratometría OD','queratometria_oi'=>'Queratometría OI',
                        'retinoscopia_od'=>'Retinoscopía OD','retinoscopia_oi'=>'Retinoscopía OI',
                        'subjetivo_od'=>'Subjetivo OD','subjetivo_oi'=>'Subjetivo OI',
                        'refraccion_comp_od'=>'Refracción comp. OD','refraccion_comp_oi'=>'Refracción comp. OI',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="text" name="examen_optometrico[{{ $key }}]" value="{{ $eo->{$key} ?? '' }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    @endforeach
                    @foreach([
                        'percepcion_simultanea'=>'Percepción simultánea','fusion'=>'Fusión',
                        'estereopsis'=>'Estereopsis','punto_proximo_conv'=>'Punto próximo conv.',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="text" name="examen_optometrico[{{ $key }}]" value="{{ $eo->{$key} ?? '' }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    @endforeach
                    @foreach([
                        'cover_test_od'=>'Cover Test OD','cover_test_oi'=>'Cover Test OI',
                        'vision_colores_od'=>'Visión colores OD','vision_colores_oi'=>'Visión colores OI',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="text" name="examen_optometrico[{{ $key }}]" value="{{ $eo->{$key} ?? '' }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    @endforeach
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Otros</label>
                        <input type="text" name="examen_optometrico[otros]" value="{{ $eo->otros ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                        <textarea name="examen_optometrico[observacion]" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ $eo->observacion ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="examen_optometrico[notas]" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ $eo->notas ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: Contactología --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'contactologia'" class="bg-white rounded-lg shadow p-6">
                @php $cont = optional($consulta->historialClinico)->contactologia; @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contactología</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha examen</label>
                    <input type="date" name="contactologia[fecha_examen]"
                           value="{{ $cont?->fecha_examen?->format('Y-m-d') }}"
                           class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- OD --}}
                    <div>
                        <h4 class="text-sm font-bold text-blue-600 uppercase mb-2">OD (Ojo Derecho)</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['od_esf'=>'ESF','od_cyl'=>'CYL','od_eje'=>'EJE','od_diametro'=>'Diámetro','od_curva_base'=>'Curva Base'] as $key => $label)
                                <div>
                                    <label class="block text-xs text-gray-500">{{ $label }}</label>
                                    <input type="text" name="contactologia[{{ $key }}]" value="{{ $cont->{$key} ?? '' }}"
                                           class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                                </div>
                            @endforeach
                            <div>
                                <label class="block text-xs text-gray-500">AV</label>
                                <input type="text" name="contactologia[od_av]" value="{{ $cont->od_av ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>
                    {{-- OI --}}
                    <div>
                        <h4 class="text-sm font-bold text-blue-600 uppercase mb-2">OI (Ojo Izquierdo)</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['oi_esf'=>'ESF','oi_cyl'=>'CYL','oi_eje'=>'EJE','oi_diametro'=>'Diámetro','oi_curva_base'=>'Curva Base'] as $key => $label)
                                <div>
                                    <label class="block text-xs text-gray-500">{{ $label }}</label>
                                    <input type="text" name="contactologia[{{ $key }}]" value="{{ $cont->{$key} ?? '' }}"
                                           class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                                </div>
                            @endforeach
                            <div>
                                <label class="block text-xs text-gray-500">AV</label>
                                <input type="text" name="contactologia[oi_av]" value="{{ $cont->oi_av ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AVCC AO Lejos</label>
                        <input type="text" name="contactologia[avcc_ao_lejos]" value="{{ $cont->avcc_ao_lejos ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AVCC AO Cerca</label>
                        <input type="text" name="contactologia[avcc_ao_cerca]" value="{{ $cont->avcc_ao_cerca ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo lente</label>
                        <input type="text" name="contactologia[tipo_lente]" value="{{ $cont->tipo_lente ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                        <input type="text" name="contactologia[observacion]" value="{{ $cont->observacion ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: Evaluación Oftalmológica --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'evaluacion_oftalmologica'" class="bg-white rounded-lg shadow p-6">
                @php $eof = optional($consulta->historialClinico)->evaluacionOftalmologica; @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">Evaluación Oftalmológica</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha examen</label>
                    <input type="date" name="evaluacion_oftalmologica[fecha_examen]"
                           value="{{ $eof?->fecha_examen?->format('Y-m-d') }}"
                           class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([
                        'biomicroscopia_od'=>'Biomicroscopía OD','biomicroscopia_oi'=>'Biomicroscopía OI',
                        'tension_od'=>'Tensión OD','tension_oi'=>'Tensión OI',
                        'pupilas_od'=>'Pupilas OD','pupilas_oi'=>'Pupilas OI',
                        'oftalmoscopia_od'=>'Oftalmoscopía OD','oftalmoscopia_oi'=>'Oftalmoscopía OI',
                        'schirmer_od'=>'Schirmer OD','schirmer_oi'=>'Schirmer OI',
                        'amsler_od'=>'Amsler OD','amsler_oi'=>'Amsler OI',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="text" name="evaluacion_oftalmologica[{{ $key }}]" value="{{ $eof->{$key} ?? '' }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    @endforeach
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Otros</label>
                        <input type="text" name="evaluacion_oftalmologica[otros]" value="{{ $eof->otros ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="evaluacion_oftalmologica[observaciones]" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ $eof->observaciones ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: Exámenes Preliminares --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'examenes_preliminares'" class="bg-white rounded-lg shadow p-6">
                @php $ep = optional($consulta->historialClinico)->examenesPreliminares; @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">Exámenes Preliminares</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha examen</label>
                    <input type="date" name="examenes_preliminares[fecha_examen]"
                           value="{{ $ep?->fecha_examen?->format('Y-m-d') }}"
                           class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([
                        'observacion_od'=>'Observación OD','observacion_oi'=>'Observación OI',
                        'av_od'=>'AV OD','av_oi'=>'AV OI','av_ao'=>'AV AO',
                        'av_estenop_od'=>'AV Estenop. OD','av_estenop_oi'=>'AV Estenop. OI',
                        'motilidad_1'=>'Motilidad 1','hirschberg_1'=>'Hirschberg 1',
                        'purkinje'=>'Purkinje',
                        'ccv_od'=>'CCV OD','ccv_oi'=>'CCV OI',
                        'motilidad_2'=>'Motilidad 2','hirschberg_2'=>'Hirschberg 2',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                            <input type="text" name="examenes_preliminares[{{ $key }}]" value="{{ $ep->{$key} ?? '' }}"
                                   class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TAB: RX Final Lente --}}
            {{-- ============================================================ --}}
            <div x-show="activeTab === 'rx_final_lente'" class="bg-white rounded-lg shadow p-6">
                @php $rx = optional($consulta->historialClinico)->rxFinalLente; @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">RX Final Lente</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha examen</label>
                    <input type="date" name="rx_final_lente[fecha_examen]"
                           value="{{ $rx?->fecha_examen?->format('Y-m-d') }}"
                           class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- OD --}}
                    <div>
                        <h4 class="text-sm font-bold text-blue-600 uppercase mb-2">OD (Ojo Derecho)</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['od_esf'=>'ESF','od_cyl'=>'CYL','od_eje'=>'EJE','od_add'=>'ADD','od_prisma'=>'Prisma','od_dnp'=>'DNP','od_dp'=>'DP','od_alt'=>'ALT'] as $key => $label)
                                <div>
                                    <label class="block text-xs text-gray-500">{{ $label }}</label>
                                    <input type="text" name="rx_final_lente[{{ $key }}]" value="{{ $rx->{$key} ?? '' }}"
                                           class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                                </div>
                            @endforeach
                            <div>
                                <label class="block text-xs text-gray-500">AV Lejos</label>
                                <input type="text" name="rx_final_lente[od_av_lejos]" value="{{ $rx->od_av_lejos ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500">AV Cerca</label>
                                <input type="text" name="rx_final_lente[od_av_cerca]" value="{{ $rx->od_av_cerca ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>
                    {{-- OI --}}
                    <div>
                        <h4 class="text-sm font-bold text-blue-600 uppercase mb-2">OI (Ojo Izquierdo)</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['oi_esf'=>'ESF','oi_cyl'=>'CYL','oi_eje'=>'EJE','oi_add'=>'ADD','oi_prisma'=>'Prisma','oi_dnp'=>'DNP','oi_dp'=>'DP','oi_alt'=>'ALT'] as $key => $label)
                                <div>
                                    <label class="block text-xs text-gray-500">{{ $label }}</label>
                                    <input type="text" name="rx_final_lente[{{ $key }}]" value="{{ $rx->{$key} ?? '' }}"
                                           class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                                </div>
                            @endforeach
                            <div>
                                <label class="block text-xs text-gray-500">AV Lejos</label>
                                <input type="text" name="rx_final_lente[oi_av_lejos]" value="{{ $rx->oi_av_lejos ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500">AV Cerca</label>
                                <input type="text" name="rx_final_lente[oi_av_cerca]" value="{{ $rx->oi_av_cerca ?? '' }}"
                                       class="shadow border rounded w-full py-2 px-2 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AVCC AO Lejos</label>
                        <input type="text" name="rx_final_lente[avcc_ao_lejos]" value="{{ $rx->avcc_ao_lejos ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AVCC AO Cerca</label>
                        <input type="text" name="rx_final_lente[avcc_ao_cerca]" value="{{ $rx->avcc_ao_cerca ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diseño lente</label>
                        <input type="text" name="rx_final_lente[diseno_lente]" value="{{ $rx->diseno_lente ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento</label>
                        <input type="text" name="rx_final_lente[tratamiento]" value="{{ $rx->tratamiento ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                        <input type="text" name="rx_final_lente[material]" value="{{ $rx->material ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                        <input type="text" name="rx_final_lente[diagnostico]" value="{{ $rx->diagnostico ?? '' }}"
                               class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recomendaciones</label>
                        <textarea name="rx_final_lente[recomendaciones]" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ $rx->recomendaciones ?? '' }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="rx_final_lente[observaciones]" rows="2"
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ $rx->observaciones ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end space-x-3 mt-6">
                <a href="{{ route('historial.show', $consulta->id_paciente) }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
