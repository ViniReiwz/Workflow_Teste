```blade
@extends('layouts.app')

@section('content')

<form
    action="{{ $action }}"
    method="POST"
    name="livros_edit"
    id="generatedForm"
>
    @csrf

    <div class="row g-2 align-items-end">

        <div class="col-md-2">
            <label for="ISBN" class="form-label">
                ISBN <span class="text-danger">*</span>
            </label>

            <input
                id="ISBN"
                type="text"
                name="ISBN"
                class="form-control form-control-sm"
                value="{{ old('ISBN', $livro->ISBN ?? '') }}"
                required
            >
        </div>

        <div class="col-md-3">
            <label for="titulo" class="form-label">
                Título <span class="text-danger">*</span>
            </label>

            <input
                id="titulo"
                type="text"
                name="titulo"
                class="form-control form-control-sm"
                value="{{ old('titulo', $livro->titulo ?? '') }}"
                required
            >
        </div>

        <div class="col-md-3">
            <label for="autor" class="form-label">
                Nome do/a autor/a
            </label>

            <input
                id="autor"
                type="text"
                name="autor"
                class="form-control form-control-sm"
                value="{{ old('autor', $livro->autor ?? '') }}"
            >
        </div>

        <div class="col-md-2">
            <label for="categoria" class="form-label">
                Categoria
            </label>

            <input
                id="categoria"
                type="text"
                name="categoria"
                class="form-control form-control-sm"
                value="{{ old('categoria', $livro->categoria ?? '') }}"
            >
        </div>

        <div class="col-md-2">
            <label for="qtd_exemplares" class="form-label">
                Qtd. exemplares <span class="text-danger">*</span>
            </label>

            <input
                id="qtd_exemplares"
                type="number"
                name="qtd_exemplares"
                class="form-control form-control-sm"
                value="{{ old('qtd_exemplares', $livro->qtd_exemplares ?? 1) }}"
                min="0"
                required
            >
        </div>

    </div>

    <div class="mt-3">
        <button
            type="submit"
            class="btn btn-primary btn-sm"
        >
            Enviar
        </button>
    </div>

</form>

@endsection
```
