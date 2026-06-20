<?php
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super 8 - Beach Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Super 8 Beach Tennis</h1>
    </header>
    <main class="menu">
        <a href="participantes/cadastro.php" class="btn">1. Cadastrar Jogadores</a>
        <a href="configuracao/configuracao.php" class="btn">2. Gerar Rodadas</a>
        <a href="rodadas/rodadas.php" class="btn">3. Jogar Rodadas</a>
        <a href="classificacao/classificacao.php" class="btn">4. Classifica&ccedil;&atilde;o</a>
        <button onclick="zerarSistema()" class="btn btn-danger">Zerar Torneio</button>
    </main>
    <footer class="footer">
        <p>&copy; Desenvolvido por Breno de Souza Guedes.</p>
    </footer>
    <script src="js/ui.js"></script>
</body>
</html>
