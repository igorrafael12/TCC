<?php
include '../config.php';

$erro = null;

// Recebe o texto de pesquisa, se existir
$pesquisa = $_GET['pesquisa'] ?? '';

// Exclusão
if (isset($_GET['excluir_id'])) {
    $idExcluir = (int)$_GET['excluir_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM livros WHERE id = :id");
        $stmt->execute([':id' => $idExcluir]);
        header("Location: listar_livros.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $erro = "Não é possível excluir este livro porque existem empréstimos vinculados a ele.";
        } else {
            $erro = "Erro inesperado: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Busca com filtro de pesquisa
if ($pesquisa) {
    $stmt = $pdo->prepare("SELECT * FROM livros WHERE nome_livro LIKE :pesquisa ORDER BY nome_livro");
    $stmt->execute([':pesquisa' => "%$pesquisa%"]);
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $livros = $pdo->query("SELECT * FROM livros ORDER BY nome_livro")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Lista de Livros</title>
<!-- Fonte Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
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
    max-width: 900px;
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
    justify-content: center;
    gap: 10px;
  }
  input[type="text"] {
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    font-size: 16px;
    width: 300px;
  }
  button.pesquisar {
    background-color: #fcbf49;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    color: #222;
    font-size: 16px;
    box-shadow: 0 0 12px #fcbf49;
    transition: background-color 0.3s;
  }
  button.pesquisar:hover {
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
  .btn {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    color: #222;
    box-shadow: 0 0 8px #fcbf49;
    transition: background-color 0.3s;
    margin-right: 8px;
    display: inline-block;
  }
  .btn-editar {
    background-color: #fcbf49;
  }
  .btn-excluir {
    background-color: #d84315;
    color: #fff;
    box-shadow: 0 0 12px #d84315;
  }
  .btn:hover {
    filter: brightness(0.85);
  }
  .msg-erro {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 10px;
    border-radius: 4px;
    margin-top: 15px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
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
</style>
</head>
<body>
  <div class="container">
    <h1>Livros cadastrados</h1>

    <?php if ($erro): ?>
      <div class="msg-erro"><?= $erro ?></div>
    <?php endif; ?>

    <form method="get" action="">
      <input
        type="text"
        name="pesquisa"
        placeholder="Pesquisar por título..."
        value="<?= htmlspecialchars($pesquisa) ?>"
      />
      <button class="pesquisar" type="submit">Pesquisar</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($livros) > 0): ?>
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
        <?php else: ?>
          <tr><td colspan="3">Nenhum livro encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
  </div>
</body>
</html>
