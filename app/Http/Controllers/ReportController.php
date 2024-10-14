<?php

namespace App\Http\Controllers;

use App\Mail\ReportSent;
use App\Models\Report;
use App\Models\WorkOrder;
use App\Services\Interfaces\ProfileServiceInterface;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ReportController extends Controller
{
    protected $reportService, $profileService;

    public function __construct(ReportService $reportService, ProfileServiceInterface $profileService)
    {
        $this->reportService = $reportService;
        $this->profileService = $profileService;

    }
    public function index()
    {
        $reports = Report::with('workOrder')->paginate(10);
        return view('reports.index', compact('reports'));
    }

    /**
     * @throws Throwable
     */
    public function generatePdf($id)
    {
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
}
