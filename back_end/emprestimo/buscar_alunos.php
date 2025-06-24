<?php
include '../config.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$term = $_GET['term'] ?? '';
$term = "%$term%";

$sql = "SELECT id, nome FROM alunos WHERE nome LIKE :term ORDER BY nome LIMIT 20";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':term', $term);
$stmt->execute();

$resultados = [];

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $resultados[] = [
        'id' => $row['id'],
        'text' => $row['nome']
    ];
}

echo json_encode(['results' => $resultados]);
