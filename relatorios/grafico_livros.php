<?php
include '../back_end/config.php';

// Consulta para aluno que mais pegou livros emprestados
$sqlAlunoTop = "
    SELECT a.nome, COUNT(e.id) AS total_emprestimos
    FROM alunos a
    JOIN emprestimos e ON e.alunos_id = a.id
    GROUP BY a.id
    ORDER BY total_emprestimos DESC
    LIMIT 1
";
$stmtAluno = $pdo->prepare($sqlAlunoTop);
$stmtAluno->execute();
$topAluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

// Gráfico 1: Livros mais emprestados (últimos 2 meses)
$sql1 = "
    SELECT l.nome_livro, COUNT(e.id) AS total
    FROM emprestimos e
    JOIN livros l ON e.livros_id = l.id
    WHERE e.data_retirada >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)
    GROUP BY l.nome_livro
    ORDER BY total DESC
    LIMIT 10
";
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();
$livros = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Gráfico 2: Empréstimos por mês (últimos 6 meses)
$sql2 = "
    SELECT DATE_FORMAT(data_retirada, '%Y-%m') AS mes, COUNT(*) AS total
    FROM emprestimos
    WHERE data_retirada >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY mes
    ORDER BY mes
";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$mensal = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Relatórios</title>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #121212;
            padding: 40px;
            text-align: center;
            color: #fcbf49;
        }

        h1 {
            margin-bottom: 40px;
            text-shadow: 0 0 8px #fcbf49;
        }

        .top-aluno {
            background-color: #b37400;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 20px #fcbf49;
            max-width: 700px;
            margin: 0 auto 40px auto;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .grafico {
            margin: 30px auto;
            width: 90%;
            max-width: 900px;
            height: 400px;
            background: #222;
            border-radius: 12px;
            box-shadow: 0 0 20px #fcbf49aa;
            padding: 10px;
        }

        .btn-voltar {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 24px;
            background-color: #fcbf49;
            color: #222;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 0 12px #fcbf49;
            transition: background-color 0.3s;
        }

        .btn-voltar:hover {
            background-color: #d99800;
        }
    </style>

    <script>
        google.charts.load('current', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Gráfico 1: Livros mais emprestados
            const data1 = google.visualization.arrayToDataTable([
                ['Livro', 'Empréstimos'],
                <?php
                foreach ($livros as $row) {
                    echo "['" . addslashes($row['nome_livro']) . "', " . $row['total'] . "],";
                }
                ?>
            ]);

            const options1 = {
    title: 'Livros Mais Emprestados (Últimos 2 meses)',
    titleTextStyle: {
        color: '#fcbf49',  // MESMA COR dos nomes dos livros
        fontSize: 18,
        bold: true
    },
    curveType: 'function',
    backgroundColor: '#222',
    chartArea: { width: '80%', height: '70%' },
    legend: {
        position: 'bottom',
        textStyle: { color: '#fcbf49' }
    },
    hAxis: {
        title: 'Mês',
        textStyle: { color: '#fcbf49', fontSize: 13 },
        titleTextStyle: { color: '#fcbf49', fontSize: 16 }
    },
    vAxis: {
        title: 'Total',
        textStyle: { color: '#fcbf49', fontSize: 13 },
        titleTextStyle: { color: '#fcbf49', fontSize: 16 }
    },
    colors: ['#fcbf49'],
    pointSize: 8,
    lineWidth: 3,
    animation: {
        startup: true,
        duration: 1000,
        easing: 'inAndOut'
    }
};


            const chart1 = new google.visualization.BarChart(document.getElementById('grafico1'));
            chart1.draw(data1, options1);

            // Gráfico 2: Empréstimos por mês
            const data2 = google.visualization.arrayToDataTable([
                ['Mês', 'Empréstimos'],
                <?php
                foreach ($mensal as $row) {
                    echo "['" . $row['mes'] . "', " . $row['total'] . "],";
                }
                ?>
            ]);

            const options2 = {
    title: 'Quantidade de Empréstimos por Mês (Últimos 6 meses)',
    titleTextStyle: {
        color: '#fcbf49',  // MESMA COR dos nomes dos livros
        fontSize: 18,
        bold: true
    },
    curveType: 'function',
    backgroundColor: '#222',
    chartArea: { width: '80%', height: '70%' },
    legend: {
        position: 'bottom',
        textStyle: { color: '#fcbf49' }
    },
    hAxis: {
        title: 'Mês',
        textStyle: { color: '#fcbf49', fontSize: 13 },
        titleTextStyle: { color: '#fcbf49', fontSize: 16 }
    },
    vAxis: {
        title: 'Total',
        textStyle: { color: '#fcbf49', fontSize: 13 },
        titleTextStyle: { color: '#fcbf49', fontSize: 16 }
    },
    colors: ['#fcbf49'],
    pointSize: 8,
    lineWidth: 3,
    animation: {
        startup: true,
        duration: 1000,
        easing: 'inAndOut'
    }
};


            const chart2 = new google.visualization.LineChart(document.getElementById('grafico2'));
            chart2.draw(data2, options2);
        }
    </script>
</head>
<body>
    <h1>Painel de Relatórios</h1>

    <?php if ($topAluno): ?>
        <div class="top-aluno">
            Aluno que mais pegou livros emprestados: <strong><?= htmlspecialchars($topAluno['nome']) ?></strong> — <strong><?= $topAluno['total_emprestimos'] ?></strong> empréstimos
        </div>
    <?php endif; ?>

    <h2>Livros Mais Emprestados</h2>
    <div id="grafico1" class="grafico"></div>

    <h2>Empréstimos por Mês</h2>
    <div id="grafico2" class="grafico"></div>

    <a href="../listas.php" class="btn-voltar">Voltar</a>
</body>
</html>
