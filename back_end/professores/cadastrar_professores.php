<?php
include '../config.php';

$mensagem = "";
$mensagemTipo = ""; // 'sucesso' ou 'erro'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $senha = $_POST['senha'];
    $email = trim($_POST['email']);

    if (strlen($cpf) != 11 || !preg_match('/^[0-9]{11}$/', $cpf)) {
        $mensagem = "CPF inválido! Digite 11 dígitos numéricos.";
        $mensagemTipo = "erro";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido!";
        $mensagemTipo = "erro";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM professores WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $mensagem = "Já existe um professor cadastrado com este CPF!";
            $mensagemTipo = "erro";
        } else {
            $sql = "INSERT INTO professores (nome, cpf, senha, email) VALUES (:nome, :cpf, :senha, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $mensagem = "Professor cadastrado com sucesso!";
                $mensagemTipo = "sucesso";
            } else {
                $mensagem = "Erro ao cadastrar professor. Tente novamente.";
                $mensagemTipo = "erro";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Professor</title>
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
            color: #fff;
            height: 100vh;
            background-image: url('https://c4.wallpaperflare.com/wallpaper/570/599/660/breaking-bad-heisenberg-walter-white-tv-wallpaper-preview.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 255, 0, 0.4);
            width: 100%;
            max-width: 400px;
        }
        .mensagem {
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            font-size: 18px;
        }
        .sucesso {
            background-color: #8BC34A;
            color: #000;
        }
        .erro {
            background-color: #f44336;
            color: white;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
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
            background-color: #8BC34A;
            color: #000;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        input[type="submit"]:hover {
            background-color: #689F38;
        }
        .botao-voltar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #8BC34A;
            color: #000;
            padding: 12px 20px;
            width: 150px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }
        .botao-voltar:hover {
            background-color: #689F38;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?= $mensagemTipo ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="cadastrar_professores.php">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required maxlength="11" pattern="\d{11}" title="Digite 11 dígitos numéricos">

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <input type="submit" value="Cadastrar Professor">
        </form>
    </div>

    <button onclick="history.back()" class="botao-voltar">Voltar</button>
</body>
</html>
