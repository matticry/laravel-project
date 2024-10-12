<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\ServiceServiceInterface;
use App\Services\Interfaces\WorkOrderRepositoryInterface;
use App\Services\WorkOrderService;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class CalendarioController extends Controller
{
    protected $calendarioService, $serviceService, $productService, $workOrderService;



    public function __construct(WorkOrderService $calendarioService, ServiceServiceInterface $serviceService, ProductServiceInterface $productService, WorkOrderRepositoryInterface $workOrderRepository)
    {
        $this->calendarioService = $calendarioService;
        $this->serviceService = $serviceService;
        $this->productService = $productService;
        $this->workOrderService = $workOrderRepository;
    }

    public function index()
    {

        $products = $this->productService->getAllProducts()->take(4);
        $allProducts = $this->productService->getAllProducts();

        $employees = $this->calendarioService->getEmployees();
        $services = $this->serviceService->getAllServices()->take(4);
        $allServices = $this->serviceService->getAllServices();

        $clients = $this->calendarioService->getClients();

        return view('calendario.index', compact('employees', 'clients', 'products', 'services', 'allProducts', 'allServices'));
    }

    public function workOrder()
    {

        $workOrders = $this->workOrderService->getAllWorkOrders();
        return view('calendario.ordenes', compact('workOrders'));

    }

    public function store(Request $request)
    {
        if ($this->workOrderService->existsWorkOrderByCode($request->wo_order_code)) {
            return redirect()->route('calendario.index')->withErrors('error', 'El orden de trabajo ya existe');

        }
        try {
            $validatedData = $request->validate([
                'wo_start_date' => 'required|string',
                'wo_final_date' => 'required|string',
                'us_id' => 'required|exists:tbl_user,us_id',
                'cli_id' => 'required|exists:tbl_user,us_id',
                'wo_description' => 'required|string',
                'selected_products' => 'array',
                'selected_services' => 'array',
                'selected_tasks' => 'array',
                'wo_total' => 'required|numeric|min:0',

            ]);


            // Formatear las fechas
            $validatedData['wo_start_date'] = $this->formatDate($validatedData['wo_start_date']);
            $validatedData['wo_final_date'] = $this->formatDate($validatedData['wo_final_date']);


            $workOrder = $this->workOrderService->create($validatedData);

            if (!$workOrder) {
                return redirect()->route('calendario.index')->withErrors('error', 'No se pudo crear el orden de trabajo');
            }
            $products = [];

            foreach ($request->selected_products as $productId) {
                $quantity = $request->product_quantity[$productId] ?? 1;

                $checkStock = Product::findOrFail($productId);

                if ($checkStock->pro_amount < $quantity) {
                    return back()->withErrors(['product_quantity' => 'No hay suficiente stock del producto: ' . $checkStock->pro_name . ''])->withInput();
                }

                $products[] = [
                    'id' => $productId,
                    'quantity' => $quantity
                ];

                $this->workOrderService->updateStock($productId, $quantity);
            }

            $this->workOrderService->addProducts($workOrder->wo_id, $products);


            $services = [];
            foreach ($request->selected_services as $serviceId) {
                $services[] = [
                    'id' => $serviceId,
                    'price' => $request->input('service_price.' . $serviceId, 0),
                    'tasks' => explode(',', $request->input('service_tasks.' . $serviceId, '')),
                ];
            }

            $this->workOrderService->addServices($workOrder->wo_id, $services);

            return redirect()->route('calendario.index')->with('success', 'Orden de trabajo creada con exito');
        }catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Introduce bien estos datos:\n";

            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }

    }
    public function formatDate($dateString)
    {
        // Eliminar la coma y cualquier espacio extra
        $dateString = preg_replace('/,\s*/', ' ', $dateString);

        // Convertir la fecha de "dd/mm/yyyy HH:MM" a un objeto DateTime
        $date = DateTime::createFromFormat('d/m/Y H:i', $dateString);

        if (!$date) {
            throw new \InvalidArgumentException('Formato de fecha inválido: ' . $dateString);
        }

        // Formatear la fecha para MySQL TIMESTAMP (formato Y-m-d H:i:s)
        return $date->format('Y-m-d H:i:s');
    }
}
