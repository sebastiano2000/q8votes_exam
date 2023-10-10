<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FinancialTrasanctionPDF;
use Illuminate\Http\Request;

class FinancialTrasanctionPDFController extends Controller
{
    public function index() 
    {
        return (new FinancialTrasanctionPDF)->download();
    }

    public function show(Request $request) 
    {
        return (new FinancialTrasanctionPDF)->downloadInvoice($request);
    }
}
