<?php
include '../config.php'; // Inclui o arquivo de configuração para conexão com o banco de dados

// Consulta SQL para obter todos os alunos
$sql = "SELECT id, nome, serie, email FROM alunos ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #F9A825;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Lista de Alunos Cadastrados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Série</th>
            <th>Email</th>
        </tr>
        <?php foreach ($alunos as $aluno): ?>
            <tr>
                <td><?php echo htmlspecialchars($aluno['id']); ?></td>
                <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                <td><?php echo htmlspecialchars($aluno['serie']); ?></td>
                <td><?php echo htmlspecialchars($aluno['email']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
