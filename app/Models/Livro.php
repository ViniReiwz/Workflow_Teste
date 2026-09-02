<?php

namespace App\Models;

use App\Http\Requests\LivroStoreRequest;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Emprestimo;
use Ramsey\Collection\Collection;

class Livro extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'ISBN';
    
    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'livros';

    protected $fillable = [
        'titulo',
        'categoria',
        'autor',
        'qtd_exemplares',
        ];

    protected $casts = [
        'ISBN' => 'string',
        'titulo' => 'string',
        'categoria' => 'string',
        'autor' => 'string',
        'qtd_exemplares' => 'integer',
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
     * @return Collection<int, Livro>
     */
    public static function getAllFromCategory(string $category): Collection
    {
        return SELF::where('categoria', $category)->get();
    }

    /**
     * Retorna todos os livros de um autor específico
     * @param string $author
     * @return Collection<int, Livro>
     */
    public static function getAllFromAuthor(string $author): Collection
    {
        return SELF::where('autor', $author)->get();
    }

    /**
     * Lida com a persistência de um livro
     * @param LivroStoreRequest $request
     * @return Livro
     */
    public static function handleStore(LivroStoreRequest $request): Livro
    {
        /** @var Livro */
        $livro = SELF::findOrNew($request->input('ISBN'));
        $livro->ISBN = $request->input('ISBN');
        $livro->titulo = $request->input('titulo');
        $livro->categoria = $request->input('categoria') ?? '- Não informada -';
        $livro->autor = $request->input('autor') ?? '- Não inforamdo -';
        $livro->qtd_exemplares = $request->input('qtd_exemplares') ?? 0;
        $livro->save();

        return $livro;
    }

    /**
     * Verifica se ainda há exemplares deste livro para serem emprestados
     * @return bool
     */
    public function hasRemanining(): bool
    {
        return ($this->qtd_exemplares > 0);
    }

    /**
     * Empresta o livro, se possível, decrementeando a quantidade de exemplares disponíveis
     * retornando 'true' caso o empréstimo tenha sido realizado com sucesso, ou 'false' caso 
     * contrário
     * @return bool
     */
    public function handBook(): bool
    {
        if($this->hasRemanining())
        {
            $this->qtd_exemplares--;
            $this->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Retorna um livro, incrementeando a quantidade de exemplares disponíveis
     * @return void
     */
    public function retrieveBook()
    {
        $this->qtd_exemplares++;
        $this->save();
    }

    /**
     * Verifica se o livro está emprestado atualmente
     * @return bool
     */
    private function isHanded()
    {
        $emprestimos = Emprestimo::getAllFromBook($this->id);
        return !$emprestimos->isEmpty();
    }

    /**
     * Remove um livro do banco de dados (caso não esteja emprestado)
     * @return bool
     */
    public function delete(): bool
    {
        $isHanded = $this->isHanded();
        if($isHanded) { return false; }
        else { return parent::delete(); }
    }

}