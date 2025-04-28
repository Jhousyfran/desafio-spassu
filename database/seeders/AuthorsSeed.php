<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('authors')->insert([
            [
                'name' => 'J.R.R. Tolkien',
            ],
            [
                'name' => 'George R.R. Martin',
            ],
            [
                'name' => 'Isaac Asimov',
            ],
            [
                'name' => 'Arthur C. Clarke',
            ],
            [
                'name' => 'William Shakespeare ',
            ],
            [
                'name' => 'Miguel de Cervantes',
            ],
            [
                'name' => 'Mark Twain',
            ],
            [
                'name' => 'Machado de Assis ',
            ]
        ]);
    }
}
