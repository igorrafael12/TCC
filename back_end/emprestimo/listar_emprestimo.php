<?php
include '../config.php';

// Recebe os filtros enviados pelo formulário
$filtroTexto = $_GET['filtro_texto'] ?? '';
$filtroTipo = $_GET['filtro_tipo'] ?? 'nome'; // padrão: nome do aluno
$filtroAtrasado = isset($_GET['filtro_atrasado']) ? true : false;

// Monta a query base com joins
$sql = "SELECT e.id, a.nome AS aluno, p.nome AS professor, l.nome_livro AS livro,
               e.data_retirada, e.data_devolucao
        FROM emprestimos e
        JOIN alunos a ON e.alunos_id = a.id
        JOIN professores p ON e.professores_id = p.id
        JOIN livros l ON e.livros_id = l.id
        WHERE 1=1 ";

// Parâmetros para o prepare
$params = [];

// Filtro por texto e tipo
if ($filtroTexto !== '') {
    if ($filtroTipo === 'nome') {
        $sql .= " AND a.nome LIKE :filtro_texto ";
    } elseif ($filtroTipo === 'professor') {
        $sql .= " AND p.nome LIKE :filtro_texto ";
    } elseif ($filtroTipo === 'livro') {
        $sql .= " AND l.nome_livro LIKE :filtro_texto ";
    }
    $params[':filtro_texto'] = '%' . $filtroTexto . '%';
}

// Filtro por empréstimos atrasados
if ($filtroAtrasado) {
    $hoje = date('Y-m-d');
    $sql .= " AND e.data_devolucao < :hoje ";
    $params[':hoje'] = $hoje;
}

$sql .= " ORDER BY e.data_retirada DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Empréstimos</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background: url('https://wallpapercat.com/w/full/f/2/5/41291-3000x2000-desktop-hd-whiplash-background-photo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
        }

        .container {
            background: rgba(20, 20, 20, 0.85);
            padding: 25px;
            border-radius: 12px;
            max-width: 1200px;
            margin: auto;
            box-shadow: 0 0 30px rgba(255, 183, 77, 0.9);
        }

        h1 {
            color: #fcbf49;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 0 0 10px #fcbf49;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        input[type="text"] {
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
            width: 220px;
        }

        select {
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
        }

        label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 16px;
            user-select: none;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        button {
            background-color: #fcbf49;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            color: #222;
            font-size: 16px;
            box-shadow: 0 0 12px #fcbf49;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #f9a825;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(30, 30, 30, 0.85);
            box-shadow: 0 0 15px rgba(255, 183, 77, 0.8);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 183, 77, 0.3);
            text-align: left;
        }

        th {
            background: #fcbf49;
            color: #222;
            font-weight: 700;
            text-shadow: 0 0 6px #b47c00;
        }

        tr:hover {
            background: rgba(252, 191, 73, 0.3);
        }

        .acoes a {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            color: #222;
            box-shadow: 0 0 8px #fcbf49;
            transition: background-color 0.3s;
            margin-right: 8px;
        }

        .editar {
            background-color: #fcbf49;
        }

        .excluir {
            background-color: #d84315;
            color: #fff;
            box-shadow: 0 0 12px #d84315;
        }

        .acoes a:hover {
            filter: brightness(0.85);
        }

        .atrasado {
            color: #f44336;
            font-weight: bold;
            text-shadow: 0 0 5px #b71c1c;
        }

        .btn-voltar {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            background-color: #fcbf49;
            color: #222;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 0 15px #fcbf49;
            transition: background-color 0.3s;
        }

        .btn-voltar:hover {
            background-color: #f9a825;
        }

        @media (max-width: 720px) {
            form {
                flex-direction: column;
                gap: 12px;
            }

            input[type="text"], select, button {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Empréstimos Registrados</h1>

        <form method="get" action="">
            <input type="text" name="filtro_texto" placeholder="Pesquisar..." value="<?= htmlspecialchars($filtroTexto) ?>" />

            <select name="filtro_tipo">
                <option value="nome" <?= $filtroTipo === 'nome' ? 'selected' : '' ?>>Aluno</option>
                <option value="professor" <?= $filtroTipo === 'professor' ? 'selected' : '' ?>>Professor</option>
                <option value="livro" <?= $filtroTipo === 'livro' ? 'selected' : '' ?>>Livro</option>
            </select>

            <label>
                <input type="checkbox" name="filtro_atrasado" <?= $filtroAtrasado ? 'checked' : '' ?> />
                Atrasados
            </label>

            <button type="submit">Filtrar</button>
        </form>

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
                    <?php foreach ($emprestimos as $e): 
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
                                    <span class="atrasado">(Atrasado)</span>
                                <?php endif; ?>
                            </td>
                            <td class="acoes">
                                <a href="editar_emprestimo.php?id=<?= $e['id'] ?>" class="editar">Editar</a>
                                <a href="?excluir_id=<?= $e['id'] ?>" class="excluir" onclick="return confirm('Confirma a exclusão deste empréstimo?')">Excluir</a>
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

        <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>
