<?php
session_start();
include_once('./conection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $movie_title = isset($_POST['movie_title']) ? $_POST['movie_title'] : '';
    $movie_poster = isset($_POST['movie_poster']) ? $_POST['movie_poster'] : '';

    // Verifique se o filme já foi favoritado pelo usuário
    $checkFavorite = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND movie_title = ?"); 
	//prepare: metodo de preparacao para consulta protegendo a query de invasoes
    $checkFavorite->bind_param("is", $user_id, $movie_title);// "is" i=integer, s=string
	//bind_param: combate sql injections
    $checkFavorite->execute();//execucao da query na linha 11
    
    $result = $checkFavorite->get_result();
    $isFavorite = $result->num_rows > 0;//boolean

    if ($isFavorite) {
        $response = ['success' => false, 'message' => 'Este filme já está na sua lista de favoritos.'];
    } else {
        // Insira os dados do filme favorito no banco de dados
        $sql = $conn->prepare("INSERT INTO favorites (user_id, movie_title, movie_poster) VALUES (?, ?, ?)");
        $sql->bind_param("iss", $user_id, $movie_title, $movie_poster);

        if ($sql->execute()) {
            $response = ['success' => true, 'message' => 'Filme favoritado com sucesso.'];
        } else {
            $response = ['success' => false, 'message' => 'Erro ao favoritar o filme.'];
        }
    }
} else {
    $response = ['success' => false, 'message' => 'Usuário não autenticado.'];
}

// Envia a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);//passa resultado como arquivo .json
?>
