<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Vitrin',
                'price' => 199.99,
                'stripe_price_id' => 'price_vitrin_monthly',
                'interval' => 'month',
                'features' => [
                    'Basic clinic profile',
                    'Up to 2 doctors',
                    'Basic appointment scheduling',
                    'Email support',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Masa',
                'price' => 399.99,
                'stripe_price_id' => 'price_masa_monthly',
                'interval' => 'month',
                'features' => [
                    'Enhanced clinic profile',
                    'Up to 5 doctors',
                    'Advanced appointment scheduling',
                    'Resource sharing',
                    'Priority email support',
                    'Basic analytics',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Vitrin+Masa',
                'price' => 699.99,
                'stripe_price_id' => 'price_vitrin_masa_monthly',
                'interval' => 'month',
                'features' => [
                    'Premium clinic profile',
                    'Unlimited doctors',
                    'Advanced appointment scheduling',
                    'Resource sharing',
                    'Priority support 24/7',
                    'Advanced analytics',
                    'Custom branding',
                    'API access',
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create([
                ...$plan,
                'slug' => Str::slug($plan['name']),
                'trial_days' => 7, // Default trial period
                'is_active' => true,
            ]);
        }

        // Create yearly plans with 20% discount
        foreach ($plans as $plan) {
            $yearlyPrice = round($plan['price'] * 12 * 0.8, 2); // 20% discount for yearly plans
            Plan::create([
                'name' => $plan['name'] . ' (Yearly)',
                'slug' => Str::slug($plan['name'] . ' yearly'),
                'stripe_price_id' => str_replace('monthly', 'yearly', $plan['stripe_price_id']),
                'price' => $yearlyPrice,
                'interval' => 'year',
                'features' => $plan['features'],
                'trial_days' => 7,
                'is_active' => true,
                'sort_order' => $plan['sort_order'] + 3,
            ]);
        }
    }
}
