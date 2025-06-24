<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: listar_emprestimos.php");
    exit;
}

$id = (int)$_GET['id'];
$erro = '';
$sucesso = '';

// Buscar dados do empréstimo para preencher o formulário
$stmt = $pdo->prepare("SELECT * FROM emprestimos WHERE id = :id");
$stmt->execute([':id' => $id]);
$emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emprestimo) {
    header("Location: listar_emprestimos.php");
    exit;
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alunos_id = $_POST['alunos_id'];
    $professores_id = $_POST['professores_id'];
    $livros_id = $_POST['livros_id'];
    $data_retirada = $_POST['data_retirada'];
    $data_devolucao = $_POST['data_devolucao'];

    $hoje = date('Y-m-d');

    if ($data_retirada < $hoje) {
        $erro = "Não é possível registrar empréstimos com data de retirada no passado.";
    } elseif ($data_devolucao < $data_retirada) {
        $erro = "A data de devolução não pode ser anterior à data de retirada.";
    } else {
        $sql = "UPDATE emprestimos SET alunos_id = :alunos_id, professores_id = :professores_id, livros_id = :livros_id,
                data_retirada = :data_retirada, data_devolucao = :data_devolucao WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $updated = $stmt->execute([
            ':alunos_id' => $alunos_id,
            ':professores_id' => $professores_id,
            ':livros_id' => $livros_id,
            ':data_retirada' => $data_retirada,
            ':data_devolucao' => $data_devolucao,
            ':id' => $id,
        ]);
        if ($updated) {
            $sucesso = "Empréstimo atualizado com sucesso!";
            // Atualiza $emprestimo para manter os dados na tela
            $emprestimo = [
                'alunos_id' => $alunos_id,
                'professores_id' => $professores_id,
                'livros_id' => $livros_id,
                'data_retirada' => $data_retirada,
                'data_devolucao' => $data_devolucao
            ];
        } else {
            $erro = "Erro ao atualizar empréstimo.";
        }
    }
}

// Buscar listas para selects
$alunos = $pdo->query("SELECT id, nome FROM alunos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$professores = $pdo->query("SELECT id, nome FROM professores ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$livros = $pdo->query("SELECT id, nome_livro FROM livros ORDER BY nome_livro")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Editar Empréstimo</title>
<style>
/* Estilo simples */
body { font-family: Arial, sans-serif; padding: 20px; }
form { max-width: 400px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px #ccc; }
label { display: block; margin-top: 10px; }
select, input[type="date"] { width: 100%; padding: 8px; margin-top: 5px; }
input[type="submit"] { margin-top: 15px; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
input[type="submit"]:hover { background-color: #45a049; }
.msg-erro { color: red; margin-top: 15px; }
.msg-sucesso { color: green; margin-top: 15px; }
a { display: inline-block; margin-top: 15px; color: #2196F3; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h1>Editar Empréstimo #<?= $id ?></h1>

<?php if ($erro): ?>
<p class="msg-erro"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<?php if ($sucesso): ?>
<p class="msg-sucesso"><?= htmlspecialchars($sucesso) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label for="alunos_id">Aluno:</label>
    <select name="alunos_id" id="alunos_id" required>
        <option value="">Selecione um aluno</option>
        <?php foreach ($alunos as $aluno): ?>
            <option value="<?= $aluno['id'] ?>" <?= ($emprestimo['alunos_id'] == $aluno['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($aluno['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="professores_id">Professor:</label>
    <select name="professores_id" id="professores_id" required>
        <option value="">Selecione um professor</option>
        <?php foreach ($professores as $professor): ?>
            <option value="<?= $professor['id'] ?>" <?= ($emprestimo['professores_id'] == $professor['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($professor['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="livros_id">Livro:</label>
    <select name="livros_id" id="livros_id" required>
        <option value="">Selecione um livro</option>
        <?php foreach ($livros as $livro): ?>
            <option value="<?= $livro['id'] ?>" <?= ($emprestimo['livros_id'] == $livro['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($livro['nome_livro']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="data_retirada">Data de Retirada:</label>
    <input type="date" name="data_retirada" id="data_retirada" required value="<?= htmlspecialchars($emprestimo['data_retirada']) ?>">

    <label for="data_devolucao">Data de Devolução:</label>
    <input type="date" name="data_devolucao" id="data_devolucao" required value="<?= htmlspecialchars($emprestimo['data_devolucao']) ?>">

    <input type="submit" value="Atualizar Empréstimo">
</form>

<a href="listar_emprestimos.php">Voltar para lista de empréstimos</a>

</body>
</html>
