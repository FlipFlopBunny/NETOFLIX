<?php
    session_start();
    include_once('./conection.php');

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $passworrd = $_POST['password'];

        $sql = $conn->prepare("INSERT INTO users (nome, senha) VALUES (?,?)");//insere no banco de dados
        $sql->bind_param("ss", $username, $passworrd);

        if($sql->execute()){
            echo "usuário cadastrado com sucesso.";
        }else{
            echo "Erro ao cadastrae usuário: " . $conn->error;
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
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
<h1>Cadastro</h1>
    <form method="POST" action="cadastrar.php">
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit">Cadastrar</button>
    </form>
</div>

</main>

</body>
</html>