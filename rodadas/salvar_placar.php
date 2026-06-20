<?php
require_once '../utils/json_helper.php';
require_once '../utils/pontuacao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rodadas = ler_json('../data/rodadas.json');
    $participantes = ler_json('../data/participantes.json');
    $index = (int)$_POST['rodada_index'];

    if (!isset($rodadas[$index])) {
        echo json_encode(['status' => 'erro', 'msg' => 'Rodada invalida.']);
        exit;
    }

    for ($i = 0; $i < 2; $i++) {
        $placar1 = isset($_POST["p1_$i"]) ? trim($_POST["p1_$i"]) : '';
        $placar2 = isset($_POST["p2_$i"]) ? trim($_POST["p2_$i"]) : '';

        if ($placar1 === '' || $placar2 === '') {
            echo json_encode(['status' => 'erro', 'msg' => 'Preencha todos os placares da rodada.']);
            exit;
        }

        if ((int)$placar1 === (int)$placar2) {
            echo json_encode(['status' => 'erro', 'msg' => 'Empate nao e permitido. Informe um placar sem empate.']);
            exit;
        }
    }

    $rodadas[$index]['partidas'][0]['placar_1'] = $_POST['p1_0'];
    $rodadas[$index]['partidas'][0]['placar_2'] = $_POST['p2_0'];
    $rodadas[$index]['partidas'][1]['placar_1'] = $_POST['p1_1'];
    $rodadas[$index]['partidas'][1]['placar_2'] = $_POST['p2_1'];
    $rodadas[$index]['status'] = 'concluida';

    gravar_json('../data/rodadas.json', $rodadas);
    gravar_json('../data/classificacoes.json', calcular_rankings_por_rodada($participantes, $rodadas, true));

    $rodadaConcluida = (int)$rodadas[$index]['rodada'];
    echo json_encode(['status' => 'ok', 'redirect' => 'rodadas.php?rodada_concluida=' . $rodadaConcluida]);
}
