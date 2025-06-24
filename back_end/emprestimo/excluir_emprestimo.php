<?php
include '../config.php';

if (isset($_GET['id'])) {
    $idExcluir = intval($_GET['id']);
    $sql = "DELETE FROM emprestimos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idExcluir);

    if ($stmt->execute()) {
        header('Location: listar_emprestimos.php?msg=excluido');
        exit;
    } else {
        echo "Erro ao excluir empréstimo.";
    }
} else {
    header('Location: listar_emprestimos.php');
    exit;
}
