<?php
include '../config.php'; // Inclui o arquivo de configuração, que provavelmente contém a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebendo os dados do formulário
    $nome = trim($_POST['nome']);
    $serie = trim($_POST['serie']);
    $email = trim($_POST['email']);
    
    // Verificação de formato de e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "E-mail inválido!";
        exit;
    }

    // Preparando a consulta SQL para inserir os dados na tabela 'alunos'
    $sql = "INSERT INTO alunos (nome, serie, email) VALUES (:nome, :serie, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':serie', $serie);
    $stmt->bindParam(':email', $email);

    // Executando a consulta e verificando se foi bem-sucedida
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aluno</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
        padding: 0;
        margin: 0;
        height: 100vh; /* Altura da tela inteira */
        background-image: url('https://media.tenor.com/TP12G5jWn24AAAAi/nerd.gif'); /* Link direto do GIF */
        background-size: cover; /* Faz o GIF cobrir toda a área da tela */
        background-position: center; /* Centraliza o GIF */
        background-repeat: no-repeat; /* Evita a repetição do GIF */
        display: flex;
        justify-content: center;
        align-items: center;
    }
    form {
        background-color: rgba(255, 255, 255, 0.8); /* Fundo branco com transparência */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
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
    }
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #F9A825; /* Tom de amarelo mais escuro */
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    input[type="submit"]:hover {
        background-color: #F57F17; /* Tom ainda mais escuro de amarelo */
    }
    .botao-voltar {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #F9A825; /* Mesma cor do botão de envio */
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
}
.botao-voltar:hover {
    background-color: #F57F17; /* Mesmo hover do botão principal */
}
</style>

</head>
<body>
    <form method="post" action="cadastrar_alunos.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br>
        
        <label for="serie">Série:</label>
        <input type="text" id="serie" name="serie" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <input type="submit" value="Cadastrar Aluno">
    </form>
    <a href="javascript:history.back()" class="botao-voltar">Voltar</a>
</body>
</html>
