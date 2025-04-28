<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::created([
            [
                'title' => 'O Senhor dos Anéis',
                'subtitle' => 'A Sociedade do Anel',
                'publisher' => 'HarperCollins',
                'edition' => 1,
                'year_of_publication' => 1954,
                'price' => 39.90,
            ],
            [
                'title' => 'O Senhor dos Anéis',
                'subtitle' => 'As Duas Torres',
                'publisher' => 'HarperCollins',
                'edition' => 1,
                'year_of_publication' => 1954,
                'price' => 39.90,
            ],
            [
                'title' => 'O Senhor dos Anéis',
                'subtitle' => 'O Retorno do Rei',
                'publisher' => 'HarperCollins',
                'edition' => 1,
                'year_of_publication' => 1955,
                'price' => 39.90,
            ],
        ]);
    }
}
