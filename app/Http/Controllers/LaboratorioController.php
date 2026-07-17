<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\User;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    /**
     * Listar laboratorios (con búsqueda opcional por nombre o categoría).
     */
    public function index(Request $request)
    {
        $laboratorios = Laboratorio::query()
            ->with('encargado')
            ->when($request->filled('nombre'), function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->nombre . '%');
            })
            ->when($request->filled('categoria'), function ($q) use ($request) {
                $q->where('categoria', 'like', '%' . $request->categoria . '%');
            })
            ->orderBy('id_laboratorio', 'desc')
            ->get();

        $oftalmologos = $this->getOftalmologos();

        return view('laboratorios.index', compact('laboratorios', 'oftalmologos'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $laboratorio = Laboratorio::with('encargado')->findOrFail($id);
        $oftalmologos = $this->getOftalmologos($laboratorio->id_encargado);

        return view('laboratorios.edit', compact('laboratorio', 'oftalmologos'));
    }

    /**
     * Crear un laboratorio.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:150',
            'categoria'      => 'nullable|string|max:100',
            'fecha_creacion' => 'nullable|date',
            'id_encargado'   => 'nullable|integer|exists:tbl_user,us_id',
        ]);

        Laboratorio::create($request->only([
            'nombre', 'categoria', 'fecha_creacion', 'id_encargado',
        ]));

        return redirect()->route('laboratorios.index')
            ->with('success', 'Laboratorio creado correctamente.');
    }

    /**
     * Actualizar un laboratorio.
     */
    public function update(Request $request, $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $request->validate([
            'nombre'         => 'required|string|max:150',
            'categoria'      => 'nullable|string|max:100',
            'fecha_creacion' => 'nullable|date',
            'id_encargado'   => 'nullable|integer|exists:tbl_user,us_id',
        ]);

        $laboratorio->update($request->only([
            'nombre', 'categoria', 'fecha_creacion', 'id_encargado',
        ]));

        return redirect()->route('laboratorios.index')
            ->with('success', 'Laboratorio actualizado correctamente.');
    }

    /**
     * Eliminar un laboratorio.
     */
    public function destroy($id)
    {
        $laboratorio = Laboratorio::findOrFail($id);
        $laboratorio->delete();

        return redirect()->route('laboratorios.index')
            ->with('success', 'Laboratorio eliminado correctamente.');
    }

    /**
     * Lista de usuarios con rol de Oftalmólogo, ordenados por nombre.
     * Si el encargado actual del laboratorio no es oftalmólogo (por si cambió de rol),
     * se incluye igual para no perder la selección.
     */
    private function getOftalmologos(?int $incluirId = null)
    {
        $query = User::query()
            ->withRoleName('oftalm')
            ->where('us_status', '!=', 'I')
            ->orderBy('us_name')
            ->orderBy('us_lastName')
            ->get(['us_id', 'us_name', 'us_lastName']);

        if ($incluirId && !$query->contains('us_id', $incluirId)) {
            $extra = User::find($incluirId, ['us_id', 'us_name', 'us_lastName']);
            if ($extra) {
                $query->push($extra);
            }
        }

        return $query;
    }
}
