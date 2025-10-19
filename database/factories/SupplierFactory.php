<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = \App\Models\User::factory()->create([
            'role_id' => \App\Models\Role::where('slug', 'supplier')->first()->id
        ]);

        return [
            'user_id' => $user->id,
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'contact_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'commercial_record' => $this->faker->unique()->numerify('CR#####'),
            'payment_term_days' => 30,
            'created_at' => now(),
            'updated_at' => now(),
            'is_active' => true,

        ];
    }
}
