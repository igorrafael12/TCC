<?php
include '../config.php';

header('Content-Type: application/json');

$termo = $_GET['term'] ?? '';
$termo = "%$termo%";

try {
    $sql = "SELECT id, nome_livro FROM livros WHERE nome_livro LIKE :termo ORDER BY nome_livro LIMIT 20";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':termo', $termo);
    $stmt->execute();

    $resultados = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $resultados[] = [
            'id' => $row['id'],
            'text' => $row['nome_livro']
        ];
    }

    echo json_encode(['results' => $resultados]);
} catch (PDOException $e) {
    echo json_encode(['results' => [], 'erro' => $e->getMessage()]);
}
