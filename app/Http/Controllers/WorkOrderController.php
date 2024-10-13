<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\ServiceServiceInterface;
use App\Services\Interfaces\WorkOrderRepositoryInterface;
use App\Services\WorkOrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WorkOrderController extends Controller
{
    protected $calendarioService, $serviceService, $productService, $workOrderService;



    public function __construct(WorkOrderService $calendarioService, ServiceServiceInterface $serviceService, ProductServiceInterface $productService, WorkOrderRepositoryInterface $workOrderRepository)
    {
        $this->calendarioService = $calendarioService;
        $this->serviceService = $serviceService;
        $this->productService = $productService;
        $this->workOrderService = $workOrderRepository;
    }
    /**
     * @param Request $request
     * @return array
     */
    public function validateDataWorkOrder(Request $request): array
    {
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
        return $validatedData;
    }

    public function index()
    {

        $products = $this->productService->getAllProducts()->take(4);
        $employees = $this->calendarioService->getEmployees();
        $clients = $this->calendarioService->getClients();
        $services = $this->serviceService->getAllServices()->take(4);
        $allProducts = $this->productService->getAllProducts();
        $allServices = $this->serviceService->getAllServices();
        $workOrders = $this->workOrderService->getAllWorkOrders();
        return view('calendario.ordenes', compact('workOrders', 'employees', 'clients', 'products', 'services', 'allProducts', 'allServices'));

    }
    public function update(Request $request, $workOrderId)
    {
        try {
            $validatedData = $this->validateDataWorkOrder($request);

            $workOrder = $this->workOrderService->findById($workOrderId);

            if (!$workOrder) {
                return redirect()->route('calendario.index')->withErrors('error', 'No se encontró la orden de trabajo');
            }

            // Actualizar los campos básicos de la orden de trabajo
            $workOrder = $this->workOrderService->update($workOrder, $validatedData);

            // Eliminar los productos y servicios existentes
            $this->workOrderService->deleteProducts($workOrder->wo_id);
            $this->workOrderService->deleteServices($workOrder->wo_id);

            // Agregar los nuevos productos
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

            // Agregar los nuevos servicios
            $services = [];
            foreach ($request->selected_services as $serviceId) {
                $services[] = [
                    'id' => $serviceId,
                    'price' => $request->input('service_price.' . $serviceId, 0),
                    'tasks' => explode(',', $request->input('service_tasks.' . $serviceId, '')),
                ];
            }

            $this->workOrderService->addServices($workOrder->wo_id, $services);

            return redirect()->route('calendario.index')->with('success', 'Orden de trabajo actualizada con éxito');
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Introduce bien estos datos:\n";

            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }
    }
}
