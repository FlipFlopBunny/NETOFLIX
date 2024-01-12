<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'cinedb';

$conn = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_error()) {
    echo 'Erro de conexão fale com o administrador: ' . mysqli_connect_error();
}
?>