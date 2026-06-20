<?php
require_once '../utils/json_helper.php';
$rodadas = ler_json('../data/rodadas.json');
$participantes = ler_json('../data/participantes.json');

$nomes = [];
foreach ($participantes as $p) {
    $nomes[$p['id']] = $p['apelido'] ?: $p['nome'];
}

$rodadaAtual = null;
foreach ($rodadas as $r) {
    if ($r['status'] === 'pendente') {
        $rodadaAtual = $r;
        break;
    }
}

$rodadaConcluida = isset($_GET['rodada_concluida']) ? (int)$_GET['rodada_concluida'] : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodadas - Super 8</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="voltar">&larr; Voltar</a>

        <?php if ($rodadaConcluida): ?>
            <div class="aviso-parcial">
                <p>Classifica&ccedil;&atilde;o parcial da Rodada <?= $rodadaConcluida ?> dispon&iacute;vel.</p>
                <a href="../classificacao/classificacao.php?rodada=<?= $rodadaConcluida ?>" class="btn">Visualizar Classifica&ccedil;&atilde;o Parcial</a>
            </div>
        <?php endif; ?>

        <?php if (!$rodadaAtual): ?>
            <h2>Todas as rodadas foram conclu&iacute;das!</h2>
            <a href="../classificacao/classificacao.php" class="btn">Ver Classifica&ccedil;&atilde;o Final</a>
        <?php else: ?>
            <h2>Rodada <?= $rodadaAtual['rodada'] ?> de 7</h2>
            <form onsubmit="enviarFormulario(event, 'salvar_placar.php')">
                <input type="hidden" name="rodada_index" value="<?= $rodadaAtual['rodada'] - 1 ?>">

                <?php foreach ($rodadaAtual['partidas'] as $index => $partida): ?>
                    <div class="partida">
                        <h3>Quadra <?= $index + 1 ?></h3>
                        <p>
                            <?= $nomes[$partida['dupla_1'][0]] ?> / <?= $nomes[$partida['dupla_1'][1]] ?>
                            <b>VS</b>
                            <?= $nomes[$partida['dupla_2'][0]] ?> / <?= $nomes[$partida['dupla_2'][1]] ?>
                        </p>
                        <div class="placar-inputs">
                            <input type="number" name="p1_<?= $index ?>" min="0" required>
                            <span>X</span>
                            <input type="number" name="p2_<?= $index ?>" min="0" required>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn">Salvar Rodada e Avan&ccedil;ar</button>
            </form>
        <?php endif; ?>
    </div>
    <footer class="footer">
        <p>&copy; Desenvolvido por Breno de Souza Guedes.</p>
    </footer>
    <script src="../js/ui.js"></script>
</body>
</html>
