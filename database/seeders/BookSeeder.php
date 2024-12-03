<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run()
    {
        // Generate 50 data buku menggunakan factory
        Book::factory()->count(50)->create();
    }
}
