<?php
session_start();
include_once('./conection.php');

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

    // Consulta os filmes favoritos do usuário a partir do banco de dados
    $getFavorites = $conn->prepare("SELECT movie_title, movie_poster FROM favorites WHERE user_id = ?");
    $getFavorites->bind_param("i", $user_id);
    $getFavorites->execute();

    $result = $getFavorites->get_result();
} else {
    echo 'Você precisa estar autenticado para ver seus filmes favoritos. Faça o login primeiro.';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmes Favoritos</title>
	<link rel="stylesheet" href="favoritos.css">
	<link rel="icon" type="image/x-icon" href="3Dicon.png">
</head>
<body>

<!-- Cabecalho -->
	<header>
		<img class="pop_icon" src="3Dicon.png">
		<a href="index.php" class="title"><h1 class="title">NETOFLIX</h1></a>
		<h1 id="h1_favoritos">Filmes Favoritos</h1>
	</header>

<?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
    

    
    <ul class="lista">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="item">
                <img src="<?= $row['movie_poster'] ?>" />
                <h2 class="lista"><?= $row['movie_title'] ?></h2>
                <a href="javascript:void(0);" onclick="confirmDelete('<?= urlencode($row['movie_title']) ?>')" class="lista">Remover</a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>

<script>
function confirmDelete(movieTitle) {
    var decodedTitle = decodeURIComponent(movieTitle).replace(/\+/g, ' '); // Substitui o sinal de "+" por espaço
    var result = confirm("Tem certeza de que deseja remover o filme '" + decodedTitle + "' dos favoritos?");
    if (result) {
        // Se o usuário confirmar, redirecione para a página de remoção do filme
        window.location.href = 'remover_filme.php?movie_title=' + movieTitle;
    }
}
</script>

</body>
</html>