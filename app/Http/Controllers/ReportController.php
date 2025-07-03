<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\NetworkUsage;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-reports');
    }

    /**
     * Display reports dashboard
     */
    public function index()
    {
        // Simple version for testing
        $revenue = [
            'current_month' => 0,
            'last_month' => 0,
            'total_outstanding' => 0,
            'total_collected' => 0,
        ];

        $clientStats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'new_this_month' => 0,
        ];

        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => 0
            ];
        }

        $topPlans = collect();

        return view('reports.index', compact(
            'revenue',
            'clientStats',
            'monthlyRevenue',
            'topPlans'
        ));
    }

    /**
     * Generate revenue report
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $invoices = Invoice::with(['client', 'servicePlan'])
                          ->whereBetween('issue_date', [$startDate, $endDate])
                          ->orderBy('issue_date', 'desc')
                          ->paginate(20);

        $summary = [
            'total_invoiced' => Invoice::whereBetween('issue_date', [$startDate, $endDate])
                                     ->sum('total_amount'),
            'total_paid' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                                  ->sum('amount'),
            'total_outstanding' => Invoice::whereIn('status', ['pending', 'overdue', 'partially_paid'])
                                         ->whereBetween('issue_date', [$startDate, $endDate])
                                         ->sum('total_amount') - 
                                  Payment::whereHas('invoice', function($q) use ($startDate, $endDate) {
                                      $q->whereBetween('issue_date', [$startDate, $endDate]);
                                  })->sum('amount'),
        ];

        return view('reports.revenue', compact('invoices', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Generate clients report
     */
    public function clients(Request $request)
    {
        $query = Client::with(['activeSubscription.servicePlan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_type')) {
            $query->where('client_type', $request->client_type);
        }

        $clients = $query->orderBy('name')->paginate(20);

        $summary = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'suspended_clients' => Client::where('status', 'suspended')->count(),
            'inactive_clients' => Client::where('status', 'inactive')->count(),
        ];

        return view('reports.clients', compact('clients', 'summary'));
    }

    /**
     * Generate usage report
     */
    public function usage(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $usageData = NetworkUsage::with(['client', 'subscription.servicePlan'])
                                ->whereBetween('usage_date', [$startDate, $endDate])
                                ->orderBy('usage_date', 'desc')
                                ->paginate(20);

        $summary = [
            'total_usage_gb' => round(NetworkUsage::whereBetween('usage_date', [$startDate, $endDate])
                                                ->sum('total_mb') / 1024, 2),
            'average_daily_usage' => round(NetworkUsage::whereBetween('usage_date', [$startDate, $endDate])
                                                      ->avg('total_mb') / 1024, 2),
            'top_users' => NetworkUsage::with('client')
                                     ->select('client_id', DB::raw('SUM(total_mb) as total_usage'))
                                     ->whereBetween('usage_date', [$startDate, $endDate])
                                     ->groupBy('client_id')
                                     ->orderBy('total_usage', 'desc')
                                     ->limit(10)
                                     ->get(),
        ];

        return view('reports.usage', compact('usageData', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Export reports
     */
    public function export($type, Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        switch ($type) {
            case 'revenue':
                return $this->exportRevenue($startDate, $endDate);
            case 'clients':
                return $this->exportClients();
            case 'usage':
                return $this->exportUsage($startDate, $endDate);
            default:
                return redirect()->back()->with('error', 'Invalid export type');
        }
    }

    private function exportRevenue($startDate, $endDate)
    {
        $invoices = Invoice::with(['client', 'subscription.servicePlan'])
                          ->whereBetween('invoice_date', [$startDate, $endDate])
                          ->orderBy('invoice_date', 'desc')
                          ->get();

        $csvData = "Invoice Number,Client Name,Service Plan,Invoice Date,Due Date,Amount,Status,Paid Date\n";
        
        foreach ($invoices as $invoice) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $invoice->invoice_number,
                $invoice->client->name,
                $invoice->subscription->servicePlan->name ?? 'N/A',
                $invoice->invoice_date->format('Y-m-d'),
                $invoice->due_date->format('Y-m-d'),
                $invoice->total_amount,
                $invoice->status,
                $invoice->paid_at ? $invoice->paid_at->format('Y-m-d') : 'N/A'
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="revenue_report_' . $startDate . '_to_' . $endDate . '.csv"');
    }

    private function exportClients()
    {
        $clients = Client::with(['activeSubscription.servicePlan'])->get();

        $csvData = "Name,Email,Phone,Status,Client Type,Service Plan,Account Balance,Connection Date\n";
        
        foreach ($clients as $client) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $client->name,
                $client->email,
                $client->phone,
                $client->status,
                $client->client_type,
                $client->activeSubscription->servicePlan->name ?? 'No Active Plan',
                $client->account_balance,
                $client->connection_date ? $client->connection_date->format('Y-m-d') : 'N/A'
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="clients_report_' . now()->format('Y-m-d') . '.csv"');
    }

    private function exportUsage($startDate, $endDate)
    {
        $usageData = NetworkUsage::with(['client', 'subscription.servicePlan'])
                                ->whereBetween('usage_date', [$startDate, $endDate])
                                ->orderBy('usage_date', 'desc')
                                ->get();

        $csvData = "Client Name,Service Plan,Date,Download (GB),Upload (GB),Total (GB),Session Hours\n";
        
        foreach ($usageData as $usage) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $usage->client->name,
                $usage->subscription->servicePlan->name ?? 'N/A',
                $usage->usage_date->format('Y-m-d'),
                $usage->download_gb,
                $usage->upload_gb,
                $usage->total_gb,
                $usage->session_duration_hours
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="usage_report_' . $startDate . '_to_' . $endDate . '.csv"');
    }
}
