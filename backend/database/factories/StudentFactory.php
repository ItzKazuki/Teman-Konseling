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
        $withAddress = $this->faker->boolean(70);

        return [
            'id' => (string) Str::uuid(),

            'nis' => $this->faker->unique()->numerify('########'),
            'nisn' => $this->faker->unique()->numerify('##########'),

            'name' => $this->faker->name(),
            'class_room_id' => ClassRoom::inRandomOrder()->value('id'),

            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),

            'avatar_path' => null,
            'phone_number' => '62'.fake()->numerify('8#########'),

            'postal_code' => $withAddress ? $this->faker->numerify('#####') : null,
            'address' => $withAddress
                ? 'Jl. '.$this->faker->streetName().' No. '.$this->faker->numberBetween(1, 200)
                : null,

            'village' => $withAddress ? 'Kel. '.$this->faker->word() : null,
            'district' => $withAddress ? 'Kec. '.$this->faker->word() : null,
            'city' => $withAddress ? $this->faker->randomElement([
                'Bandung',
                'Jakarta Selatan',
                'Surabaya',
                'Yogyakarta',
                'Semarang',
                'Malang',
            ]) : null,
            'province' => $withAddress ? $this->faker->randomElement([
                'Jawa Barat',
                'DKI Jakarta',
                'Jawa Tengah',
                'Jawa Timur',
                'DI Yogyakarta',
            ]) : null,

            'parent_name' => $this->faker->name(),
            'parent_phone_number' => fake()->numerify('62'.'8#########'),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
