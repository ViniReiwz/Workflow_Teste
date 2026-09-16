<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Livro;
use Illuminate\Support\Collection;
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
                
                $object = Workflow::start('emprestimo_livro_simples', $emprestimo);

                // Aplica a transitção para que os bibliotecários sejam notificados, e já passa o pedido para análise.
                $object->apply('tr_enviar', user: Auth()->user());

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
        if ($emprestimo) 
        {
            $livro = $emprestimo->getLivro();
            $livro->retrieveBook();
            // TODO - Faz sentido isso ?  (mas não dá pra deletar por conta de history)
            // $workflowObj = Workflow::find($emprestimo);
            // $workflowObj->delete();
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
        return view('emprestimos.emprestimos-show', ['emprestimos' => $all_emprestimos, 'fromOthers' => true]);
    }

    /**
     * Exibe todos os empréstimos do usuário atual
     * @return View
     */
    public function showUserEmprestimos(): View
    {
        $user_emprestimos = Emprestimo::getAllFromUser(Auth()->user()->id);
        return view('emprestimos.emprestimos-show', ['emprestimos' => $user_emprestimos]);
    }

    /**
     * Exibe os empréstimos no qual o usuário têm um papel a desempenhar
     * @return View
     */
    public function showAntendimentos(): View
    {
        $workflowObjects = Workflow::getUserRelatedObjects(Auth()->user());
        
        /** @var Collection<int, Emprestimo> */
        $emprestimos = collect();

        foreach($workflowObjects as $workflowObject)
        {
            $emprestimo = $workflowObject->object;
            if(isset($emprestimo)){$emprestimos->push($emprestimo);}
        }

        return view('emprestimos.emprestimos-show', ['emprestimos' => $emprestimos, 'fromOthers' => true]);
    }
}
