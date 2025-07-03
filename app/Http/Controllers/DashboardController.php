<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\ServicePlan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-dashboard');
    }

    public function index()
    {
        // Get key statistics
        $stats = [
            'total_clients' => Client::count(),
            'new_clients_this_month' => Client::whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)
                                           ->count(),
            'monthly_revenue' => Invoice::where('status', 'paid')
                                      ->whereMonth('paid_at', now()->month)
                                      ->whereYear('paid_at', now()->year)
                                      ->sum('total_amount'),
            'revenue_growth' => $this->calculateRevenueGrowth(),
            'active_devices' => Device::where('status', 'active')->count(),
            'device_uptime' => 99.2, // This would come from monitoring system
            'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
            'urgent_tickets' => Ticket::where('priority', 'urgent')
                                     ->whereIn('status', ['open', 'in_progress'])
                                     ->count(),
            'last_backup' => 'Yesterday 2:00 AM',
            'system_load' => 'Low',
        ];

        // Get recent data
        $recent_invoices = Invoice::with('client')
                                 ->latest()
                                 ->limit(5)
                                 ->get();

        $recent_payments = Payment::with('client')
                                 ->where('status', 'completed')
                                 ->latest()
                                 ->limit(5)
                                 ->get();

        $service_plans = ServicePlan::withCount('activeSubscriptions')
                                   ->where('status', 'active')
                                   ->get();

        return view('dashboard', compact(
            'stats',
            'recent_invoices', 
            'recent_payments',
            'service_plans'
        ));
    }

    private function calculateRevenueGrowth()
    {
        $currentMonth = Invoice::where('status', 'paid')
                              ->whereMonth('paid_at', now()->month)
                              ->whereYear('paid_at', now()->year)
                              ->sum('total_amount');

        $lastMonth = Invoice::where('status', 'paid')
                           ->whereMonth('paid_at', now()->subMonth()->month)
                           ->whereYear('paid_at', now()->subMonth()->year)
                           ->sum('total_amount');

        if ($lastMonth == 0) return 0;

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }
}
