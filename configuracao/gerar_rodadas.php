<?php
require_once '../utils/json_helper.php';
require_once '../utils/sorteio.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formato = $_POST['formato'];
    $participantes = ler_json('../data/participantes.json');
    
    if (count($participantes) !== 8) {
        echo json_encode(['status' => 'erro', 'msg' => 'Cadastre 8 jogadores primeiro.']);
        exit;
    }

    if ($formato === 'rotativas') {
        $rodadas = gerar_rotativas($participantes);
    } else {
        $formato = 'fixas';
        $rodadas = gerar_fixas($participantes);
    }

    foreach ($rodadas as &$rodada) {
        $rodada['formato'] = $formato;
    }
    unset($rodada);

    gravar_json('../data/rodadas.json', $rodadas);
    gravar_json('../data/classificacoes.json', []);
    echo json_encode(['status' => 'ok', 'redirect' => '../rodadas/rodadas.php']);
}
