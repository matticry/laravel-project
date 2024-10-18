<?php

namespace App\Services;

use App\Models\Report;
use App\Models\WorkOrder;
use App\Services\Interfaces\ReportServiceInterface;

class ReportService implements ReportServiceInterface
{

    public function getAllReports()
    {
        return Report::with('workOrder')->get();
    }

    public function getReportById($id)
    {
        return Report::findOrFail($id);
    }

    public function deleteReport($id): int
    {
        return Report::destroy($id);
    }

    public function createReport(array $reportDetails)
    {
        return Report::create($reportDetails);
    }

    public function updateReport($id, array $newDetails)
    {
        $report = $this->getReportById($id);
        $report->update($newDetails);
        return $report;
    }

    public function getRelatedWorkOrder($reportId)
    {
        $report = $this->getReportById($reportId);
        return $report->workOrder;
    }

    public function getUsedProducts($workOrderId)
    {
        return WorkOrder::findOrFail($workOrderId)->usedProducts;
    }
}
