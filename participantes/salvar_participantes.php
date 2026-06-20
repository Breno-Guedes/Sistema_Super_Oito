<?php
require_once '../utils/json_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomes = $_POST['nome'];
    $apelidos = $_POST['apelido'];

    if (count($nomes) !== 8) {
        echo json_encode(['status' => 'erro', 'msg' => 'E necessario cadastrar 8 jogadores.']);
        exit;
    }

    $participantes = [];
    for ($i = 0; $i < 8; $i++) {
        $participantes[] = [
            'id' => $i + 1,
            'nome' => trim($nomes[$i]),
            'apelido' => trim($apelidos[$i])
        ];
    }

    gravar_json('../data/participantes.json', $participantes);
    echo json_encode(['status' => 'ok', 'redirect' => '../configuracao/configuracao.php']);
}
