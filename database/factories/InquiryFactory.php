<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'name'        => $this->faker->name(),
            'email'       => $this->faker->safeEmail(),
            'phone'       => $this->faker->optional()->phoneNumber(),
            'message'     => $this->faker->paragraph(),
            'status'      => $this->faker->randomElement(InquiryStatus::cases())->value,
        ];
    }

    public function asNew(): static
    {
        return $this->state(['status' => InquiryStatus::New->value]);
    }
}
