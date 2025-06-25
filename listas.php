<!-- listas.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Listas</title>
    <!-- Fonte Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('https://wallpapercat.com/w/full/f/2/5/41291-3000x2000-desktop-hd-whiplash-background-photo.jpg') no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            padding: 60px 20px;
            color: #fff;
        }

        h1 {
            margin-bottom: 40px;
            font-size: 36px;
            text-shadow: 0 0 10px rgba(0,0,0,0.7);
            font-weight: 600;
        }

        .btn {
            display: block;
            margin: 15px auto;
            padding: 14px 24px;
            width: 280px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .btn:hover {
            background-color: rgba(0, 0, 0, 0.9);
            transform: scale(1.03);
        }

        .btn-voltar {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 28px;
            background-color: rgba(183, 28, 28, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-voltar:hover {
            background-color: rgba(142, 0, 0, 0.9);
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <h1>Listas</h1>

    <a href="back_end/alunos/listar_alunos.php" class="btn">Lista de Alunos</a>
    <a href="back_end/professores/listar_professores.php" class="btn">Lista de Professores</a>
    <a href="back_end/livros/listar_livros.php" class="btn">Lista de Livros</a>
    <a href="back_end/emprestimo/listar_emprestimo.php" class="btn">Lista de Empréstimos</a>
    <a href="relatorios/grafico_livros.php" class="btn">Gráfico de Empréstimos</a>

    <a href="inicial.php" class="btn-voltar">Voltar</a>
</body>
</html>
