<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),

            'nis' => $this->faker->unique()->numerify('########'),
            'nisn' => $this->faker->unique()->numerify('##########'),

            'name' => $this->faker->name(),
            'class_room_id' => ClassRoom::inRandomOrder()->value('id'),

            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default testing password

            'avatar_path' => null,
            'phone_number' => $this->faker->phoneNumber(),

            'postal_code' => $this->faker->optional()->postcode(),
            'address' => $this->faker->optional()->address(),

            'village' => $this->faker->optional()->citySuffix(),
            'district' => $this->faker->optional()->city(),
            'city' => $this->faker->optional()->city(),
            'province' => $this->faker->optional()->state(),

            'parent_name' => $this->faker->optional()->name(),
            'parent_phone_number' => $this->faker->optional()->phoneNumber(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
