<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Livro;
use Uspdev\Workflow\Workflow;

class EmprestimoController extends Controller
{
    /**
     * Faz a busca de um livro no banco de dados através de seu título ou ISBN,
     * retornando o JsonResponse para o frontEnd ao realizar um novo empréstimo
     * @param Request $request
     * @return JsonResponse
     */
    public function searchBook(Request $request): JsonResponse
    {
        $search = $request->input('busca');
        $livros = Livro::where('titulo', 'like', "%{$search}%")->orWhere('ISBN', 'like', "%{$search}%")->get([
            'id',
            'titulo',
            'ISBN'
        ]);

        return response()->json($livros);
    }

    /**
     * Exibe o formulário de empréstimo de livros
     * @return View
     */
    public function showCreateForm(): View
    {
        return view('emprestimos.new-emprestimo');
    }
    
    /**
     * Adiciona um novo empréstimo ao banco de dados, e faz os devidos ajustes na quantidade de 
     * exemplares do livro
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request): RedirectResponse
    {
        $livro_id = (int) $request->input('livro_id');

        $livro = Livro::where('id', $livro_id)->first();
        if($livro->handBook())
            {
                $emprestimo = Emprestimo::create([
                    'user_id' => Auth()->user()->id,
                    'livro_id' => $livro_id
                ]);
                
                Workflow::start('emprestimo_livro_simples', $emprestimo);

                return redirect()->route('emprestimos.fromUser')->with('alert-success', 'Empréstimo do livro \'' . $livro->titulo . '\' realizado com sucesso');
            }
            else
            {
                return redirect()->back()->with('alert-danger', 'Não há exemplares disponíveis para empréstimo');
            }
    }

    /**
     * Remove um empréstimo do banco de dados, a partir de seu ID
     * @param int $emprestimo_id
     * @return RedirectResponse
     */
    public function delete(int $emprestimo_id): RedirectResponse
    {
        $emprestimo = Emprestimo::find($emprestimo_id);
        if ($emprestimo) {
            $livro = $emprestimo->getLivro();
            $livro->retrieveBook();
            $emprestimo->delete();
            return redirect()->back()->with('alert-success','\'' . $livro->titulo . '\' devolvido com sucesso !');
        }
        return redirect()->back()->with('alert-danger', 'Empréstimo não encontrado');
    }

    /**
     * Exibe todos os empréstimos existentes
     * @return View
     */
    public function index(): View
    {
        $all_emprestimos = Emprestimo::all();
        return view('emprestimos.emprestimos-index', ['emprestimos' => $all_emprestimos]);
    }

    /**
     * Exibe todos os empréstimos do usuário atual
     * @return View
     */
    public function showUserEmprestimos(): View
    {
        $user_emprestimos = Emprestimo::getAllFromUser(Auth()->user()->id);
        return view('emprestimos.emprestimos-index', ['emprestimos' => $user_emprestimos]);
    }
}
