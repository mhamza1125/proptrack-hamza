<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            ['name' => 'Agent Alice',   'email' => 'alice@proptrack.test'],
            ['name' => 'Agent Bob',     'email' => 'bob@proptrack.test'],
        ];

        foreach ($agents as $data) {
            $agent = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            $agent->assignRole('agent');
        }
    }
}
