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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-image: url('https://wallpapercat.com/w/full/f/2/5/41291-3000x2000-desktop-hd-whiplash-background-photo.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: #f0e6d2;
        margin: 0;
        padding: 40px 20px;
        backdrop-filter: brightness(0.6);
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #f9c468;
        text-shadow: 0 0 10px rgba(0,0,0,0.6);
    }

    #filtro-nome {
        display: block;
        margin: 0 auto 25px;
        padding: 12px 18px;
        width: 80%;
        max-width: 400px;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        background-color: rgba(255, 255, 255, 0.15);
        color: #f9eccc;
        box-shadow: 0 0 10px rgba(249, 197, 109, 0.4);
        outline: none;
        transition: background-color 0.3s ease;
    }

    #filtro-nome::placeholder {
        color: #e0c9a7;
    }

    #filtro-nome:focus {
        background-color: rgba(255, 255, 255, 0.25);
    }

    table {
        border-collapse: collapse;
        width: 90%;
        margin: 0 auto;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.6);
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
    }

    th {
        background-color: rgba(255, 215, 0, 0.2);
        color: #ffe082;
        font-weight: 600;
        border-bottom: 2px solid #bfa540;
    }

    td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-editar {
        background-color: #f9a825;
        color: #000;
    }

    .btn-editar:hover {
        background-color: #f57f17;
    }

    .btn-excluir {
        background-color: #c62828;
        color: white;
        margin-left: 8px;
    }

    .btn-excluir:hover {
        background-color: #b71c1c;
    }

    .msg-erro {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 12px;
        border-radius: 4px;
        margin: 20px auto;
        width: 90%;
        max-width: 600px;
        text-align: center;
        font-weight: bold;
    }

    .botao-voltar {
        display: block;
        margin: 40px auto 0;
        padding: 12px 24px;
        background-color: #795548;
        color: #fff;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }

    .botao-voltar:hover {
        background-color: #5d4037;
    }
</style>
</head>
<body>

<h1>Alunos cadastrados</h1>

<input type="text" id="filtro-nome" placeholder="Pesquisar por nome..." onkeyup="filtrarTabela()" />

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

<a href="../../listas.php" class="botao-voltar">Voltar</a>

<script>
function filtrarTabela() {
    const input = document.getElementById("filtro-nome");
    const filtro = input.value.toLowerCase();
    const linhas = document.querySelectorAll("table tbody tr");

    linhas.forEach(linha => {
        const nome = linha.cells[1].textContent.toLowerCase();
        linha.style.display = nome.includes(filtro) ? "" : "none";
    });
}
</script>

</body>
</html>
