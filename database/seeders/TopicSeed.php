<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('topics')->insert([
            [
                'name' => 'Ficção Científica',
            ],
            [
                'name' => 'Ficção Fantástica',
            ],
            [
                'name' => 'Romance',
            ],
            [
                'name' => 'Drama',
            ],
            [
                'name' => 'Aventura',
            ],
            [
                'name' => 'Terror',
            ],
            [
                'name' => 'Mistério',
            ],
            [
                'name' => 'Suspense',
            ],
            [
                'name' => 'Comédia',
            ],
            [
                'name' => 'Biografia',
            ],
            [
                'name' => 'História',
            ],
            [
                'name' => 'Autoajuda',
            ],
            [
                'name' => 'Religião',
            ],
            [
                'name' => 'Filosofia',
            ],
        ]);
    }
}
