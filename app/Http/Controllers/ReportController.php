<?php

namespace App\Http\Controllers;

use App\Mail\ReportSent;
use App\Models\Report;
use App\Models\UsedProduct;
use App\Models\WorkOrder;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\Interfaces\ProfileServiceInterface;
use App\Services\Interfaces\WorkOrderRepositoryInterface;
use App\Services\ReportService;
use App\Services\WorkOrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Cloudinary\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ReportController extends Controller
{
    protected $reportService, $profileService, $productService;


    public function __construct(ReportService $reportService, ProfileServiceInterface $profileService, ProductServiceInterface $productService)
    {
        $this->reportService = $reportService;
        $this->profileService = $profileService;
        $this->productService = $productService;

    }

    public function index(Request $request)
    {
        $reports = Report::with(['workOrder', 'usedProducts.product'])
            ->paginate(10);

        $allProducts = $this->productService->getAllProducts();

        if ($request->has('wor_order_code')) {
            if ($request->has('wor_order_code') && $request->wor_order_code) {
                $reports = $reports->filter(function ($report) use ($request) {
                    return str_contains($report->workOrder->wo_order_code, $request->wor_order_code);
                });
            }
        }

        return view('reports.index', compact('reports', 'allProducts'));
    }

    /**
     * @throws Throwable
     */
    public function generatePdf($id)
    {
        try {
            $report = Report::with(['workOrder.client', 'workOrder.user', 'workOrder.usedProducts.product', 'workOrder.services.service'])->findOrFail($id);

            if (!$report->workOrder) {
                return back()->withErrors('No se encontró una orden de trabajo asociada a este reporte.');
            }

            // Generar el HTML con los datos
            $html = view('reports.pdf_template', compact('report'))->render();

            // Generar el PDF
            $pdf = PDF::loadHtml($html);
            $pdfContent = $pdf->output();

            // Guardar el PDF en el reporte
            $report->pdf_report = $pdfContent;
            $report->save();

            // Guardar el PDF en la orden de trabajo si es necesario
            if ($report->workOrder) {
                $report->workOrder->pdf_report = $pdfContent;
                $report->workOrder->save();
            }

            return redirect()->route('reports.index')->with('success', 'Reporte PDF generado correctamente.');
        }catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
    public function servePdf($id)
    {
        $report = Report::findOrFail($id);
        if (!$report->pdf_report) {
            abort(404, 'PDF no encontrado');
        }
        return response($report->pdf_report)
            ->header('Content-Type', 'application/pdf');
    }
     public function sendEmail($id)
     {
         $report = Report::with('workOrder.client')->findOrFail($id);
         if (!$report->workOrder) {
             return back()->withErrors('No se encontró una orden de trabajo asociada a este reporte.');
         }

         $client = $report->workOrder->client;
         if (!$client || !$client->us_email) {
             return back()->withErrors('No se encontró un email para este reporte.');
         }

         Mail::to($client->us_email)->send(new ReportSent($report));
         return redirect()->route('reports.index')->with('success', 'Correo enviado correctamente.');

     }

    public function update(Request $request, $id, WorkOrderService $workOrderService)
    {
        DB::beginTransaction();

        try {
            $report = Report::findOrFail($id);

            $validatedData = $request->validate([
                'description_report' => 'required|string|max:200',
                'image_report' => 'nullable|image|max:2048',
                'signature_report' => 'nullable|image|max:2048',
                'image_before_report' => 'nullable|image|max:2048',
                'signature_client_report' => 'nullable|image|max:2048',
                'used_products' => 'nullable|array',
                'used_products.*.pro_id' => 'required|exists:tbl_product,pro_id',
                'used_products.*.up_amount' => 'required|integer|min:1',
            ]);

            $updateData = ['description_report' => $validatedData['description_report']];

            $cloudinary = new Cloudinary();
            $imageFields = ['image_report', 'signature_report', 'image_before_report', 'signature_client_report'];

            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    if ($report->$field) {
                        $this->deleteFromCloudinary($report->$field);
                    }

                    $result = $cloudinary->uploadApi()->upload($request->file($field)->getRealPath(), [
                        'folder' => 'reports',
                    ]);
                    $updateData[$field] = $result['secure_url'];
                }
            }

            $report->update($updateData);

            // Manejar productos usados
            if (isset($validatedData['used_products'])) {
                $currentUsedProducts = $report->usedProducts()->pluck('up_amount', 'pro_id')->toArray();

                foreach ($validatedData['used_products'] as $product) {
                    $proId = $product['pro_id'];
                    $newAmount = $product['up_amount'];
                    $oldAmount = $currentUsedProducts[$proId] ?? 0;

                    UsedProduct::updateOrCreate(
                        ['wo_id' => $report->id_work_order, 'pro_id' => $proId],
                        ['up_amount' => $newAmount]
                    );

                    // Actualizar stock
                    $stockDifference = $newAmount - $oldAmount;
                    if ($stockDifference != 0) {
                        $workOrderService->updateStock($proId, $stockDifference);
                    }

                    // Remover de la lista de productos actuales
                    unset($currentUsedProducts[$proId]);
                }

                // Eliminar productos que ya no están en uso
                foreach ($currentUsedProducts as $proId => $amount) {
                    UsedProduct::where('wo_id', $report->id_work_order)
                        ->where('pro_id', $proId)
                        ->delete();
                    $workOrderService->updateStock($proId, -$amount);
                }
            }

            DB::commit();

            return redirect()->route('reports.index')->with('success', 'Reporte actualizado con éxito');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el reporte: ' . $e->getMessage()]);
        }
    }

    private function deleteFromCloudinary($url)
    {
        $publicId = $this->getPublicIdFromUrl($url);
        $cloudinary = new Cloudinary();
        $cloudinary->configuration->cloud->verifySSL = false;



        try {
            $result = $cloudinary->uploadApi()->destroy($publicId, ['invalidate' => true]);
            if ($result['result'] === 'ok') {
                \Log::info("Imagen eliminada correctamente: " . $publicId);
            } else {
                \Log::warning("No se pudo eliminar la imagen: " . $publicId);
            }
        } catch (\Exception $e) {
            \Log::error("Error al eliminar la imagen de Cloudinary: " . $e->getMessage());
        }
    }

    private function getPublicIdFromUrl($url)
    {
        $parts = explode('/', parse_url($url, PHP_URL_PATH));
        // Eliminamos el primer elemento vacío y el nombre del dominio
        array_shift($parts);
        array_shift($parts);
        // Juntamos los elementos restantes
        return implode('/', $parts);
    }

    public function removeProduct(Report $report, $usedProductId)
    {
        $usedProduct = UsedProduct::findOrFail($usedProductId);

        // Verifica si el producto usado pertenece a la orden de trabajo del reporte
        if ($usedProduct->wo_id !== $report->id_work_order) {
            return back()->with('error', 'El producto no pertenece a este reporte.');
        }

        $usedProduct->delete();

        return back()->with('success', 'Producto eliminado del reporte exitosamente.');
    }

    public function destroy($id, WorkOrderService $workOrderService)
    {
        DB::beginTransaction();

        try {
            $report = Report::with(['workOrder', 'usedProducts'])->findOrFail($id);



            // Actualizar el stock de los productos
            foreach ($report->usedProducts as $usedProduct) {
                $workOrderService->updateStock($usedProduct->pro_id, -$usedProduct->up_amount);
            }

            // Eliminar productos usados asociados
            $report->usedProducts()->delete();

            // Eliminar el reporte
            $report->delete();

            // Si el reporte está asociado a una orden de trabajo, actualizar su estado si es necesario
            if ($report->workOrder) {
                $remainingReports = $report->workOrder->reports()->count();
                if ($remainingReports == 0) {
                    $report->workOrder->update(['wo_status' => 'pendiente']); // O el estado que corresponda
                }
            }

            DB::commit();

            return redirect()->route('reports.index')->with('success', 'Reporte eliminado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el reporte: ' . $e->getMessage()]);
        }
    }
}
