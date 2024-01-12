<?php
// Inicie a sessão (caso não tenha sido iniciada)
session_start();

// Limpe todas as variáveis de sessão
$_SESSION = array();

// Encerre a sessão
session_destroy();

header("Location: index.php");
exit;
?>
