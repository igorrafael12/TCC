<?php
if (!isset($_GET['query']) || strlen(trim($_GET['query'])) < 3) {
    exit;
}

$query = urlencode($_GET['query']);
$url = "https://www.googleapis.com/books/v1/volumes?q=" . $query;

$response = file_get_contents($url);
$data = json_decode($response, true);

if (!isset($data['items'])) {
    echo "<p>Nenhum livro encontrado.</p>";
    exit;
}

foreach ($data['items'] as $item) {
    $volumeInfo = $item['volumeInfo'];
    $titulo = $volumeInfo['title'] ?? 'Sem título';
    $autores = isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Desconhecido';
    $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? '';

    echo "<li>";
    echo "<strong>Título:</strong> $titulo<br>";
    echo "<strong>Autor:</strong> $autores<br>";
    if ($thumbnail) {
        echo "<img src='$thumbnail' alt='Capa do livro'><br>";
    }
    // Botão para cadastrar
    echo "<form method='POST' action='' style='margin-top: 10px;'>";
    echo "<input type='hidden' name='titulo' value=\"" . htmlspecialchars($titulo, ENT_QUOTES) . "\">";
    echo "<input type='hidden' name='autor' value=\"" . htmlspecialchars($autores, ENT_QUOTES) . "\">";
    echo "<button type='submit'>Cadastrar</button>";
    echo "</form>";
    echo "</li>";
}
