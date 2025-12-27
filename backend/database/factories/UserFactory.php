<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),

            'nip' => $this->faker->unique()->numerify('################'),
            'name' => $this->faker->name(),

            'email' => $this->faker->unique()->safeEmail(),

            'role' => $this->faker->randomElement(['bk', 'guru', 'staff']),
            'jabatan' => $this->faker->jobTitle(),

            'email_verified_at' => now(),

            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            'avatar_file_id' => null,
            'phone_number' => $this->faker->phoneNumber(),

            'is_available' => 1,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State khusus role BK
     */
    public function bk(): static
    {
        return $this->state(fn () => [
            'role' => 'bk',
            'jabatan' => 'Guru BK',
        ]);
    }

    /**
     * State khusus guru
     */
    public function guru(): static
    {
        return $this->state(fn () => [
            'role' => 'guru',
            'jabatan' => 'Guru Mata Pelajaran',
        ]);
    }

    /**
     * State khusus staff
     */
    public function staff(): static
    {
        return $this->state(fn () => [
            'role' => 'staff',
            'jabatan' => 'Staff Administrasi',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
