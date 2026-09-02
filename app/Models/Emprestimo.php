<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{

    protected $fillable = [
        'user_id',
        'livro_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'livro_id' => 'integer',
    ];

    /**
     * Retorna todos os empréstimos do usuário
     * @param int $user_id
     * @return Collection<int, Emprestimo>
     */
    public static function getAllFromUser(int $user_id): Collection
    {
        return SELF::where('user_id', $user_id)->get();
    }
    /**
     * Retorna todos os empréstimos atrelados ao livro de id referenciado
     * @param int $book_id
     * @return Collection<int, Emprestimo>
     */
    public static function getAllFromBook(int $book_id): Collection
    {
        return SELF::where('livro_id', $book_id)->get();
    }

    /**
     * Retorna o objeto Livro específico para este empréstimo
     * @return ?Livro
     */
    public function getLivro(): ?Livro
    {
        return Livro::where('id', $this->livro_id)->first();
    }

    /**
     * 
     * Retorna o usuário a que aquele empréstimo se reere
     * @return ?User
     */
    public function getUser(): ?User
    {
        return User::find($this->user_id);
    }
}
