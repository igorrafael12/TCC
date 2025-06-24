<?php
include '../back_end/config.php';

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
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 40px;
            text-align: center;
        }

        h2 {
            margin-top: 40px;
            color: #333;
        }

        .grafico {
            margin: 30px auto;
            width: 90%;
            max-width: 900px;
            height: 400px;
        }

        .btn-voltar {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 24px;
            background-color: #1976D2;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
        }

        .btn-voltar:hover {
            background-color: #0D47A1;
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
                bars: 'horizontal',
                hAxis: { title: 'Total de Empréstimos' },
                colors: ['#4CAF50']
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
                curveType: 'function',
                legend: { position: 'bottom' },
                colors: ['#FF9800']
            };

            const chart2 = new google.visualization.LineChart(document.getElementById('grafico2'));
            chart2.draw(data2, options2);
        }
    </script>
</head>
<body>
    <h1>Painel de Relatórios</h1>

    <h2>Livros Mais Emprestados</h2>
    <div id="grafico1" class="grafico"></div>

    <h2>Empréstimos por Mês</h2>
    <div id="grafico2" class="grafico"></div>

    <a href="../listas.php" class="btn-voltar">Voltar</a>
</body>
</html>
