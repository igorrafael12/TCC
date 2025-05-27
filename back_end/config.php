<?php
$host = 'localhost';  // ou o IP do seu servidor MySQL
$dbname = 'biblioteca';
$username = 'root';  // substitua com o seu nome de usuário do MySQL
$password = '';      // substitua com a sua senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
