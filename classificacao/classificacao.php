<?php
require_once '../utils/json_helper.php';
require_once '../utils/pontuacao.php';

$participantes = ler_json('../data/participantes.json');
$rodadas = ler_json('../data/rodadas.json');
$formato = obter_formato_rodadas($rodadas);
$rodadaSelecionada = isset($_GET['rodada']) ? (int)$_GET['rodada'] : null;
$rodadasConcluidas = [];
$competicaoConcluida = !empty($rodadas);

foreach ($rodadas as $rodada) {
    if ($rodada['status'] === 'concluida') {
        $rodadasConcluidas[] = (int)$rodada['rodada'];
    } else {
        $competicaoConcluida = false;
    }
}

if ($rodadaSelecionada && !in_array($rodadaSelecionada, $rodadasConcluidas, true)) {
    $rodadaSelecionada = null;
}

$ranking = [];
if (!empty($participantes) && !empty($rodadas)) {
    $ranking = calcular_ranking($participantes, $rodadas, $rodadaSelecionada, $formato);
}

$colunaNome = $formato === 'fixas' ? 'Dupla' : 'Jogador';
$titulo = 'Classifica&ccedil;&atilde;o Atual';
if ($rodadaSelecionada) {
    $titulo = 'Classifica&ccedil;&atilde;o Parcial - Rodada ' . $rodadaSelecionada;
} else if ($competicaoConcluida) {
    $titulo = 'Classifica&ccedil;&atilde;o Final';
}

$voltarUrl = $rodadaSelecionada
    ? '../rodadas/rodadas.php?rodada_concluida=' . $rodadaSelecionada
    : '../index.php';

function imprimir_tabela_classificacao($ranking, $colunaNome) {
    ?>
    <div class="tabela-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Pos</th>
                    <th><?= $colunaNome ?></th>
                    <th>Pts</th>
                    <th>J</th>
                    <th>V</th>
                    <th>D</th>
                    <th>GV</th>
                    <th>GP</th>
                    <th>SG</th>
                </tr>
            </thead>
            <tbody>
                <?php $pos = 1; foreach($ranking as $r): ?>
                <tr>
                    <td><?= $pos++ ?>&ordm;</td>
                    <td><?= htmlspecialchars($r['nome']) ?></td>
                    <td><b><?= $r['pontos'] ?></b></td>
                    <td><?= $r['jogos'] ?></td>
                    <td><?= $r['vitorias'] ?></td>
                    <td><?= $r['derrotas'] ?></td>
                    <td><?= $r['games_pro'] ?></td>
                    <td><?= $r['games_contra'] ?></td>
                    <td><?= $r['games_pro'] - $r['games_contra'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica&ccedil;&atilde;o - Super 8</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        @media print { .no-print { display: none; } }
        .tabela-wrapper { overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?= $voltarUrl ?>" class="voltar no-print">&larr; Voltar</a>
        <button onclick="imprimirRanking()" class="btn no-print btn-imprimir">Imprimir</button>

        <h2><?= $titulo ?></h2>
        <p class="subtitulo-classificacao">
            Modo: <?= $formato === 'fixas' ? 'Duplas Fixas' : 'Duplas Rotativas' ?>
        </p>

        <?php if (!empty($rodadasConcluidas)): ?>
            <div class="acoes-parciais no-print">
                <?php foreach($rodadasConcluidas as $numeroRodada): ?>
                    <a
                        href="classificacao.php?rodada=<?= $numeroRodada ?>"
                        class="btn <?= $rodadaSelecionada === $numeroRodada ? 'btn-secundario ativo' : 'btn-secundario' ?>"
                    >
                        Rodada <?= $numeroRodada ?>
                    </a>
                <?php endforeach; ?>

                <?php if ($competicaoConcluida): ?>
                    <a href="classificacao.php" class="btn <?= $rodadaSelecionada ? 'btn-secundario' : '' ?>">Final</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if(empty($ranking)): ?>
            <p>Ainda n&atilde;o h&aacute; dados suficientes.</p>
        <?php else: ?>
            <?php imprimir_tabela_classificacao($ranking, $colunaNome); ?>
        <?php endif; ?>

        <?php if ($rodadaSelecionada): ?>
            <div class="barra-fluxo no-print">
                <a href="<?= $voltarUrl ?>" class="btn">Continuar Lan&ccedil;amento</a>
            </div>
        <?php endif; ?>
    </div>
    <footer class="footer">
        <p>&copy; Desenvolvido por Breno de Souza Guedes.</p>
    </footer>
    <script src="../js/ui.js"></script>
</body>
</html>
