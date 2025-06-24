<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header('Location: listar_alunos.php');
    exit;
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $stmt = $pdo->prepare("UPDATE alunos SET nome = :nome WHERE id = :id");
    $stmt->execute([':nome' => $nome, ':id' => $id]);
    header('Location: listar_alunos.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nome FROM alunos WHERE id = :id");
$stmt->execute([':id' => $id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    echo "Aluno não encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Aluno</title>
</head>
<body>
<h2>Editar Aluno</h2>
<form method="POST">
    <label for="nome">Nome:</label><br>
    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($aluno['nome']) ?>" required><br><br>
    <button type="submit">Salvar</button>
    <a href="listar_alunos.php">Cancelar</a>
</form>
</body>
</html>
