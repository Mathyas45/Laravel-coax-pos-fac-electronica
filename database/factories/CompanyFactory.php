<?php
namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'razon_social' => $this->faker->company,
            'razon_social_comercial' => $this->faker->companySuffix,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'n_document' => $this->faker->unique()->numerify('20#########'),
            'birth_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'address' => $this->faker->address,
            'urbanizacion' => $this->faker->word,
            'cod_local' => $this->faker->bothify('###-???'),
            'ubigeo_distrito' => $this->faker->numberBetween(10000, 99999),
            'ubigeo_provincia' => $this->faker->numberBetween(1000, 9999),
            'ubigeo_region' => $this->faker->numberBetween(10, 99),
            'distrito' => $this->faker->city,
            'provincia' => $this->faker->state,
            'region' => $this->faker->state,
        ];
    }
}
