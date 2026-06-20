<?php
require_once '../utils/json_helper.php';
$participantes = ler_json('../data/participantes.json');
?>
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
                <select name="formato" id="formato" required>
                    <option value="">Selecione o formato...</option>
                    <option value="rotativas">Duplas Rotativas (Sorteio Inteligente)</option>
                    <option value="fixas">Duplas Fixas (4 Duplas)</option>
                </select>
            </div>
            <div class="duplas-fixas-config" id="duplas-fixas-config">
                <h3>Configurar Duplas Fixas</h3>
                <p>Selecione os jogadores cadastrados para montar manualmente as quatro duplas.</p>
                <?php for ($dupla = 0; $dupla < 4; $dupla++): ?>
                    <div class="dupla-fixa">
                        <h3>Dupla <?= $dupla + 1 ?></h3>
                        <div class="form-group">
                            <?php for ($jogador = 0; $jogador < 2; $jogador++): ?>
                                <select name="duplas[<?= $dupla ?>][]" class="select-dupla-fixa">
                                    <option value="">Jogador <?= $jogador + 1 ?></option>
                                    <?php foreach ($participantes as $participante): ?>
                                        <option value="<?= (int)$participante['id'] ?>">
                                            <?= htmlspecialchars($participante['apelido'] ?: $participante['nome'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            <button type="submit" class="btn">Gerar 7 Rodadas</button>
        </form>
    </div>
    <footer class="footer">
        <p>&copy; Desenvolvido por Breno de Souza Guedes.</p>
    </footer>
    <script src="../js/ui.js"></script>
    <script>
        const formatoSelect = document.getElementById('formato');
        const duplasFixasConfig = document.getElementById('duplas-fixas-config');
        const selectsDuplasFixas = [...document.querySelectorAll('.select-dupla-fixa')];

        function atualizarConfigDuplasFixas() {
            const usarDuplasFixas = formatoSelect.value === 'fixas';
            duplasFixasConfig.style.display = usarDuplasFixas ? 'grid' : 'none';
            selectsDuplasFixas.forEach((select) => {
                select.required = usarDuplasFixas;
                if (!usarDuplasFixas) {
                    select.value = '';
                }
            });
        }

        formatoSelect.addEventListener('change', atualizarConfigDuplasFixas);
        atualizarConfigDuplasFixas();
    </script>
</body>
</html>
