    @extends('layouts.app')

    @section('content')

        <h1 class="mb-4">
            {{ $emprestimo->getLivro()->titulo }} - {{ $emprestimo->getLivro()->autor }}
            @if(Gate::allows('bibliotecario'))
            - Código do empréstimo: {{ $emprestimo->id }}
            @endif
        </h1>
        @include('uspdev-workflow::object.show.partials.show-obj-cntnt')
    @endsection