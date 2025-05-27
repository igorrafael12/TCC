<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Itens</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('https://wallpapers.com/images/hd/euphoric-daft-punk-sci-fi-aesthetic-hd-epgolhmyg06xd7nb.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            line-height: 1.6;
            padding: 20px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 0 20px #00ffff;
            text-align: center;
        }

        h1 {
            color: #00ffff;
            margin-bottom: 30px;
            font-size: 28px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 30px;
        }

        li {
            margin: 15px 0;
        }

        a {
            text-decoration: none;
            font-size: 18px;
            color: white;
            background: linear-gradient(135deg, #ff00ff, #00ffff);
            padding: 12px 25px;
            border-radius: 30px;
            transition: transform 0.2s ease;
            box-shadow: 0 0 10px #00ffff, 0 0 20px #ff00ff;
            display: inline-block;
        }

        a:hover {
            transform: scale(1.05);
        }

        /* Player Spotify estilo */
        .spotify-player {
            margin-top: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px #00ffff;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tela de Cadastro</h1>
        <ul>
            <li><a href="back_end/livros/cadastrar_livros.php">Cadastrar Livros</a></li>
            <li><a href="back_end/professores/cadastrar_professores.php">Cadastrar Professores</a></li>
            <li><a href="back_end/emprestimo/cadastrar_emprestimos.php">Cadastrar Empréstimo</a></li>
            <li><a href="back_end/alunos/cadastrar_alunos.php">Cadastrar Alunos</a></li>
        </ul>
        <a href="logout.php">Sair</a>

        <!-- Player Spotify atualizado -->
        <div class="spotify-player">
            <iframe 
                style="border-radius:12px" 
                src="https://open.spotify.com/embed/track/0oks4FnzhNp5QPTZtoet7c?utm_source=generator&theme=0" 
                width="100%" 
                height="152" 
                frameborder="0" 
                allowfullscreen="" 
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" 
                loading="lazy"
                title="Spotify Player"
            ></iframe>
        </div>
    </div>
</body>
</html>
