<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicePlan;

class ServicePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servicePlans = [
            [
                'name' => 'Basic Home 5Mbps',
                'description' => 'Perfect for light browsing, email, and social media',
                'monthly_price' => 1500.00,
                'setup_fee' => 2000.00,
                'speed_mbps' => 5,
                'data_limit_gb' => 50,
                'billing_cycle' => 'monthly',
                'is_unlimited' => false,
                'plan_type' => 'residential',
                'status' => 'active',
                'features' => ['Email Support', 'Basic Installation', '24/7 Monitoring'],
                'max_devices' => 5,
                'overage_rate' => 5.00,
            ],
            [
                'name' => 'Standard Home 10Mbps',
                'description' => 'Great for streaming and multiple devices',
                'monthly_price' => 2500.00,
                'setup_fee' => 2000.00,
                'speed_mbps' => 10,
                'data_limit_gb' => 100,
                'billing_cycle' => 'monthly',
                'is_unlimited' => false,
                'plan_type' => 'residential',
                'status' => 'active',
                'features' => ['Priority Support', 'Free Installation', '24/7 Monitoring', 'WiFi Router'],
                'max_devices' => 10,
                'overage_rate' => 4.00,
            ],
            [
                'name' => 'Premium Home Unlimited',
                'description' => 'Unlimited high-speed internet for power users',
                'monthly_price' => 4000.00,
                'setup_fee' => 1500.00,
                'speed_mbps' => 20,
                'data_limit_gb' => null,
                'billing_cycle' => 'monthly',
                'is_unlimited' => true,
                'plan_type' => 'residential',
                'status' => 'active',
                'features' => ['Priority Support', 'Free Installation', '24/7 Monitoring', 'WiFi Router', 'Static IP'],
                'max_devices' => 20,
                'overage_rate' => null,
            ],
            [
                'name' => 'Business 50Mbps',
                'description' => 'High-speed internet for small businesses',
                'monthly_price' => 8000.00,
                'setup_fee' => 3000.00,
                'speed_mbps' => 50,
                'data_limit_gb' => null,
                'billing_cycle' => 'monthly',
                'is_unlimited' => true,
                'plan_type' => 'business',
                'status' => 'active',
                'features' => ['24/7 Support', 'Free Installation', 'SLA Guarantee', 'Static IP', 'Business Router'],
                'max_devices' => 50,
                'overage_rate' => null,
            ],
            [
                'name' => 'Corporate 100Mbps',
                'description' => 'Enterprise-grade internet for large organizations',
                'monthly_price' => 15000.00,
                'setup_fee' => 5000.00,
                'speed_mbps' => 100,
                'data_limit_gb' => null,
                'billing_cycle' => 'monthly',
                'is_unlimited' => true,
                'plan_type' => 'corporate',
                'status' => 'active',
                'features' => ['Dedicated Support', 'Free Installation', 'SLA Guarantee', 'Multiple Static IPs', 'Enterprise Router', 'Redundancy'],
                'max_devices' => 100,
                'overage_rate' => null,
            ],
        ];

        foreach ($servicePlans as $plan) {
            ServicePlan::firstOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }

        $this->command->info('Service plans created successfully!');
    }
}
