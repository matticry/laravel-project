<?php

namespace App\Http\Controllers;

use App\Models\Contactologia;
use App\Models\Consulta;
use App\Models\EvaluacionOftalmologica;
use App\Models\ExamenOptometrico;
use App\Models\ExamenesPreliminares;
use App\Models\HistorialClinico;
use App\Models\Lensometria;
use App\Models\Profile;
use App\Models\RxFinalLente;
use App\Mail\HistorialClinicoEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HistorialClinicoController extends Controller
{
    public function index(Request $request)
    {
        $users = Profile::with(['roles', 'consultas.historialClinico'])
            ->whereHas('roles', function ($q) {
                $q->where('rol_name', 'Usuario P');
            })
            ->when($request->filled('nombre'), function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('us_name', 'like', '%' . $request->nombre . '%')
                        ->orWhere('us_lastName', 'like', '%' . $request->nombre . '%');
                });
            })
            ->when($request->filled('dni'), function ($q) use ($request) {
                $q->where('us_dni', 'like', '%' . $request->dni . '%');
            })
            ->orderBy('us_name')
            ->get();

        return view('historial.index', compact('users'));
    }

    public function show($id)
    {
        $user = Profile::with([
            'roles',
            'consultas.historialClinico',
            'consultas.historialClinico.contactologia',
            'consultas.historialClinico.evaluacionOftalmologica',
            'consultas.historialClinico.examenOptometrico',
            'consultas.historialClinico.examenesPreliminares',
            'consultas.historialClinico.lensometria',
            'consultas.historialClinico.rxFinalLente',
        ])->findOrFail($id);

        return view('historial.show', compact('user'));
    }

    public function edit($consultaId)
    {
        $consulta = Consulta::with([
            'paciente',
            'historialClinico.contactologia',
            'historialClinico.evaluacionOftalmologica',
            'historialClinico.examenOptometrico',
            'historialClinico.examenesPreliminares',
            'historialClinico.lensometria',
            'historialClinico.rxFinalLente',
        ])->findOrFail($consultaId);

        return view('historial.edit', compact('consulta'));
    }

    public function update(Request $request, $consultaId)
    {
        $consulta = Consulta::with('historialClinico')->findOrFail($consultaId);

        DB::transaction(function () use ($request, $consulta) {

            // 1. Actualizar historial clínico
            if ($consulta->historialClinico) {
                $consulta->historialClinico->update($request->only([
                    'motivo_consulta',
                    'antecedentes_patologicos_familiares',
                    'antecedentes_patologicos_personales',
                    'antecedentes_oculares_familiares',
                    'antecedentes_oculares_personales',
                    'utiliza_lentes',
                    'tipo_lente',
                    'fecha_inicio_uso_lentes',
                    'observaciones',
                ]));

                $idHistorial = $consulta->historialClinico->id_historial_clinico;

                // 2. Lensometría
                if ($request->filled('lensometria')) {
                    Lensometria::updateOrCreate(
                        ['id_historial_clinico' => $idHistorial],
                        $request->input('lensometria')
                    );
                }

                // 3. Examen Optométrico
                if ($request->filled('examen_optometrico')) {
                    ExamenOptometrico::updateOrCreate(
                        ['id_historial_clinico' => $idHistorial],
                        $request->input('examen_optometrico')
                    );
                }

                // 4. Contactología
                if ($request->filled('contactologia')) {
                    Contactologia::updateOrCreate(
                        ['id_historial_clinico' => $idHistorial],
                        $request->input('contactologia')
                    );
                }

                // 5. Evaluación Oftalmológica
                if ($request->filled('evaluacion_oftalmologica')) {
                    EvaluacionOftalmologica::updateOrCreate(
                        ['id_historial_clinico' => $idHistorial],
                        $request->input('evaluacion_oftalmologica')
                    );
                }

                // 6. Exámenes Preliminares
                if ($request->filled('examenes_preliminares')) {
                    ExamenesPreliminares::updateOrCreate(
                        ['id_historial_clinico' => $idHistorial],
                        $request->input('examenes_preliminares')
                    );
                }

                // 7. RX Final Lente
                if ($request->filled('rx_final_lente')) {
                    RxFinalLente::updateOrCreate(
                        ['id_historial_clinico' => $idHistorial],
                        $request->input('rx_final_lente')
                    );
                }
            }
        });

        return redirect()->route('historial.show', $consulta->id_paciente)
            ->with('success', 'Historial clínico actualizado correctamente.');
    }

    public function sendEmail($consultaId)
    {
        $consulta = Consulta::with([
            'paciente',
            'historialClinico.contactologia',
            'historialClinico.evaluacionOftalmologica',
            'historialClinico.examenOptometrico',
            'historialClinico.examenesPreliminares',
            'historialClinico.lensometria',
            'historialClinico.rxFinalLente',
        ])->findOrFail($consultaId);

        if ($consulta->estado !== 'F') {
            return redirect()->back()->with('error', 'Solo se pueden enviar correos de consultas finalizadas.');
        }

        Mail::to($consulta->paciente->us_email)->send(new HistorialClinicoEmail($consulta));

        return redirect()->route('historial.show', $consulta->id_paciente)
            ->with('success', 'Correo enviado exitosamente a ' . $consulta->paciente->us_email);
    }
}
