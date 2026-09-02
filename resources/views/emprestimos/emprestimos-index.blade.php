@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">
        Empréstimos
        {{-- <a href="{{ route('livros.edit', ['isbn' => 'new']) }}" class="btn btn-primary btn-sm">Adicionar livro</a> --}}
    </h1>
    

    @if($emprestimos->isEmpty())
        <div class="alert alert-info">
            Nenhum empréstimo encontrado.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        @if(Gate::allows('admin'))
                            <th>ID Usuário</th>
                        @endif
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoria</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($emprestimos as $emprestimo)
                        <tr>
                            @if(Gate::allows('admin'))
                                <td>{{ $emprestimo->user_id }}</td>
                            @endif
                            <td>{{ $emprestimo->getLivro()->titulo }}</td>
                            <td>{{ $emprestimo->getLivro()->autor }}</td>
                            <td>{{ $emprestimo->getLivro()->categoria }}</td>
                            <td> placeholder_situacao </td>
                            <td>
                                @include('emprestimos.partials.delete-btn', ['emprestimo' => $emprestimo])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
</div>
@endsection