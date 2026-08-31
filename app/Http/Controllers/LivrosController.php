<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroStoreRequest;
use App\Models\Livro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LivrosController extends Controller
{
    /**
     * Exibe todos os livros do banco de dados
     * @return View
     */
    public function index(): View
    {
        $all_books = Livro::all();
        return view('livros-index', ['livros' => $all_books]);
    }

    /**
     * Armazena o livro, seja uma edição ou o registro de um novo
     * @param LivroStoreRequest $request
     * @return RedirectResponse
     */
    public function store(LivroStoreRequest $request): RedirectResponse
    {
        Livro::handleStore($request);
        return redirect()->route('livros.index')->with('alert-success', 'Livro' . $request->input('titulo') . ' registrado com sucesso');
    }

    /**
     * Retorna a view para a edição de um livro existente ou adição de um novo
     * @param string $ISBN
     * @return View
     */
    public function edit(string $ISBN): View
    {
        $livro = Livro::find($ISBN);
    
        $action = route('livros.update');

        if(!isset($livro))
        {
            $action = route('livros.create');
        }


        return view('edit-livro-form', ['action' => $action, 'livro' => $livro]);
    }

    /**
     * Remove um livro do banco de dados
     * @param string $ISBN
     * @return RedirectResponse
     */
    public function delete(string $ISBN)
    {
        $livro = Livro::find($ISBN);
        $livroName = $livro->titulo;
        if($livro->delete())
        {
            return redirect()->back()->with('alert-warning', 'Livro \'' . $livroName . '\' removido com sucesso.');
        }
        else 
        {
            return redirect()->back()->with('alert-danger', 'Não foi possível remover o livro');
        }
    }
}
