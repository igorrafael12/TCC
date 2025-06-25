<?php
// Inclui o arquivo de configuração do banco de dados (conexão PDO, por exemplo)
include '../config.php';

// Função para buscar livros usando a API do Google Books
function searchGoogleBooks($query) {
    // Monta a URL da API com o termo pesquisado
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query);

    // Inicializa uma sessão cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // Define a URL a ser acessada
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Retorna a resposta como string

    $response = curl_exec($ch); // Executa a requisição
    curl_close($ch); // Fecha a conexão cURL

    return json_decode($response, true); // Decodifica o JSON em array associativo
}

// Verifica se o formulário foi enviado via POST com os campos obrigatórios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && isset($_POST['autor'])) {
    $titulo = $_POST['titulo']; // Obtém o título enviado
    $autor = $_POST['autor'];   // Obtém o autor enviado

    try {
        // Prepara o comando SQL para inserir no banco de dados
        $sql = "INSERT INTO livros (nome_livro, nome_autor) VALUES (:titulo, :autor)";
        $stmt = $pdo->prepare($sql); // Prepara a consulta
        $stmt->bindParam(':titulo', $titulo); // Vincula o título
        $stmt->bindParam(':autor', $autor);   // Vincula o autor
        $stmt->execute(); // Executa a inserção
        echo "<p>Livro cadastrado com sucesso!</p>";
    } catch (Exception $e) {
        // Mostra uma mensagem de erro, caso ocorra alguma exceção
        echo "<p>Erro ao cadastrar livro: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar e Cadastrar Livros</title>

    <!-- Fonte Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background-image: url('https://c4.wallpaperflare.com/wallpaper/446/712/946/minecraft-bookshelves-hd-wallpaper-preview.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        input, button {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 80%;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }

        button {
            background-color: #F9A825;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #F57F17;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: rgba(255, 255, 255, 0.9);
            margin: 15px auto;
            padding: 15px;
            border-radius: 10px;
            width: 80%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            text-align: left;
            color: black;
            font-family: 'Poppins', sans-serif;
        }

        img {
            max-width: 100px;
            border-radius: 5px;
            margin-top: 10px;
        }
        li p {
    font-size: 14px; /* diminui para 14px, ajuste como quiser */
    line-height: 1.4;
}
        .botao-voltar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2E7D32;
            color: white;
            padding: 10px 20px;
            width: auto;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            transition: background 0.3s;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        .botao-voltar:hover {
            background-color: #1B5E20;
        }
    </style>

    <script>
        function searchBooks() {
            const query = document.getElementById('searchInput').value;
            if (query.length < 3) {
                document.getElementById('results').innerHTML = '';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'buscar_livros.php?query=' + encodeURIComponent(query), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('results').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Buscar Livro</h2>
        <input type="text" id="searchInput" placeholder="Digite o nome ou ISBN" onkeyup="searchBooks()" required>
        <div id="results">
            <!-- Os resultados serão carregados aqui via AJAX -->
        </div>
    </div>

    <!-- Botão de Voltar -->
    <form>
        <button type="button" onclick="history.back()" class="botao-voltar">Voltar</button>
    </form>
</body>
</html>