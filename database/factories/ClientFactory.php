<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeClient = $this->faker->randomElement([1, 2]);
        $gender = $this->faker->randomElement(['M', 'F']);
        
        if ($typeClient === 1) {
            // Cliente normal
            $firstName = $this->faker->firstName($gender === 'M' ? 'male' : 'female');
            $lastName = $this->faker->lastName();
            $fullName = $firstName . ' ' . $lastName;
            $document = $this->faker->unique()->numerify('########');
            $documentType = 'DNI';
        } else {
            // Empresa
            $firstName = null;
            $lastName = null;
            $fullName = $this->faker->company() . ' ' . $this->faker->randomElement(['SAC', 'SRL', 'EIRL']);
            $document = '20' . $this->faker->unique()->numerify('#########');
            $documentType = 'RUC';
            $gender = null;
        }

        return [
            'name' => $firstName,
            'surname' => $lastName,
            'full_name' => $fullName,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'type_client' => $typeClient,
            'type_document' => $documentType,
            'n_document' => $document,
            'gender' => $gender,
            'birth_date' => $typeClient === 1 ? $this->faker->date('Y-m-d', '-18 years') : null,
            'address' => $this->faker->address(),
            'ubigeo_distrito' => $this->faker->numerify('#####'),
            'ubigeo_provincia' => $this->faker->numerify('####'),
            'ubigeo_region' => $this->faker->numerify('##'),
            'distrito' => $this->faker->city(),
            'provincia' => $this->faker->state(),
            'region' => $this->faker->state(),
            'state' => $this->faker->randomElement([0, 1]),
        ];
    }

    /**
     * Indicate that the client is a normal client.
     */
    public function normal()
    {
        return $this->state(function (array $attributes) {
            $gender = $this->faker->randomElement(['M', 'F']);
            $firstName = $this->faker->firstName($gender === 'M' ? 'male' : 'female');
            $lastName = $this->faker->lastName();
            
            return [
                'name' => $firstName,
                'surname' => $lastName,
                'full_name' => $firstName . ' ' . $lastName,
                'type_client' => 1,
                'type_document' => 'DNI',
                'n_document' => $this->faker->unique()->numerify('########'),
                'gender' => $gender,
                'birth_date' => $this->faker->date('Y-m-d', '-18 years'),
            ];
        });
    }

    /**
     * Indicate that the client is a company.
     */
    public function company()
    {
        return $this->state(function (array $attributes) {
            $companyName = $this->faker->company() . ' ' . $this->faker->randomElement(['SAC', 'SRL', 'EIRL']);
            
            return [
                'name' => null,
                'surname' => null,
                'full_name' => $companyName,
                'type_client' => 2,
                'type_document' => 'RUC',
                'n_document' => '20' . $this->faker->unique()->numerify('#########'),
                'gender' => null,
                'birth_date' => null,
            ];
        });
    }

    /**
     * Indicate that the client is active.
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 1,
            ];
        });
    }

    /**
     * Indicate that the client is inactive.
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 0,
            ];
        });
    }
}
