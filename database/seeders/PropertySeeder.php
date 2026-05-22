<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::role('agent')->get();

        if ($agents->isEmpty()) {
            $this->command->warn('No agents found. Run AgentSeeder first.');
            return;
        }

        $agents->each(function (User $agent) {
            // 8 properties per agent, 5 active
            Property::factory()
                ->count(5)
                ->active()
                ->forAgent($agent)
                ->create()
                ->each(function (Property $property) {
                    Inquiry::factory()->count(rand(1, 4))->create([
                        'property_id' => $property->id,
                    ]);
                });

            Property::factory()
                ->count(3)
                ->forAgent($agent)
                ->create();
        });
    }
}
