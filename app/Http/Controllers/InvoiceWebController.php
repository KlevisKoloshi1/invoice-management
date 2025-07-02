<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoiceWebController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->orderByDesc('created_at')->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        return view('invoices.show', compact('invoice'));
    }
}
