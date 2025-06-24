<?php
include '../config.php';

if (!isset($_GET['id'])) {
    header('Location: listar_alunos.php');
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: listar_alunos.php');
exit;
