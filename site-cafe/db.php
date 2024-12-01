<?php
$host = "localhost";
$user = "admin";
$password = "admin";
$dbname = "loja_virtual_cafe";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>


