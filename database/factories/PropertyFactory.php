<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    private static array $cities = [
        'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix',
        'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose',
    ];

    public function definition(): array
    {
        $type   = $this->faker->randomElement(PropertyType::cases());
        $status = $this->faker->randomElement(PropertyStatus::cases());

        $bedrooms  = in_array($type, [PropertyType::House, PropertyType::Apartment])
            ? $this->faker->numberBetween(1, 6)
            : null;

        $bathrooms = $bedrooms ? $this->faker->numberBetween(1, $bedrooms) : null;

        return [
            'user_id'        => User::factory(),
            'title'          => $this->faker->sentence(4),
            'description'    => $this->faker->paragraphs(3, true),
            'type'           => $type->value,
            'status'         => $status->value,
            'price'          => $this->faker->numberBetween(50_000, 2_500_000),
            'bedrooms'       => $bedrooms,
            'bathrooms'      => $bathrooms,
            'area'           => $this->faker->numberBetween(400, 8_000),
            'city'           => $this->faker->randomElement(self::$cities),
            'address'        => $this->faker->streetAddress(),
            'featured_image' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => PropertyStatus::Active->value]);
    }

    public function forAgent(User $agent): static
    {
        return $this->state(['user_id' => $agent->id]);
    }
}
