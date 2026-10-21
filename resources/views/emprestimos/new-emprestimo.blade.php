@extends('layouts.app')
@section('content') 
    <h1>Novo empréstimo</h1>
    <form action="{{ route('emprestimos.create') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="busca-livro" class="form-label">
                Pesquisar livro
                </label>
                
                <input
                    type="text"
                    id="busca-livro"
                    class="form-control mb-2"
                    placeholder="Digite o título ou ISBN..."
                    autocomplete="on"
                    >
                    
                <input
                    type="hidden"
                    name="livro_id"
                    id="livro_id"
                >
                <div
                    id="lista-livros"
                    class="list-group position-absolute w-100 shadow"
                    style="z-index: 1000; max-height: 250px; overflow-y: auto; display: none;"
                ></div>
            </div>

            <button type="submit" class="btn btn-primary">
                Enviar
            </button>
        </form>
        @include('emprestimos.partials.search-script')
@endsection
