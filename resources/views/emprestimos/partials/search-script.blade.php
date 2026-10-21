<script>
    document.addEventListener('DOMContentLoaded', function () {

        const busca = document.getElementById('busca-livro');
        const lista = document.getElementById('lista-livros');
        const livroId = document.getElementById('livro_id');
        const form = busca.closest('form');
        const url = "{{ route('emprestimos.busca') }}";

        let timeout;
        let controller;

        function mostrarMensagem(texto) {
            lista.innerHTML = `<div class="list-group-item text-muted">${texto}</div>`;
            lista.style.display = 'block';
        }

        function carregarLivros(termo = '') {

            if (controller) controller.abort();
            controller = new AbortController();

            fetch(`${url}?busca=${encodeURIComponent(termo)}`, {
                signal: controller.signal,
                headers: { 'Accept': 'application/json' }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erro na requisição');
                    return response.json();
                })
                .then(livros => {

                    lista.innerHTML = '';

                    if (livros.length === 0) {
                        mostrarMensagem('Nenhum livro encontrado');
                        return;
                    }

                    livros.forEach(livro => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = `${livro.titulo} — ISBN: ${livro.ISBN}`;

                        item.addEventListener('click', function () {
                            busca.value = livro.titulo;   // mostra o título no campo
                            livroId.value = livro.id;     // guarda o ID para o envio
                            lista.style.display = 'none';
                        });

                        lista.appendChild(item);
                    });

                    lista.style.display = 'block';
                })
                .catch(error => {
                    if (error.name === 'AbortError') return;
                    console.error(error);
                    mostrarMensagem('Erro ao carregar livros');
                });
        }

        // Ao digitar: filtra por título ou ISBN
        busca.addEventListener('input', function () {
            clearTimeout(timeout);
            livroId.value = ''; // se o texto mudou, a escolha anterior deixa de valer

            const termo = this.value.trim();

            timeout = setTimeout(() => carregarLivros(termo), 300);
        });

        // Ao clicar/focar no campo: mostra a lista (alfabética se estiver vazio)
        busca.addEventListener('focus', function () {
            carregarLivros(this.value.trim());
        });

        // Fecha a lista ao clicar fora
        document.addEventListener('click', function (e) {
            if (!busca.contains(e.target) && !lista.contains(e.target)) {
                lista.style.display = 'none';
            }
        });

        // Impede enviar sem ter escolhido um livro da lista
        form.addEventListener('submit', function (e) {
            if (!livroId.value) {
                e.preventDefault();
                busca.focus();
                mostrarMensagem('Selecione um livro da lista');
            }
        });
    });
</script>