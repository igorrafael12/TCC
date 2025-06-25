<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alunos_id = $_POST['alunos_id'];
    $professores_id = $_POST['professores_id'];
    $livros_id = $_POST['livros_id'];
    $data_retirada = $_POST['data_retirada'];
    $data_devolucao = $_POST['data_devolucao'];

    $hoje = date('Y-m-d');

    if ($data_retirada < $hoje) {
        echo "<p style='color: red;'>Não é possível registrar empréstimos com data de retirada no passado.</p>";
    } elseif ($data_devolucao < $data_retirada) {
        echo "<p style='color: red;'>A data de devolução não pode ser anterior à data de retirada.</p>";
    } else {
        $sql = "INSERT INTO emprestimos (alunos_id, professores_id, livros_id, data_retirada, data_devolucao) 
                VALUES (:alunos_id, :professores_id, :livros_id, :data_retirada, :data_devolucao)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':alunos_id', $alunos_id);
        $stmt->bindParam(':professores_id', $professores_id);
        $stmt->bindParam(':livros_id', $livros_id);
        $stmt->bindParam(':data_retirada', $data_retirada);
        $stmt->bindParam(':data_devolucao', $data_devolucao);

        if ($stmt->execute()) {
            echo "<p>Empréstimo registrado com sucesso!</p>";
        } else {
            echo "<p>Erro ao registrar empréstimo!</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Empréstimo</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images7.alphacoders.com/870/thumb-1920-870563.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #d0f0c0;
            line-height: 1.6;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: rgba(10, 50, 10, 0.5);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 150, 50, 0.7);
            width: 100%;
            max-width: 400px;
            color: #c6f5a1;
            z-index: 1;
        }

        form:hover {
            background-color: rgba(10, 50, 10, 0.65);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #a8d08d;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #5cb85c;
            border-radius: 5px;
            background-color: transparent;
            color: #234d20;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 0 10px #6fbf73;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #66bb6a;
            box-shadow: 0 0 20px #a5d6a7;
        }

        .botao-voltar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2e7d32;
            color: white;
            padding: 15px 20px;
            width: 150px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 0 10px #4caf50;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            font-family: Arial, sans-serif;
        }

        .botao-voltar:hover {
            background-color: #4caf50;
            box-shadow: 0 0 20px #81c784;
        }

        /* --- Estilo personalizado para o SELECT2 --- */
        .select2-container .select2-selection--single {
            background-color: transparent !important;
            border: 1px solid #5cb85c !important;
            height: 42px;
            color: #234d20 !important;
            font-size: 16px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #234d20 !important;
            line-height: 42px;
            padding-left: 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .select2-container--default .select2-results>.select2-results__options {
            background-color: rgba(255, 255, 255, 0.9);
            color: #234d20;
            font-size: 16px;
        }

        .select2-container--default .select2-results__option--highlighted {
            background-color: #a5d6a7;
            color: #1b3e1b;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <label for="alunos_id">Aluno:</label>
        <select name="alunos_id" id="alunos_id" required></select>

        <label for="professores_id">Professor:</label>
        <select name="professores_id" id="professores_id" required></select>

        <label for="livros_id">Livro:</label>
        <select name="livros_id" id="livros_id" required></select>

        <label for="data_retirada">Data de Retirada:</label>
        <input type="date" name="data_retirada" id="data_retirada" required>

        <label for="data_devolucao">Data de Devolução:</label>
        <input type="date" name="data_devolucao" id="data_devolucao" required>

        <input type="submit" value="Registrar Empréstimo">
    </form>

    <a href="javascript:history.back()" class="botao-voltar">Voltar</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#alunos_id').select2({
                placeholder: "Selecionar Aluno",
                width: '100%',
                ajax: {
                    url: 'buscar_alunos.php',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ term: params.term }),
                    processResults: data => ({ results: data.results }),
                    cache: true
                }
            });

            $('#professores_id').select2({
                placeholder: "Selecionar Professor",
                width: '100%',
                ajax: {
                    url: 'buscar_professores.php',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ term: params.term }),
                    processResults: data => ({ results: data.results }),
                    cache: true
                }
            });

            $('#livros_id').select2({
                placeholder: "Selecionar Livro",
                width: '100%',
                ajax: {
                    url: 'buscar_livros_emprestimo.php',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ term: params.term }),
                    processResults: data => ({ results: data.results }),
                    cache: true
                }
            });

            const dataRetirada = document.getElementById("data_retirada");
            const dataDevolucao = document.getElementById("data_devolucao");
            const hoje = new Date().toISOString().split("T")[0];

            dataRetirada.setAttribute("min", hoje);
            dataRetirada.addEventListener("change", function () {
                dataDevolucao.value = '';
                dataDevolucao.setAttribute("min", this.value);
            });
        });
    </script>
</body>
</html>
