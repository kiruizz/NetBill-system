<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicePlan;

class ServicePlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-service-plans');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicePlans = ServicePlan::withCount(['subscriptions'])
                                 ->orderBy('name')
                                 ->paginate(20);

        return view('service-plans.index', compact('servicePlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'speed_download' => 'required|integer|min:1',
            'speed_upload' => 'required|integer|min:1',
            'data_limit' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'setup_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'features' => 'nullable|json',
        ]);

        ServicePlan::create($validated);

        return redirect()->route('service-plans.index')
                        ->with('success', 'Service plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicePlan $servicePlan)
    {
        $servicePlan->load(['subscriptions.client']);
        return view('service-plans.show', compact('servicePlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServicePlan $servicePlan)
    {
        return view('service-plans.edit', compact('servicePlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServicePlan $servicePlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'speed_download' => 'required|integer|min:1',
            'speed_upload' => 'required|integer|min:1',
            'data_limit' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'setup_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'features' => 'nullable|json',
        ]);

        $servicePlan->update($validated);

        return redirect()->route('service-plans.index')
                        ->with('success', 'Service plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicePlan $servicePlan)
    {
        // Check if service plan has active subscriptions
        if ($servicePlan->subscriptions()->exists()) {
            return redirect()->route('service-plans.index')
                            ->with('error', 'Cannot delete service plan with active subscriptions.');
        }

        $servicePlan->delete();

        return redirect()->route('service-plans.index')
                        ->with('success', 'Service plan deleted successfully.');
    }
}
