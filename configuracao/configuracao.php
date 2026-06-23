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
    <link rel="stylesheet" href="../css/style.css?v=<?= filemtime('../css/style.css') ?>">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="voltar">&larr; Voltar</a>
        <h2>Gerar Confrontos</h2>
        <form onsubmit="enviarFormulario(event, 'gerar_rodadas.php')">
            <div class="formato-opcoes" role="radiogroup" aria-label="Formato das duplas">
                <label class="formato-card">
                    <input type="radio" name="formato" value="fixas" class="formato-radio" required>
                    <span class="formato-card-conteudo">
                        <span class="formato-card-topo">
                            <span class="formato-card-icone">F</span>
                            <span class="formato-card-status">Selecionar</span>
                        </span>
                        <span class="formato-card-titulo">Duplas Fixas</span>
                        <span class="formato-card-descricao">Os jogadores permanecem com o mesmo parceiro.</span>
                    </span>
                </label>
                <label class="formato-card">
                    <input type="radio" name="formato" value="rotativas" class="formato-radio" required>
                    <span class="formato-card-conteudo">
                        <span class="formato-card-topo">
                            <span class="formato-card-icone">R</span>
                            <span class="formato-card-status">Selecionar</span>
                        </span>
                        <span class="formato-card-titulo">Duplas Rotativas</span>
                        <span class="formato-card-descricao">Os parceiros mudam automaticamente ao longo das rodadas.</span>
                    </span>
                </label>
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
        const formatoRadios = [...document.querySelectorAll('input[name="formato"]')];
        const duplasFixasConfig = document.getElementById('duplas-fixas-config');
        const selectsDuplasFixas = [...document.querySelectorAll('.select-dupla-fixa')];

        function atualizarConfigDuplasFixas() {
            const formatoSelecionado = formatoRadios.find((radio) => radio.checked)?.value || '';
            const usarDuplasFixas = formatoSelecionado === 'fixas';
            duplasFixasConfig.style.display = usarDuplasFixas ? 'grid' : 'none';
            formatoRadios.forEach((radio) => {
                const status = radio.closest('.formato-card').querySelector('.formato-card-status');
                status.textContent = radio.checked ? 'Selecionado' : 'Selecionar';
            });
            selectsDuplasFixas.forEach((select) => {
                select.required = usarDuplasFixas;
                if (!usarDuplasFixas) {
                    select.value = '';
                }
            });
        }

        formatoRadios.forEach((radio) => {
            radio.addEventListener('change', atualizarConfigDuplasFixas);
        });
        atualizarConfigDuplasFixas();
    </script>
</body>
</html>
