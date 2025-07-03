<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Client;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-devices');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::with(['client'])
                        ->orderBy('name')
                        ->paginate(20);

        return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        return view('devices.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_type' => 'required|in:router,switch,access_point,firewall,server,other',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:devices,serial_number',
            'mac_address' => 'nullable|string|max:17|unique:devices,mac_address',
            'ip_address' => 'nullable|ip|unique:devices,ip_address',
            'location' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'installation_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,faulty',
        ]);

        Device::create($validated);

        return redirect()->route('devices.index')
                        ->with('success', 'Device created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        $device->load('client');
        return view('devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        return view('devices.edit', compact('device', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_type' => 'required|in:router,switch,access_point,firewall,server,other',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:devices,serial_number,' . $device->id,
            'mac_address' => 'nullable|string|max:17|unique:devices,mac_address,' . $device->id,
            'ip_address' => 'nullable|ip|unique:devices,ip_address,' . $device->id,
            'location' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'installation_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,faulty',
        ]);

        $device->update($validated);

        return redirect()->route('devices.index')
                        ->with('success', 'Device updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')
                        ->with('success', 'Device deleted successfully.');
    }
}
