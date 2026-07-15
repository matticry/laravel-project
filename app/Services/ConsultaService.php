<?php

namespace App\Services;

use App\Models\Consulta;
use App\Models\HistorialClinico;
use App\Services\Interfaces\IConsultaService;
use Illuminate\Support\Facades\DB;

class ConsultaService implements IConsultaService
{
    public function getConsultasPorPaciente($pacienteId)
    {
        return Consulta::with(['historialClinico'])
            ->where('id_paciente', $pacienteId)
            ->orderByDesc('fecha_registro')
            ->get();
    }
    public function getAllConsultas(array $filters = [])
    {
        return Consulta::with(['paciente', 'historialClinico'])
            ->when(!empty($filters['nombre']), function ($q) use ($filters) {
                $q->whereHas('paciente', function ($q2) use ($filters) {
                    $q2->where('us_name', 'like', '%' . $filters['nombre'] . '%')
                        ->orWhere('us_lastName', 'like', '%' . $filters['nombre'] . '%');
                });
            })
            ->orderByDesc('fecha_registro')
            ->paginate(10);
    }

    public function getConsultaById($id)
    {
        return Consulta::with(['paciente', 'historialClinico'])
            ->findOrFail($id);
    }

    public function createConsulta(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear historial clínico
            $historial = HistorialClinico::create([
                'antecedentes_patologicos_familiares' => $data['antecedentes_patologicos_familiares'] ?? null,
                'antecedentes_patologicos_personales' => $data['antecedentes_patologicos_personales'] ?? null,
                'antecedentes_oculares_familiares'    => $data['antecedentes_oculares_familiares'] ?? null,
                'antecedentes_oculares_personales'    => $data['antecedentes_oculares_personales'] ?? null,
                'utiliza_lentes'                      => $data['utiliza_lentes'] ?? false,
                'tipo_lente'                          => $data['tipo_lente'] ?? null,
                'fecha_inicio_uso_lentes'             => $data['fecha_inicio_uso_lentes'] ?? null,
                'motivo_consulta'                     => $data['motivo_consulta'],
                'observaciones'                       => $data['observaciones'] ?? null,
            ]);

            // 2. Crear consulta vinculada
            $consulta = Consulta::create([
                'id_paciente'          => $data['id_paciente'],
                'motivo'               => $data['motivo'],
                'id_historial_clinico' => $historial->id_historial_clinico,
            ]);

            return $consulta->load(['paciente', 'historialClinico']);
        });
    }

    public function updateConsulta($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $consulta = Consulta::findOrFail($id);

            // Actualizar consulta
            $consulta->update([
                'id_paciente' => $data['id_paciente'] ?? $consulta->id_paciente,
                'motivo'      => $data['motivo'] ?? $consulta->motivo,
            ]);

            // Actualizar historial si existe
            if ($consulta->historialClinico) {
                $consulta->historialClinico->update([
                    'antecedentes_patologicos_familiares' => $data['antecedentes_patologicos_familiares'] ?? $consulta->historialClinico->antecedentes_patologicos_familiares,
                    'antecedentes_patologicos_personales' => $data['antecedentes_patologicos_personales'] ?? $consulta->historialClinico->antecedentes_patologicos_personales,
                    'antecedentes_oculares_familiares'    => $data['antecedentes_oculares_familiares'] ?? $consulta->historialClinico->antecedentes_oculares_familiares,
                    'antecedentes_oculares_personales'    => $data['antecedentes_oculares_personales'] ?? $consulta->historialClinico->antecedentes_oculares_personales,
                    'utiliza_lentes'                      => $data['utiliza_lentes'] ?? $consulta->historialClinico->utiliza_lentes,
                    'tipo_lente'                          => $data['tipo_lente'] ?? $consulta->historialClinico->tipo_lente,
                    'fecha_inicio_uso_lentes'             => $data['fecha_inicio_uso_lentes'] ?? $consulta->historialClinico->fecha_inicio_uso_lentes,
                    'motivo_consulta'                     => $data['motivo_consulta'] ?? $consulta->historialClinico->motivo_consulta,
                    'observaciones'                       => $data['observaciones'] ?? $consulta->historialClinico->observaciones,
                ]);
            }

            return $consulta->load(['paciente', 'historialClinico']);
        });
    }

    public function deleteConsulta($id)
    {
        return DB::transaction(function () use ($id) {
            $consulta = Consulta::findOrFail($id);

            // El cascade en FK elimina las tablas hijas automáticamente
            if ($consulta->historialClinico) {
                $consulta->historialClinico->delete();
            }

            return $consulta->delete();
        });
    }
}
