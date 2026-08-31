@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">
        Livros - 
        <a href="{{ route('livros.edit', ['isbn' => 'new']) }}" class="btn btn-primary btn-sm">Adicionar livro</a>
    </h1>
    

    @if($livros->isEmpty())
        <div class="alert alert-info">
            Nenhum livro encontrado.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ISBN</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($livros as $livro)
                        <tr>
                            <td>{{ $livro->ISBN }}</td>
                            <td>{{ $livro->titulo }}</td>
                            <td>{{ $livro->autor }}</td>
                            <td>{{ $livro->categoria }}</td>
                            <td>
                               <a href="{{ route('livros.edit', ['isbn' => $livro->ISBN]) }}" class="btn btn-warning btn-sm"> 
                                    Editar 
                                </a> 
                                <form action="{{ route('livros.delete', ['isbn' => $livro->ISBN]) }}" method="POST" class="d-inline"> 
                                    @csrf @method('DELETE') 
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este livro?')"> 
                                        Remover 
                                    </button> 
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection