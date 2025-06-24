<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header('Location: listar_livros.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM livros WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: listar_livros.php');
    exit;
} catch (PDOException $e) {
    if ($e->getCode() == '23000') { // Violação de chave estrangeira
        echo "<p style='color: red; font-weight: bold;'>
            Não é possível excluir este livro porque existem empréstimos vinculados a ele.
        </p>";
        echo '<p><a href="listar_livros.php">Voltar para a lista de livros</a></p>';
    } else {
        echo "<p>Erro inesperado: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
