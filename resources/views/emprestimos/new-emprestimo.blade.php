@extends('layouts.app')
@section('content') 
    @include('emprestimos.partials.search-script')
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
                    autocomplete="off"
                    >
                    
                <select
                    name="livro_id"
                    id="livro"
                    class="form-select"
                    required
                >
                    <option value="">
                        Selecione um livro
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Enviar
            </button>
        </form>
@endsection
