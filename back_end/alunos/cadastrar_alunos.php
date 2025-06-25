<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $serie = trim($_POST['serie']);
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "E-mail inválido!";
        exit;
    }

    $sql = "INSERT INTO alunos (nome, serie, email) VALUES (:nome, :serie, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':serie', $serie);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "Aluno cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar aluno. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastro de Aluno</title>

<!-- Fonte Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    color: #333;
    line-height: 1.6;
    height: 100vh;
    background-image: url('https://media.tenor.com/TP12G5jWn24AAAAi/nerd.gif');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    justify-content: center;
    align-items: center;
}

form {
    background-color: rgba(255, 255, 255, 0.3); /* Mais transparente */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    width: 100%;
    max-width: 400px;
    color: #000;
    font-weight: 600;
}

label {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #F9A825;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
}

input[type="submit"]:hover {
    background-color: #F57F17;
}

.botao-voltar {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #F9A825;
    color: white;
    padding: 15px 20px;
    width: 150px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    transition: background-color 0.3s;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
}

.botao-voltar:hover {
    background-color: #F57F17;
}
</style>
</head>
<body>
    <form method="post" action="cadastrar_alunos.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <input type="submit" value="Cadastrar Aluno">
    </form>
    <a href="javascript:history.back()" class="botao-voltar">Voltar</a>
</body>
</html>
