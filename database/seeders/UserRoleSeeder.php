<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Searching for user with email 'george@pagonoudis.gr'...");
        $user = User::whereEmail('george@pagonoudis.gr')->first();
        if ($user) {
            $this->command->info("User found! With name: " . $user->name);
        } else {
            $this->command->info("User not found!");
            return;
        }

        $this->command->info("Searching for role with name 'Admin'...");
        $role = Role::where('name->en', 'Admin')->first();
        if ($role) {
            $this->command->info("Role found! With name: {$role->name}");
        } else {
            $this->command->info("Role not found!");
            return;
        }

        $this->command->info("Attaching role to user...");
        $user->roles()->attach($role->id);

        $this->command->info("Role attached to user!");
    }
}
