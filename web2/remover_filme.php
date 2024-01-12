<?php
session_start();
include_once('./conection.php');

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] && isset($_GET['movie_title'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $movie_title = isset($_GET['movie_title']) ? $_GET['movie_title'] : '';

    // Remove o filme da lista de favoritos do usuário
    $removeFavorite = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND movie_title = ?");
    $removeFavorite->bind_param("is", $user_id, $movie_title);
    if ($removeFavorite->execute()) {
        header('Location: favoritos.php');
        exit();
    } else {
        echo 'Erro ao remover o filme dos favoritos.';
    }
} else {
    echo 'Você precisa estar autenticado para remover filmes dos favoritos. Faça o login primeiro.';
}
?>
