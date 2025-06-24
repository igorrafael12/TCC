<?php
include '../config.php';

// Lógica de exclusão
if (isset($_GET['excluir_id'])) {
    $idExcluir = intval($_GET['excluir_id']);

    $stmt = $pdo->prepare("DELETE FROM emprestimos WHERE id = :id");
    $stmt->bindParam(':id', $idExcluir);

    try {
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Empréstimo excluído com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao excluir o empréstimo.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro: não foi possível excluir. Verifique se há vínculos com outras tabelas.</p>";
    }
}

// Busca os empréstimos
$sql = "SELECT e.id, a.nome AS aluno, p.nome AS professor, l.nome_livro AS livro,
               e.data_retirada, e.data_devolucao
        FROM emprestimos e
        JOIN alunos a ON e.alunos_id = a.id
        JOIN professores p ON e.professores_id = p.id
        JOIN livros l ON e.livros_id = l.id
        ORDER BY e.data_retirada DESC";

$emprestimos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empréstimos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f7f7f7;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        .acoes {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            color: #fff;
        }
        .editar {
            background-color: #2196F3;
        }
        .excluir {
            background-color: #f44336;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<h1>Empréstimos Registrados</h1>

<table>
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Professor</th>
            <th>Livro</th>
            <th>Retirada</th>
            <th>Devolução</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($emprestimos) > 0): ?>
        <?php foreach ($emprestimos as $e): ?>
            <?php
                $hoje = date('Y-m-d');
                $atrasado = ($e['data_devolucao'] < $hoje) ? true : false;
            ?>
            <tr>
                <td><?= htmlspecialchars($e['aluno']) ?></td>
                <td><?= htmlspecialchars($e['professor']) ?></td>
                <td><?= htmlspecialchars($e['livro']) ?></td>
                <td><?= $e['data_retirada'] ?></td>
                <td>
                    <?= $e['data_devolucao'] ?>
                    <?php if ($atrasado): ?>
                        <span style="color: red; font-weight: bold;">(Atrasado)</span>
                    <?php endif; ?>
                </td>
                <td class="acoes">
                    <a href="editar_emprestimo.php?id=<?= $e['id'] ?>" class="btn editar">Editar</a>
                    <a href="?excluir_id=<?= $e['id'] ?>" class="btn excluir" onclick="return confirm('Confirma a exclusão deste empréstimo?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">Nenhum empréstimo registrado.</td>
        </tr>
    <?php endif; ?>
</tbody>
</table>

</body>
</html>
