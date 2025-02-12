<?php

namespace Database\Seeders;

use App\Enum\RoleCode;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lefteris = User::whereEmail('lefteris@test.com')->first();

        if ($lefteris) {
            $lefteris->roles()->attach(RoleCode::merchant);
        }
    }
}
