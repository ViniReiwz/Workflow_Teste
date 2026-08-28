<?php

namespace App\Models;

use App\Http\Requests\LivroStoreRequest;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class Livro extends Model
{
    protected $primaryKey = 'ISBN';
    
    public $incrementign = false;

    protected $keyType = 'string';

    protected $table = 'livros';

    protected $fillable = [
        'titulo',
        'categoria',
        'autor',
        ];

    protected $casts = [
        'ISBN' => 'string',
        'titulo' => 'string',
        'categoria' => 'string',
        'autor' => 'string',
    ];

    /**
     * Mutator para título - Armazena em maiúsculas
     * @return Attribute
     */
    protected function titulo()
    {
        return Attribute::make(set: fn($value) => strtoupper($value));
    }

    /**
     * Mutator para autor - Armazena em maiúsculas
     * @return Attribute
     */
    protected function autor()
    {
        return Attribute::make(set: fn($value) => strtoupper($value));
    }

    /**
     * Mutator para categoria - Armazena em minúsculas
     * @return Attribute
     */
    protected function categoria()
    {
        return Attribute::make(set: fn($value) => strtolower($value));
    }

    /**
     * Retorna todos os livros de uma categoria específica
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Collection<int, Livro>
     */
    public static function getAllFromCategory(string $category): Collection
    {
        return SELF::where('categoria', $category)->get();
    }

    /**
     * Retorna todos os livros de um autor específico
     * @param string $author
     * @return \Illuminate\Database\Eloquent\Collection<int, Livro>
     */
    public static function getAllFromAuthor(string $author): Collection
    {
        return SELF::where('autor', $author)->get();
    }

    /**
     * Lida com a persistência de um livro
     * @param LivroStoreRequest $request
     * @return void
     */
    public static function store(LivroStoreRequest $request)
    {
        /** @var Livro */
        $livro = SELF::findOrNew($request->input('ISBN'));
        $livro->titulo = $request->input('titulo');
        $livro->categoria = $request->input('categoria');
        $livro->autor = $request->input('autor');
        $livro->save();
    }

}