<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"><!-- icones -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NETOFLIX</title>
   <script src="./script.js" defer></script>
	<link rel="icon" type="image/x-icon" href="3Dicon.png">
</head>
<body>

	<!-- Cabecalho -->
	<header>
		<img class="pop_icon" src="3Dicon.png">
		<a href="index.php" class="title"><h1 class="title">NETOFLIX</h1></a>
		<form class="forma" method="GET">
			<input name="pesquisa" placeholder="Digite o nome do filme" />
			<button type="submit">Pesquisar</button>
		</form>
		
		<?php
session_start(); //usuario precisa estar logado para favoritar filmes
include_once('./conection.php');

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    // O usuário está autenticado, não mostra os links de cadastro e entrada
    // Links para a página de filmes e sair (logout)
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Usuário';
    echo '<p class="header_links">Bem-vindo, ' . $username . '!</p>';
    echo '<a href="./favoritos.php" class="header_links"><img class="icon" src="heart_icon.png" title="Favoritos" alt="Favoritos"></a>';
    echo '<a href="./sair.php" class="header_links"><img class="icon" src="logout_icon.png" title="Sair" alt="Sair"></a>';
} else {
    // O usuário não está autenticado, mostra os links de cadastro e entrada
    echo '<a href="./cadastrar.php" class="header_links"> Cadastre-se </a>';
    echo '<a href="./logar.php" class="header_links"> Entrar</a>';
}

?>
		
		
	</header>
	
	

	<main>
    <div class="lista" id="movie-list">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pesquisa'])) {
            $apiKey = '8fa8be48';
            $pesquisa = $_GET['pesquisa']; //o que o usuario digitar

            if (empty($pesquisa)) {
                echo "Preencha o campo!";
            } else {
                $apiUrl = "https://www.omdbapi.com/?s=" . urlencode($pesquisa) . "&apikey=" . $apiKey;
				//link gera varios .json, cada filme tem seu proprio .json
                $response = file_get_contents($apiUrl);

                if ($response === false) {
                    echo "Erro ao fazer a solicitação para a API.";
                } else {
                    $data = json_decode($response, true);
                    if (isset($data) && $data['Response'] == 'False') {
                        echo "Nenhum Filme encontrado!";
                    } elseif (isset($data) && isset($data['Search'])) {
                        foreach ($data['Search'] as $element) {
                            echo '<div class="item">';
                            echo '<img src="' . $element['Poster'] . '" />';
                            echo '<h2>' . $element['Title'] . '</h2>';
                            echo '<p>Ano de Lançamento: ' . $element['Year'] . '</p>';
                            echo '<p>Tipo: ' . $element['Type'] . '</p>';
                            echo '<p>Favoritar</p>';
                            echo '<i class="icon fas fa-heart" onclick="favoritarFilme(this, \'' . $element['Title'] . '\', \'' . $element['Poster'] . '\')"></i>';
                            echo '</div>';
                        }
                    }
                }
            }
        }
        ?>
    </div>
	</main>
		
	<!--FOOTER-->
	<footer>
		<p>ícone 3D de <a href="https://pikkovia.com/listing-tag/movie/">Pikkovia</a></p>
		<a href="https://www.flaticon.com/free-icons/logout" title="logout icons">Logout icons created by Pixel perfect - Flaticon</a>
		<a href="https://www.flaticon.com/free-icons/heart" title="heart icons">Heart icons created by Freepik - Flaticon</a>
	</footer>
	
    <script>
        // Função para favoritar um filme
        function favoritarFilme(icon, movieTitle, moviePoster) {
            console.log('Icon clicked:', icon);
            console.log('Movie Title:', movieTitle);
            console.log('Movie Poster:', moviePoster);
            // Verificando se o usuário está autenticado antes de permitir o favoritismo
            if ('<?php echo isset($_SESSION['authenticated']) ? $_SESSION['authenticated'] : ''; ?>' === '1') {
                icon.classList.toggle("favoritado"); // Alterna a classe para destacar que o filme foi favoritado

                // Fazendo uma solicitação AJAX para favoritar o filme
                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'favoritar.php', true);//abre favoritar.php
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');//define o tipo do coneteudo para dados de formulario
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                // Exibe a mensagem de sucesso ao usuário
                                alert(response.message);//$response do favoritar.php
                            } else {
                                // Exibe a mensagem de erro ao usuário
                                alert(response.message);
                            }
                        } else {
                            // Exibe uma mensagem de erro ao usuário em caso de erro de conexão
                            alert('Erro ao fazer a solicitação para o servidor.');
                        }
                    }
                };
                xhr.send('movie_title=' + encodeURIComponent(movieTitle) + '&movie_poster=' + encodeURIComponent(moviePoster));
				//codifica dados do filme para url para serem tratados no favoritar.php
            } else {
                // O usuário não está autenticado, você pode exibir uma mensagem ou redirecioná-lo para a página de login aqui
                alert('Você precisa estar autenticado para favoritar filmes. Faça o login primeiro.');
            }
        }
    </script>
</body>
</html>
