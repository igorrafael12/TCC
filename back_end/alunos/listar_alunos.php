<?php
include '../config.php';

$erro = null;

if (isset($_GET['excluir_id'])) {
    $idExcluir = (int)$_GET['excluir_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
        $stmt->execute([':id' => $idExcluir]);
        header("Location: listar_alunos.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $erro = "Não é possível excluir este aluno porque existem empréstimos vinculados a ele.";
        } else {
            $erro = "Erro inesperado: " . htmlspecialchars($e->getMessage());
        }
    }
}

$alunos = $pdo->query("SELECT * FROM alunos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Lista de Alunos</title>
<style>
/* Mesma estilização dos livros */
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

<h1>Alunos cadastrados</h1>

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
    <?php foreach ($alunos as $aluno): ?>
    <tr>
      <td><?= $aluno['id'] ?></td>
      <td><?= htmlspecialchars($aluno['nome']) ?></td>
      <td>
        <a href="editar_aluno.php?id=<?= $aluno['id'] ?>" class="btn btn-editar">Editar</a>
        <a href="listar_alunos.php?excluir_id=<?= $aluno['id'] ?>" class="btn btn-excluir" onclick="return confirm('Confirma exclusão deste aluno?');">Excluir</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>
