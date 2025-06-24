<?php
include '../config.php';

$erro = null;

if (isset($_GET['excluir_id'])) {
    $idExcluir = (int)$_GET['excluir_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM professores WHERE id = :id");
        $stmt->execute([':id' => $idExcluir]);
        header("Location: listar_professores.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $erro = "Não é possível excluir este professor porque existem empréstimos vinculados a ele.";
        } else {
            $erro = "Erro inesperado: " . htmlspecialchars($e->getMessage());
        }
    }
}

$professores = $pdo->query("SELECT * FROM professores ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Lista de Professores</title>
<style>
/* Igual dos anteriores */
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

<h1>Professores cadastrados</h1>

<?php if ($erro): ?>
  <div class="msg-erro"><?= $erro ?></div>
<?php endif; ?>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($professores as $professor): ?>
    <tr>
      <td><?= $professor['id'] ?></td>
      <td><?= htmlspecialchars($professor['nome']) ?></td>
      <td>
        <a href="editar_professor.php?id=<?= $professor['id'] ?>" class="btn btn-editar">Editar</a>
        <a href="listar_professores.php?excluir_id=<?= $professor['id'] ?>" class="btn btn-excluir" onclick="return confirm('Confirma exclusão deste professor?');">Excluir</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>
