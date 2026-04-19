<?php

namespace Database\Seeders;

use App\Models\KpiVariable;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->create([
            'name' => 'Owner Coffee Shop',
            'email' => 'owner@coffee.local',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'daily_rate' => 0,
            'is_active' => true,
        ]);

        User::factory()->count(3)->create();

        User::query()->create([
            'name' => 'Staff Kasir',
            'email' => 'staff@coffee.local',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'daily_rate' => 120000,
            'is_active' => true,
        ]);

        $variables = [
            ['name' => 'Kebersihan', 'weight' => 30, 'is_active' => true],
            ['name' => 'Up-selling', 'weight' => 35, 'is_active' => true],
            ['name' => 'Disiplin', 'weight' => 35, 'is_active' => true],
        ];

        foreach ($variables as $variable) {
            KpiVariable::query()->create($variable);
        }
    }
}
