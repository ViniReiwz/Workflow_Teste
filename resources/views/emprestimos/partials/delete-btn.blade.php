<form action="{{ route('emprestimos.delete', ['emprestimo_id' => $emprestimo->id]) }}" method="POST" class="d-inline"> 
    @csrf @method('DELETE') 
    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Tem certeza que deseja devolver este livro?')"> 
        Devolver 
    </button> 
</form>