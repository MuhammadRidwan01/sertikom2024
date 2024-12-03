<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'judul_buku'   => $this->faker->sentence(3), // Judul buku acak
                'penulis'      => $this->faker->name,        // Nama penulis acak
                'tahun_terbit' => $this->faker->year,        // Tahun acak
                'kategori'     => $this->faker->randomElement([1, 2, 3, 4, null]), // Acak kategori atau null
                'status'       => $this->faker->randomElement(['tersedia', 'dipinjam']), // Status acak
                'deskripsi'    => $this->faker->paragraph,   // Deskripsi acak
                'harga'        => $this->faker->numberBetween(50000, 500000), // Harga acak antara Rp50.000 - Rp500.000
        ];
    }
}
