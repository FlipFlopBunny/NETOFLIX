<?php
session_start();
include_once('./conection.php');

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    header("Location: index.php"); //
    exit;
} else {

    $mensagem = "";

    if (isset($_POST['submit'])) {//quando o usuario clica no botao submit
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Consulta para verificar se as credenciais são válidas
        $sql = $conn->prepare("SELECT id FROM users WHERE nome = ? AND senha = ?");
        $sql->bind_param("ss", $username, $password);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            // Credenciais válidas, o usuário está autenticado
            $row = $result->fetch_assoc();//
            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Redirecione para a página de filmes
            exit;
        } else {
            // Credenciais inválidas, exibir mensagem de erro
            $mensagem = "Credenciais inválidas. Por favor, verifique seu nome de usuário e senha.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log in</title>
	<link rel="stylesheet" href="forms.css">
	<link rel="icon" type="image/x-icon" href="3Dicon.png">
</head>

<body>
	<!-- Cabecalho -->
	<header>
		<img class="pop_icon" src="3Dicon.png">
		<a href="index.php" class="title"><h1 class="title">NETOFLIX</h1></a>
	</header>

	<main>
	<div id="formulario">
    <h1>Entrar</h1>
    <form method="POST" action="logar.php">
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit">Entrar</button>
        <?php if (!empty($mensagem)) : ?>
            <p><?php echo $mensagem; ?></p>
        <?php endif; ?>
    </form>
	</div>
<img class="popcorn" src="3Dicon.png">
	</main>

</body>

</html>
