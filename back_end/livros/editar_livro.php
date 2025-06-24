<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header('Location: listar_livros.php');
    exit;
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_livro = $_POST['nome_livro'] ?? '';
    $stmt = $pdo->prepare("UPDATE livros SET nome_livro = :nome_livro WHERE id = :id");
    $stmt->execute([':nome_livro' => $nome_livro, ':id' => $id]);
    header('Location: listar_livros.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nome_livro FROM livros WHERE id = :id");
$stmt->execute([':id' => $id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "Livro não encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Livro</title>
</head>
<body>
<h2>Editar Livro</h2>
<form method="POST">
    <label for="nome_livro">Título:</label><br>
    <input type="text" name="nome_livro" id="nome_livro" value="<?= htmlspecialchars($livro['nome_livro']) ?>" required><br><br>
    <button type="submit">Salvar</button>
    <a href="listar_livros.php">Cancelar</a>
</form>
</body>
</html>
