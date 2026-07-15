<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Services\Interfaces\IConsultaService;
use Exception;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function __construct(protected IConsultaService $consultaService) {}

    public function index(Request $request)
    {
        $consultas = $this->consultaService->getAllConsultas($request->only('nombre'));

        $pacientes = Profile::where('us_status', 'A')
            ->select('us_id', 'us_name', 'us_lastName', 'us_dni')
            ->orderBy('us_name')
            ->get();

        return view('consultas.index', compact('consultas', 'pacientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_paciente'                         => 'required|exists:tbl_user,us_id',
            'motivo'                              => 'required|string',
            'motivo_consulta'                     => 'required|string',
            'antecedentes_patologicos_familiares' => 'nullable|string',
            'antecedentes_patologicos_personales' => 'nullable|string',
            'antecedentes_oculares_familiares'    => 'nullable|string',
            'antecedentes_oculares_personales'    => 'nullable|string',
            'utiliza_lentes'                      => 'nullable|boolean',
            'tipo_lente'                          => 'nullable|string|max:100',
            'fecha_inicio_uso_lentes'             => 'nullable|date',
            'observaciones'                       => 'nullable|string',
        ]);


        try {

           $consulta = $this->consultaService->createConsulta($request->all());

           if (!$consulta) {
               return redirect()->back()->with('error', 'Error al crear la consulta');
           }

            return redirect()->route('profile.index')->with('success', 'Consulta e historial clínico creados correctamente.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la consulta: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function porPaciente($pacienteId)
    {
        $consultas = $this->consultaService->getConsultasPorPaciente($pacienteId);
        return response()->json($consultas);
    }

    public function destroy($id)
    {
        try {
            $this->consultaService->deleteConsulta($id);

            return redirect()->route('consultas.index')
                ->with('success', 'Consulta eliminada correctamente.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la consulta: ' . $e->getMessage());
        }
    }
}
