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
    if ($professor && password_verify($password, $professor['senha'])) {
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bem-vindo Professor</title>

    <!-- Fonte Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('https://images6.alphacoders.com/138/1382518.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #222;
        }

        .container {
            background: rgba(255, 255, 255, 0.15);
            padding: 40px 60px 40px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 440px;
            color: #222;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        h2 {
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 28px;
            text-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 18px;
            font-weight: 600;
            color: #111;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 20px;
            border: 1.8px solid rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.15);
            color: #111;
            font-size: 18px;
            font-weight: 600;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #333;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: rgba(255, 255, 255, 0.25);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .esqueci-senha {
            margin-top: 15px;
            display: block;
            font-size: 18px;
            color:rgb(3, 180, 9);
            text-decoration: none;
            font-weight: 500;
        }

        .esqueci-senha:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff4444;
            margin-bottom: 20px;
            font-weight: 600;
            text-shadow: 0 0 3px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h2>Bem-vindo Professor!</h2>
            
            <?php if (isset($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <label for="username">CPF:</label>
            <input type="text" name="username" id="username" placeholder="Digite seu CPF" required />

            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" placeholder="Digite sua senha" required />

            <input type="submit" value="Entrar" />

            <a href="recuperar_senha.php" class="esqueci-senha">Esqueci minha senha</a>
        </form>
    </div>
</body>
</html>
