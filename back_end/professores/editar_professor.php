<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header('Location: listar_professores.php');
    exit;
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $stmt = $pdo->prepare("UPDATE professores SET nome = :nome WHERE id = :id");
    $stmt->execute([':nome' => $nome, ':id' => $id]);
    header('Location: listar_professores.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nome FROM professores WHERE id = :id");
$stmt->execute([':id' => $id]);
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$professor) {
    echo "Professor não encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Professor</title>
</head>
<body>
<h2>Editar Professor</h2>
<form method="POST">
    <label for="nome">Nome:</label><br>
    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($professor['nome']) ?>" required><br><br>
    <button type="submit">Salvar</button>
    <a href="listar_professores.php">Cancelar</a>
</form>
</body>
</html>
