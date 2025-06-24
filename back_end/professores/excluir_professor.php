<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header('Location: listar_professores.php');
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("DELETE FROM professores WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: listar_professores.php');
exit;
