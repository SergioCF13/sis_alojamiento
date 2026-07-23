<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'adminV1@example.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('12345678'),
                'role' => 'Administrador',
            ]
        );
    }
}
