<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@proptrack.test'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('admin');
    }
}
