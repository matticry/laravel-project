<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\ServiceServiceInterface;
use App\Services\Interfaces\WorkOrderRepositoryInterface;
use App\Services\WorkOrderService;
use DateTime;
use Exception;
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
        $countPending = $this->calendarioService->CountPendingOrders();

        $countAssigned = $this->calendarioService->CountAuthorizedOrders();

        $countCompleted = $this->calendarioService->CountFinishedOrders();

        $total = $this->calendarioService->CountTotalOrders();

        $products = $this->productService->getAllProducts()->take(4);
        $allProducts = $this->productService->getAllProducts();
        $employees = $this->calendarioService->getEmployees();
        $services = $this->serviceService->getAllServices()->take(4);
        $allServices = $this->serviceService->getAllServices();
        $clients = $this->calendarioService->getClients();
        return view('calendario.index', compact('employees', 'clients', 'products', 'services', 'allProducts', 'allServices', 'countPending', 'countAssigned', 'countCompleted', 'total'));
    }

    public function workOrder()
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


    public function store(Request $request)
    {
        if ($this->workOrderService->existsWorkOrderByCode($request->wo_order_code)) {
            return redirect()->route('calendario.index')->withErrors('error', 'El orden de trabajo ya existe');

        }
        try {
            $validatedData = $this->validateDataWorkOrder($request);


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
    //Solo se ocupa en el metodo store para formatear las fechas
    //para evitar problemas con los formatos de fecha que acepta mysql
    private function formatDate($dateString)
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

    public function authorizeWorkOrder(WorkOrder $workorder)
    {
        try {
            $this->workOrderService->authorizeWorkOrder($workorder);
            return redirect()->back()->with('success', 'Orden de trabajo autorizada correctamente.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('error', 'No se pudo autorizar la orden de trabajo: ' . $e->getMessage());
        }

    }

    public function destroy($workOrderId)
    {
        try {
            // Buscar la orden de trabajo
            $workOrder = $this->workOrderService->findById($workOrderId);

            if (!$workOrder) {
                return redirect()->route('calendario.ordenes')->withErrors('error', 'No se encontró la orden de trabajo');
            }

            // Eliminar los productos asociados
            $this->workOrderService->deleteProducts($workOrder->wo_id);

            // Eliminar los servicios asociados
            $this->workOrderService->deleteServices($workOrder->wo_id);

            // Eliminar la orden de trabajo
            $this->workOrderService->delete($workOrder->wo_id);

            return redirect()->route('calendario.ordenes')->with('success', 'Orden de trabajo eliminada con éxito');
        } catch (Exception $e) {
            return redirect()->route('calendario.ordenes')->withErrors('error', 'No se pudo eliminar la orden de trabajo: ' . $e->getMessage());
        }
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

            return redirect()->route('calendario.ordenes')->with('success', 'Orden de trabajo actualizada con éxito');
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Introduce bien estos datos:\n";

            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }
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



}
