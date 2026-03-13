<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entity>
 */
class EntityFactory extends Factory
{
    protected $model = Entity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isCustomer = $this->faker->boolean(70);
        $isSupplier = $this->faker->boolean(40);

        if (! $isCustomer && ! $isSupplier) {
            $isCustomer = true;
        }

        static $number = 1;

        return [
            'number' => $number++,
            'is_customer' => $isCustomer,
            'is_supplier' => $isSupplier,
            'vat_number' => $this->faker->unique()->numerify('#########'),
            'name' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'website' => $this->faker->url(),
            'notes' => $this->faker->optional()->paragraph(),
            'gdpr_consent' => $this->faker->boolean(80),
        ];
    }
}
