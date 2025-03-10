<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating coupons...');

        $items = [
            [
                'name' => 'Welcome Coupon',
                'description' => 'Welcome coupon for new customers',
                'code' => 'WELCOME',
                'type' => 'fixed',
                'value' => 5.00
            ],
            [
                'name' => 'Summer Sale',
                'description' => 'Summer sale coupon',
                'code' => 'SUMMER',
                'type' => 'percentage',
                'value' => 10.00,
                'start_date' => '2025-06-01 00:00:00',
                'end_date' => '2025-08-31 23:59:59'
            ],
            [
                'name' => 'Winter Sale',
                'description' => 'Winter sale coupon',
                'code' => 'WINTER',
                'type' => 'percentage',
                'value' => 15.00,
                'start_date' => '2025-12-01 00:00:00',
                'end_date' => '2025-12-31 23:59:59'
            ],
            [
                'name' => 'Black Friday',
                'description' => 'Black Friday coupon',
                'code' => 'BLACKFRIDAY',
                'type' => 'percentage',
                'value' => 20.00,
                'start_date' => '2025-11-27 00:00:00',
                'end_date' => '2025-11-30 23:59:59'
            ],
        ];

        foreach ($items as $item) {
            $this->command->info('Creating coupon: ' . $item['name']);
            \App\Models\Coupon::create($item);
        }

        $this->command->info('Coupons created successfully');
    }
}
