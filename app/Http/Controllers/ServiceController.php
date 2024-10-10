<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ServiceServiceInterface;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceServiceInterface $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        $services = $this->serviceService->getAllServices();
        if ($request->has('name_serv')) {
            if ($request->has('name_serv') && $request->name_serv) {
                $services = $services->filter(function ($service) use ($request) {
                    return str_contains($service->name_serv, $request->name_serv);
                });
            }
        }
        return view('services.index', compact('services'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_serv' => 'required|max:250',
            'description_serv' => 'required|max:200',
            'price_serv' => 'required|numeric',
            'tasks' => 'required|array',
            'tasks.*.name_task' => 'required|max:250',
        ]);

        $serviceData = [
            'name_serv' => $validatedData['name_serv'],
            'description_serv' => $validatedData['description_serv'],
            'price_serv' => $validatedData['price_serv'],
            'status_serv' => 'A', // Asumiendo que el servicio se crea como activo por defecto
        ];

        $tasksData = array_map(function ($task) {
            return [
                'name_task' => $task['name_task'],
                'status_task' => 'A', // Asumiendo que la tarea se crea como activa por defecto
            ];
        }, $validatedData['tasks']);

        $service = $this->serviceService->createService($serviceData, $tasksData);

        return redirect()->route('services.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function edit($id)
    {
        $service = $this->serviceService->getServiceById($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name_serv' => 'required|max:250',
            'description_serv' => 'required|max:200',
            'price_serv' => 'required|numeric',
            'status_serv' => 'required|in:A,I',
            'tasks' => 'required|array',
            'tasks.*.name_task' => 'required|max:250',
        ]);

        $serviceData = [
            'name_serv' => $validatedData['name_serv'],
            'description_serv' => $validatedData['description_serv'],
            'price_serv' => $validatedData['price_serv'],
            'status_serv' => $validatedData['status_serv'],
        ];

        $tasksData = array_map(function ($task) {
            return [
                'name_task' => $task['name_task'],
            ];
        }, $validatedData['tasks']);

        $service = $this->serviceService->updateService($id, $serviceData, $tasksData);

        return redirect()->route('services.index')->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $this->serviceService->deleteService($id);
        return redirect()->route('services.index')->with('success', 'Servicio eliminado exitosamente.');
    }
}
