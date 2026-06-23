<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\InvoiceServiceInterface;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceServiceInterface $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }



}
