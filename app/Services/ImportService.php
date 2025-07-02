<?php

namespace App\Services;

use App\Models\Import;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Auth;

class ImportService implements ImportServiceInterface
{
    public function importFromExcel($file, $userId)
    {
        $path = $file->store('imports');
        $errors = [];
        $successCount = 0;
        $rowNum = 1;
        $import = Import::create([
            'file_path' => $path,
            'status' => 'processing',
            'created_by' => $userId,
        ]);
        try {
            $rows = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
            $sheet = $rows[0] ?? [];
            if (empty($sheet) || count($sheet) < 2) {
                throw new \Exception('Excel file must have a header and at least one data row.');
            }
            $header = array_map('strtolower', $sheet[0]);
            $expected = ['client_name','client_email','client_address','client_phone','invoice_total','invoice_status','item_description','item_quantity','item_price','item_total'];
            if ($header !== $expected) {
                throw new \Exception('Excel header does not match expected format.');
            }
            $clientCache = [];
            $invoiceMap = [];
            for ($i = 1; $i < count($sheet); $i++) {
                $row = $sheet[$i];
                $rowNum = $i + 1;
                // Validate required fields
                if (
                    empty($row[0]) || empty($row[1]) || empty($row[4]) || empty($row[6]) || empty($row[7]) || empty($row[8])
                ) {
                    $errors[] = "Row $rowNum: Missing required fields.";
                    continue;
                }
                $clientEmail = $row[1];
                if (isset($clientCache[$clientEmail])) {
                    $client = $clientCache[$clientEmail];
                } else {
                    $client = Client::where('email', $clientEmail)->first();
                    if (!$client) {
                        $client = Client::create([
                            'email' => $clientEmail,
                            'name' => $row[0],
                            'address' => $row[2] ?? null,
                            'phone' => $row[3] ?? null,
                        ]);
                    }
                    $clientCache[$clientEmail] = $client;
                }
                // Group by invoice (client_email + total + status)
                $invoiceKey = $client->id . '|' . $row[4] . '|' . ($row[5] ?? 'pending');
                if (!isset($invoiceMap[$invoiceKey])) {
                    $invoice = Invoice::create([
                        'client_id' => $client->id,
                        'total' => $row[4],
                        'status' => $row[5] ?? 'pending',
                    ]);
                    $invoiceMap[$invoiceKey] = $invoice;
                } else {
                    $invoice = $invoiceMap[$invoiceKey];
                }
                // Item
                try {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $row[6],
                        'quantity' => $row[7],
                        'price' => $row[8],
                        'total' => $row[9] ?? ($row[7] * $row[8]),
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row $rowNum: " . $e->getMessage();
                }
            }
            $import->status = 'completed';
            $import->save();
        } catch (\Exception $e) {
            $import->status = 'failed';
            $import->save();
            $errors[] = $e->getMessage();
        }
        return [
            'import_id' => $import->id,
            'success_count' => $successCount,
            'errors' => $errors,
        ];
    }

    public function updateImport($importId, $data)
    {
        $import = Import::findOrFail($importId);
        if (isset($data['status'])) {
            $import->status = $data['status'];
        }
        if (isset($data['file']) && $data['file']->isValid()) {
            // Optionally replace the file
            $path = $data['file']->store('imports');
            $import->file_path = $path;
        }
        $import->save();
        return $import;
    }

    public function deleteImport($importId)
    {
        $import = Import::findOrFail($importId);
        // Optionally delete the file from storage
        if ($import->file_path) {
            \Illuminate\Support\Facades\Storage::delete($import->file_path);
        }
        $import->delete();
        return true;
    }

    public function getAllImports($perPage = 15)
    {
        return Import::with('user')->paginate($perPage);
    }

    public function getImport($importId)
    {
        return Import::with('user')->findOrFail($importId);
    }
} 