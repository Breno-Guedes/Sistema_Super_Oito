<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Super 8</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="voltar">&larr; Voltar</a>
        <h2>Cadastro de 8 Jogadores</h2>
        <form onsubmit="enviarFormulario(event, 'salvar_participantes.php')">
            <?php for($i=1; $i<=8; $i++): ?>
            <div class="form-group">
                <input type="text" name="nome[]" placeholder="Nome Completo - Jogador <?= $i ?>" required>
                <input type="text" name="apelido[]" placeholder="Apelido (Opcional)">
            </div>
            <?php endfor; ?>
            <button type="submit" class="btn">Salvar Participantes</button>
        </form>
    </div>
    <footer class="footer">
        <p>&copy; Desenvolvido por Breno de Souza Guedes.</p>
    </footer>
    <script src="../js/ui.js"></script>
</body>
</html>
