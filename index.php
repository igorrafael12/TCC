<?php
// Inclui o arquivo de configuração do banco de dados
include './back_end/config.php';

/*
Armazena dados e pode retomar em outras paginas
*/
session_start(); // Inicia a sessão


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // CPF
    $password = $_POST['password'];

    // Consulta para verificar as credenciais do professor
    $sql = "SELECT * FROM professores WHERE cpf = :cpf"; // Alterado para CPF
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpf', $username); // Alterado para CPF
    $stmt->execute();
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o professor existe e se a senha está correta
    if (password_verify($password, $professor['senha'])) {
        // Armazena informações do professor na sessão
        $_SESSION['professor_id'] = $professor['id'];
        $_SESSION['professor_name'] = $professor['nome'];
        header("Location: /tcc/inicial.php"); // Redireciona para a página tcc
        exit();
    } else {
        $error = "CPF ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo Professor</title>
    <style>
        /* Estilos básicos para o formulário */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
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
        input[type="password"] {
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Bem-vindo Professor!</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <label for="username">CPF:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Entrar">
    </form>
</body>
</html>