<?php

namespace App\Services\Interfaces;

interface ReportServiceInterface
{
    public function getAllReports();
    public function getReportById($id);
    public function deleteReport($id);
    public function createReport(array $reportDetails);
    public function updateReport($id, array $newDetails);

}
