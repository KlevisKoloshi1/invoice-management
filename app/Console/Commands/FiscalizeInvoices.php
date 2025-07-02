<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FiscalizeInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:fiscalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send created invoices to the Tax Authorities for fiscalization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $invoices = Invoice::where('fiscalized', false)->get();
        foreach ($invoices as $invoice) {
            try {
                // Example payload, adjust as needed for the real API
                $payload = [
                    'json' => [
                        'invoice_id' => $invoice->id,
                        'total' => $invoice->total,
                        // Add other required fields here
                    ]
                ];
                $response = $client->post('https://efiskalizimi-app-test.tatime.gov.al/api/invoice', $payload);
                $body = json_decode($response->getBody(), true);
                $invoice->fiscalized = true;
                $invoice->fiscalization_response = json_encode($body);
                $invoice->fiscalized_at = now();
                $invoice->save();
            } catch (\Exception $e) {
                Log::error('Fiscalization failed for invoice ' . $invoice->id . ': ' . $e->getMessage());
            }
        }
        $this->info('Fiscalization process completed.');
    }
}
