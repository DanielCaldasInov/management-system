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

    public function definition(): array
    {
        return [
            'is_customer' => $this->faker->boolean(70),
            'is_supplier' => $this->faker->boolean(40),

            'number' => $this->faker->unique()->randomNumber(5, true),

            'vat_number' => $this->faker->unique()->numerify('#########'),

            'name' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),

            'zip_code' => $this->faker->numerify('####-###'),

            'city' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'website' => $this->faker->url(),
            'notes' => $this->faker->sentence(),

            'country_id' => null,

            'gdpr_consent' => $this->faker->boolean(80),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
//TODO: Update when we have a countries table or an API
