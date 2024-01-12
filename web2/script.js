document.addEventListener("DOMContentLoaded", function () {
    const apiKey = '8fa8be48';
    const movieList = document.getElementById("movie-list");

    // Função para carregar filmes na página
    function carregarFilmes(pesquisa) {
        if (!pesquisa) {
            // Exibe os filmes iniciais apenas se não houver pesquisa
            const apiUrl = `https://www.omdbapi.com/?s=batman&apikey=${apiKey}`; // Exemplo de pesquisa por "batman"
    
            fetch(apiUrl)
                .then((response) => response.json())
                .then((data) => {
                    if (data.Response === 'True') {
                        data.Search.forEach((element) => {
                            const item = document.createElement("div");
                            item.classList.add("item");
    
                            // Adiciona o ícone de favorito e define os atributos personalizados
                            item.innerHTML = `<img src="${element.Poster}" /><h2>${element.Title}</h2>
                                <p>Favoritar</p>
                                <i class="icon fas fa-heart" onclick="favoritarFilme(this, '${element.Title}', '${element.Poster}')"
                                    data-movie-title="${element.Title}" data-movie-poster="${element.Poster}"></i>`;
    
                            movieList.appendChild(item);
                        });
                    } else {
                        console.log('Nenhum Filme encontrado!');
                    }
                })
                .catch((error) => {
                    console.error('Erro ao buscar filmes:', error);
                });
        }
    }
    

    // Obtém a consulta de pesquisa da URL
    const urlParams = new URLSearchParams(window.location.search);
    const pesquisa = urlParams.get("pesquisa");

    carregarFilmes(pesquisa); // Chama a função apenas se houver pesquisa
});
