<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Responsibility;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // Company::factory(10)->create(); // Seeder Company
        // Team::factory(5)->create(); // Seeder Team
        // Role::factory(50)->create(); // Seeder Role
        // Responsibility::factory(200)->create(); // Seeder Responsibility
        Employee::factory(1000)->create();
    }
}
