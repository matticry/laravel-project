<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico — {{ $consulta->paciente->us_name }} {{ $consulta->paciente->us_lastName }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1 { font-size: 18px; color: #1a5276; border-bottom: 2px solid #2980b9; padding-bottom: 6px; margin-bottom: 10px; }
        h2 { font-size: 14px; color: #2980b9; margin-top: 18px; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        h3 { font-size: 12px; color: #555; margin-top: 12px; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px 8px; text-align: left; font-size: 11px; }
        th { background-color: #eaf2f8; color: #2c3e50; font-weight: bold; }
        .info-row { margin-bottom: 4px; }
        .info-row strong { display: inline-block; width: 180px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-active { background: #d4efdf; color: #1e8449; }
        .badge-finalizado { background: #d6eaf8; color: #1a5276; }
        .section-box { border: 1px solid #ddd; border-radius: 4px; padding: 10px; margin-bottom: 12px; background: #fafafa; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>

    <h1>Historial Clínico</h1>

    {{-- Datos del paciente --}}
    <div class="section-box">
        <h2>Datos del Paciente</h2>
        <div class="info-row"><strong>Nombre:</strong> {{ $consulta->paciente->us_name }} {{ $consulta->paciente->us_lastName }}</div>
        <div class="info-row"><strong>Cédula:</strong> {{ $consulta->paciente->us_dni }}</div>
        <div class="info-row"><strong>Email:</strong> {{ $consulta->paciente->us_email }}</div>
    </div>

    {{-- Datos de la consulta --}}
    <div class="section-box">
        <h2>Consulta #{{ $consulta->id_co }}</h2>
        <div class="info-row"><strong>Fecha:</strong> {{ $consulta->fecha_registro ? $consulta->fecha_registro->format('d/m/Y H:i') : 'Sin fecha' }}</div>
        <div class="info-row"><strong>Motivo:</strong> {{ $consulta->motivo }}</div>
        <div class="info-row">
            <strong>Estado:</strong>
            @if($consulta->estado === 'F')
                <span class="badge badge-finalizado">Finalizado</span>
            @else
                <span class="badge badge-active">Activo</span>
            @endif
        </div>
    </div>

    {{-- Historial clínico --}}
    @if($consulta->historialClinico)
        @php $h = $consulta->historialClinico; @endphp
        <div class="section-box">
            <h2>Historial Clínico</h2>
            <div class="info-row"><strong>Motivo de consulta:</strong> {{ $h->motivo_consulta }}</div>
            @if($h->antecedentes_patologicos_familiares)
                <div class="info-row"><strong>Ant. patológicos familiares:</strong> {{ $h->antecedentes_patologicos_familiares }}</div>
            @endif
            @if($h->antecedentes_patologicos_personales)
                <div class="info-row"><strong>Ant. patológicos personales:</strong> {{ $h->antecedentes_patologicos_personales }}</div>
            @endif
            @if($h->antecedentes_oculares_familiares)
                <div class="info-row"><strong>Ant. oculares familiares:</strong> {{ $h->antecedentes_oculares_familiares }}</div>
            @endif
            @if($h->antecedentes_oculares_personales)
                <div class="info-row"><strong>Ant. oculares personales:</strong> {{ $h->antecedentes_oculares_personales }}</div>
            @endif
            @if($h->utiliza_lentes)
                <div class="info-row"><strong>Utiliza lentes:</strong> Sí {{ $h->tipo_lente ? '('.$h->tipo_lente.')' : '' }} {{ $h->fecha_inicio_uso_lentes ? 'desde '.$h->fecha_inicio_uso_lentes->format('d/m/Y') : '' }}</div>
            @endif
            @if($h->observaciones)
                <div class="info-row"><strong>Observaciones:</strong> {{ $h->observaciones }}</div>
            @endif
        </div>

        {{-- Lensometría --}}
        @if($h->lensometria)
            @php $l = $h->lensometria; @endphp
            <div class="section-box">
                <h2>Lensometría</h2>
                @if($l->fecha_examen)
                    <div class="info-row"><strong>Fecha:</strong> {{ $l->fecha_examen->format('d/m/Y') }}</div>
                @endif
                <h3>OD (Ojo Derecho)</h3>
                <table>
                    <tr><th>ESF</th><th>CYL</th><th>EJE</th><th>ADD</th><th>Prisma</th><th>DNP</th><th>DP</th><th>ALT</th></tr>
                    <tr>
                        <td>{{ $l->od_esf }}</td><td>{{ $l->od_cyl }}</td><td>{{ $l->od_eje }}</td><td>{{ $l->od_add }}</td>
                        <td>{{ $l->od_prisma }}</td><td>{{ $l->od_dnp }}</td><td>{{ $l->od_dp }}</td><td>{{ $l->od_alt }}</td>
                    </tr>
                </table>
                <h3>OI (Ojo Izquierdo)</h3>
                <table>
                    <tr><th>ESF</th><th>CYL</th><th>EJE</th><th>ADD</th><th>Prisma</th><th>DNP</th><th>DP</th><th>ALT</th></tr>
                    <tr>
                        <td>{{ $l->oi_esf }}</td><td>{{ $l->oi_cyl }}</td><td>{{ $l->oi_eje }}</td><td>{{ $l->oi_add }}</td>
                        <td>{{ $l->oi_prisma }}</td><td>{{ $l->oi_dnp }}</td><td>{{ $l->oi_dp }}</td><td>{{ $l->oi_alt }}</td>
                    </tr>
                </table>
                @if($l->diseno_lente || $l->material || $l->tratamiento)
                    <div class="info-row"><strong>Diseño:</strong> {{ $l->diseno_lente }} | <strong>Material:</strong> {{ $l->material }} | <strong>Tratamiento:</strong> {{ $l->tratamiento }}</div>
                @endif
            </div>
        @endif

        {{-- Examen Optométrico --}}
        @if($h->examenOptometrico)
            @php $eo = $h->examenOptometrico; @endphp
            <div class="section-box">
                <h2>Examen Optométrico</h2>
                @if($eo->fecha_examen)
                    <div class="info-row"><strong>Fecha:</strong> {{ $eo->fecha_examen->format('d/m/Y') }}</div>
                @endif
                <table>
                    <tr><th>Campo</th><th>OD</th><th>OI</th></tr>
                    <tr><td>Queratometría</td><td>{{ $eo->queratometria_od }}</td><td>{{ $eo->queratometria_oi }}</td></tr>
                    <tr><td>Retinoscopía</td><td>{{ $eo->retinoscopia_od }}</td><td>{{ $eo->retinoscopia_oi }}</td></tr>
                    <tr><td>Subjetivo</td><td>{{ $eo->subjetivo_od }}</td><td>{{ $eo->subjetivo_oi }}</td></tr>
                    <tr><td>Refracción comp.</td><td>{{ $eo->refraccion_comp_od }}</td><td>{{ $eo->refraccion_comp_oi }}</td></tr>
                    <tr><td>Cover Test</td><td>{{ $eo->cover_test_od }}</td><td>{{ $eo->cover_test_oi }}</td></tr>
                    <tr><td>Visión colores</td><td>{{ $eo->vision_colores_od }}</td><td>{{ $eo->vision_colores_oi }}</td></tr>
                </table>
                <div class="info-row"><strong>Percepción simultánea:</strong> {{ $eo->percepcion_simultanea }}</div>
                <div class="info-row"><strong>Fusión:</strong> {{ $eo->fusion }}</div>
                <div class="info-row"><strong>Estereopsis:</strong> {{ $eo->estereopsis }}</div>
                <div class="info-row"><strong>Punto próximo conv.:</strong> {{ $eo->punto_proximo_conv }}</div>
                @if($eo->otros)
                    <div class="info-row"><strong>Otros:</strong> {{ $eo->otros }}</div>
                @endif
                @if($eo->observacion)
                    <div class="info-row"><strong>Observación:</strong> {{ $eo->observacion }}</div>
                @endif
                @if($eo->notas)
                    <div class="info-row"><strong>Notas:</strong> {{ $eo->notas }}</div>
                @endif
            </div>
        @endif

        {{-- Contactología --}}
        @if($h->contactologia)
            @php $c = $h->contactologia; @endphp
            <div class="section-box">
                <h2>Contactología</h2>
                @if($c->fecha_examen)
                    <div class="info-row"><strong>Fecha:</strong> {{ $c->fecha_examen->format('d/m/Y') }}</div>
                @endif
                <table>
                    <tr><th>Campo</th><th>OD</th><th>OI</th></tr>
                    <tr><td>ESF</td><td>{{ $c->od_esf }}</td><td>{{ $c->oi_esf }}</td></tr>
                    <tr><td>CYL</td><td>{{ $c->od_cyl }}</td><td>{{ $c->oi_cyl }}</td></tr>
                    <tr><td>EJE</td><td>{{ $c->od_eje }}</td><td>{{ $c->oi_eje }}</td></tr>
                    <tr><td>Diámetro</td><td>{{ $c->od_diametro }}</td><td>{{ $c->oi_diametro }}</td></tr>
                    <tr><td>Curva Base</td><td>{{ $c->od_curva_base }}</td><td>{{ $c->oi_curva_base }}</td></tr>
                    <tr><td>AV</td><td>{{ $c->od_av }}</td><td>{{ $c->oi_av }}</td></tr>
                </table>
                <div class="info-row"><strong>AVCC AO Lejos:</strong> {{ $c->avcc_ao_lejos }}</div>
                <div class="info-row"><strong>AVCC AO Cerca:</strong> {{ $c->avcc_ao_cerca }}</div>
                <div class="info-row"><strong>Tipo lente:</strong> {{ $c->tipo_lente }}</div>
                @if($c->observacion)
                    <div class="info-row"><strong>Observación:</strong> {{ $c->observacion }}</div>
                @endif
            </div>
        @endif

        {{-- Evaluación Oftalmológica --}}
        @if($h->evaluacionOftalmologica)
            @php $eof = $h->evaluacionOftalmologica; @endphp
            <div class="section-box">
                <h2>Evaluación Oftalmológica</h2>
                @if($eof->fecha_examen)
                    <div class="info-row"><strong>Fecha:</strong> {{ $eof->fecha_examen->format('d/m/Y') }}</div>
                @endif
                <table>
                    <tr><th>Campo</th><th>OD</th><th>OI</th></tr>
                    <tr><td>Biomicroscopía</td><td>{{ $eof->biomicroscopia_od }}</td><td>{{ $eof->biomicroscopia_oi }}</td></tr>
                    <tr><td>Tensión</td><td>{{ $eof->tension_od }}</td><td>{{ $eof->tension_oi }}</td></tr>
                    <tr><td>Pupilas</td><td>{{ $eof->pupilas_od }}</td><td>{{ $eof->pupilas_oi }}</td></tr>
                    <tr><td>Oftalmoscopía</td><td>{{ $eof->oftalmoscopia_od }}</td><td>{{ $eof->oftalmoscopia_oi }}</td></tr>
                    <tr><td>Schirmer</td><td>{{ $eof->schirmer_od }}</td><td>{{ $eof->schirmer_oi }}</td></tr>
                    <tr><td>Amsler</td><td>{{ $eof->amsler_od }}</td><td>{{ $eof->amsler_oi }}</td></tr>
                </table>
                @if($eof->otros)
                    <div class="info-row"><strong>Otros:</strong> {{ $eof->otros }}</div>
                @endif
                @if($eof->observaciones)
                    <div class="info-row"><strong>Observaciones:</strong> {{ $eof->observaciones }}</div>
                @endif
            </div>
        @endif

        {{-- Exámenes Preliminares --}}
        @if($h->examenesPreliminares)
            @php $ep = $h->examenesPreliminares; @endphp
            <div class="section-box">
                <h2>Exámenes Preliminares</h2>
                @if($ep->fecha_examen)
                    <div class="info-row"><strong>Fecha:</strong> {{ $ep->fecha_examen->format('d/m/Y') }}</div>
                @endif
                <table>
                    <tr><th>Campo</th><th>OD</th><th>OI</th></tr>
                    <tr><td>Observación</td><td>{{ $ep->observacion_od }}</td><td>{{ $ep->observacion_oi }}</td></tr>
                    <tr><td>AV</td><td>{{ $ep->av_od }}</td><td>{{ $ep->av_oi }}</td></tr>
                    <tr><td>AV Estenop.</td><td>{{ $ep->av_estenop_od }}</td><td>{{ $ep->av_estenop_oi }}</td></tr>
                    <tr><td>CCV</td><td>{{ $ep->ccv_od }}</td><td>{{ $ep->ccv_oi }}</td></tr>
                </table>
                <div class="info-row"><strong>AV AO:</strong> {{ $ep->av_ao }}</div>
                <div class="info-row"><strong>Motilidad 1:</strong> {{ $ep->motilidad_1 }}</div>
                <div class="info-row"><strong>Hirschberg 1:</strong> {{ $ep->hirschberg_1 }}</div>
                <div class="info-row"><strong>Purkinje:</strong> {{ $ep->purkinje }}</div>
                <div class="info-row"><strong>Motilidad 2:</strong> {{ $ep->motilidad_2 }}</div>
                <div class="info-row"><strong>Hirschberg 2:</strong> {{ $ep->hirschberg_2 }}</div>
            </div>
        @endif

        {{-- RX Final Lente --}}
        @if($h->rxFinalLente)
            @php $rx = $h->rxFinalLente; @endphp
            <div class="section-box">
                <h2>RX Final Lente</h2>
                @if($rx->fecha_examen)
                    <div class="info-row"><strong>Fecha:</strong> {{ $rx->fecha_examen->format('d/m/Y') }}</div>
                @endif
                <h3>OD (Ojo Derecho)</h3>
                <table>
                    <tr><th>ESF</th><th>CYL</th><th>EJE</th><th>ADD</th><th>Prisma</th><th>DNP</th><th>DP</th><th>ALT</th><th>AV Lejos</th><th>AV Cerca</th></tr>
                    <tr>
                        <td>{{ $rx->od_esf }}</td><td>{{ $rx->od_cyl }}</td><td>{{ $rx->od_eje }}</td><td>{{ $rx->od_add }}</td>
                        <td>{{ $rx->od_prisma }}</td><td>{{ $rx->od_dnp }}</td><td>{{ $rx->od_dp }}</td><td>{{ $rx->od_alt }}</td>
                        <td>{{ $rx->od_av_lejos }}</td><td>{{ $rx->od_av_cerca }}</td>
                    </tr>
                </table>
                <h3>OI (Ojo Izquierdo)</h3>
                <table>
                    <tr><th>ESF</th><th>CYL</th><th>EJE</th><th>ADD</th><th>Prisma</th><th>DNP</th><th>DP</th><th>ALT</th><th>AV Lejos</th><th>AV Cerca</th></tr>
                    <tr>
                        <td>{{ $rx->oi_esf }}</td><td>{{ $rx->oi_cyl }}</td><td>{{ $rx->oi_eje }}</td><td>{{ $rx->oi_add }}</td>
                        <td>{{ $rx->oi_prisma }}</td><td>{{ $rx->oi_dnp }}</td><td>{{ $rx->oi_dp }}</td><td>{{ $rx->oi_alt }}</td>
                        <td>{{ $rx->oi_av_lejos }}</td><td>{{ $rx->oi_av_cerca }}</td>
                    </tr>
                </table>
                <div class="info-row"><strong>AVCC AO Lejos:</strong> {{ $rx->avcc_ao_lejos }}</div>
                <div class="info-row"><strong>AVCC AO Cerca:</strong> {{ $rx->avcc_ao_cerca }}</div>
                <div class="info-row"><strong>Diseño:</strong> {{ $rx->diseno_lente }}</div>
                <div class="info-row"><strong>Material:</strong> {{ $rx->material }}</div>
                <div class="info-row"><strong>Tratamiento:</strong> {{ $rx->tratamiento }}</div>
                @if($rx->diagnostico)
                    <div class="info-row"><strong>Diagnóstico:</strong> {{ $rx->diagnostico }}</div>
                @endif
                @if($rx->recomendaciones)
                    <div class="info-row"><strong>Recomendaciones:</strong> {{ $rx->recomendaciones }}</div>
                @endif
                @if($rx->observaciones)
                    <div class="info-row"><strong>Observaciones:</strong> {{ $rx->observaciones }}</div>
                @endif
            </div>
        @endif
    @endif

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} — Sistema de Citas Médicas
    </div>

</body>
</html>
