<?php
if (isset($_GET['query'])) {
    // Pega o texto que a pessoa digitou
    $query = urlencode($_GET['query']);

    // Monta o link para consultar o Google Books
    $url = "https://www.googleapis.com/books/v1/volumes?q=$query";

    // Pega a resposta do Google
    $response = file_get_contents($url);

    // Transforma o resultado em um formato que o PHP entende
    $data = json_decode($response, true);

    // Se tiver resultados
    if (!empty($data['items'])) {
        foreach ($data['items'] as $item) {
            $volume = $item['volumeInfo'];
            $title = $volume['title'] ?? 'Sem título';
            $authors = isset($volume['authors']) ? implode(', ', $volume['authors']) : 'Autor desconhecido';
            $description = $volume['description'] ?? 'Descrição não disponível.';
            $thumbnail = $volume['imageLinks']['thumbnail'] ?? '';

            echo "<li>";
            echo "<strong>$title</strong><br>";
            echo "<em>Autor(es): $authors</em><br>";
            echo "<p>$description</p>";
            if ($thumbnail) {
                echo "<img src='$thumbnail' alt='Capa do livro'><br>";
            }

            // Formulário para cadastrar o livro
            echo "<form method='POST' action=''>
                    <input type='hidden' name='titulo' value=\"" . htmlspecialchars($title, ENT_QUOTES) . "\">
                    <input type='hidden' name='autor' value=\"" . htmlspecialchars($authors, ENT_QUOTES) . "\">
                    <button type='submit'>Cadastrar</button>
                  </form>";

            echo "</li>";
        }
    } else {
        echo "<li>Nenhum livro encontrado.</li>";
    }
}
?>
