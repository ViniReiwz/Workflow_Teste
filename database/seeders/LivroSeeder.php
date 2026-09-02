<?php

namespace Database\Seeders;

use App\Models\Livro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class LivroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livros = [
            [
                'titulo' => 'Dom Casmurro',
                'autor' => 'Machado de Assis',
                'ISBN' => '9788535902775',
                'qtd_exemplares' => rand(1, 10),
                'categoria' => 'Romance',
            ],
            [
                'titulo' => 'O Cortiço',
                'autor' => 'Aluísio Azevedo',
                'ISBN' => '9788503010061',
                'qtd_exemplares' => rand(1, 10),
                'categoria' => 'Romance',
            ],
            [
                'titulo' => 'Vidas Secas',
                'autor' => 'Graciliano Ramos',
                'ISBN' => '9788501067347',
                'qtd_exemplares' => rand(1, 10),
                'categoria' => 'Ficção',
            ],
            [
                'titulo' => 'A Hora da Estrela',
                'autor' => 'Clarice Lispector',
                'ISBN' => '9788520926603',
                'qtd_exemplares' => rand(1, 10),
                'categoria' => 'Ficção',
            ],
            [
                'titulo' => 'O Hobbit',
                'autor' => 'J. R. R. Tolkien',
                'ISBN' => '9788595084742',
                'qtd_exemplares' => rand(1, 10),
                'categoria' => 'Fantasia',
            ],
            [
                'titulo' => '1984',
                'autor' => 'George Orwell',
                'ISBN' => '9788535914849',
                'qtd_exemplares' => rand(1, 10),
                'categoria' => 'Ficção Científica',
            ],
        ];

        foreach($livros as $livro) 
        {
            Livro::create($livro);
        }
    }
}
