<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configura&ccedil;&atilde;o - Super 8</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="voltar">&larr; Voltar</a>
        <h2>Gerar Confrontos</h2>
        <form onsubmit="enviarFormulario(event, 'gerar_rodadas.php')">
            <div class="form-group form-group-unico">
                <select name="formato" required>
                    <option value="">Selecione o formato...</option>
                    <option value="rotativas">Duplas Rotativas (Sorteio Inteligente)</option>
                    <option value="fixas">Duplas Fixas (4 Duplas)</option>
                </select>
            </div>
            <button type="submit" class="btn">Gerar 7 Rodadas</button>
        </form>
    </div>
    <footer class="footer">
        <p>&copy; Desenvolvido por Breno de Souza Guedes.</p>
    </footer>
    <script src="../js/ui.js"></script>
</body>
</html>
