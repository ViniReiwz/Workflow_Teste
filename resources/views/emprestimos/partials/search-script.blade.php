<script>
    document.addEventListener('DOMContentLoaded', function () {

        const busca = document.getElementById('busca-livro');
        const select = document.getElementById('livro');

        let timeout;

        busca.addEventListener('input', function () {

            // Cancela a requisição agendada anteriormente
            clearTimeout(timeout);

            const termo = this.value.trim();
            // Não pesquisa se tiver menos de 2 caracteres
            if (termo.length < 2) {
                select.innerHTML =
                    '<option value="">Digite pelo menos 2 caracteres</option>';

                return;
            }

            // Aguarda 300ms antes de fazer a pesquisa
            timeout = setTimeout(() => {
            const url =  "{{ route('emprestimos.busca') }}"
                fetch(`${url}?busca=${encodeURIComponent(termo)}`)
                    .then(response => response.json())
                    .then(livros => {
                        // Limpa as opções anteriores
                        select.innerHTML =
                            '<option value="">Selecione um livro</option>';

                        // Adiciona os livros encontrados
                        livros.forEach(livro => {

                            const option = document.createElement('option');

                            option.value = livro.id;

                            option.textContent =
                                `${livro.titulo} — ISBN: ${livro.ISBN}`;

                            select.appendChild(option);
                        });

                        // Caso não encontre nenhum livro
                        if (livros.length === 0) {

                            select.innerHTML =
                                '<option value="">Nenhum livro encontrado</option>';
                        }
                    });
            }, 300);
        });
    });
</script>