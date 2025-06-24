<?php
include '../config.php';

$erro = null;

if (isset($_GET['excluir_id'])) {
    $idExcluir = (int)$_GET['excluir_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM livros WHERE id = :id");
        $stmt->execute([':id' => $idExcluir]);
        header("Location: listar_livros.php"); // Redireciona pra evitar reenvio do formulário
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $erro = "Não é possível excluir este livro porque existem empréstimos vinculados a ele.";
        } else {
            $erro = "Erro inesperado: " . htmlspecialchars($e->getMessage());
        }
    }
}

$livros = $pdo->query("SELECT * FROM livros ORDER BY nome_livro")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Lista de Livros</title>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; }
  table { border-collapse: collapse; width: 100%; margin-top: 10px;}
  th, td { border: 1px solid #ddd; padding: 8px; }
  th { background-color: #4CAF50; color: white; }
  .btn {
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    text-decoration: none;
    color: white;
  }
  .btn-editar { background-color: #2196F3; }
  .btn-excluir { background-color: #f44336; margin-left: 5px; }
  .msg-erro {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 10px;
    border-radius: 4px;
    margin-top: 15px;
  }
</style>
</head>
<body>

<h1>Livros cadastrados</h1>

<?php if ($erro): ?>
  <div class="msg-erro"><?= $erro ?></div>
<?php endif; ?>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Título</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($livros as $livro): ?>
    <tr>
      <td><?= $livro['id'] ?></td>
      <td><?= htmlspecialchars($livro['nome_livro']) ?></td>
      <td>
        <a href="editar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-editar">Editar</a>
        <a href="listar_livros.php?excluir_id=<?= $livro['id'] ?>" class="btn btn-excluir" onclick="return confirm('Confirma exclusão deste livro?');">Excluir</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>
