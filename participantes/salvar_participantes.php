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
    $nomesCadastrados = [];
    for ($i = 0; $i < 8; $i++) {
        $nome = trim($nomes[$i]);
        $apelido = trim($apelidos[$i]);
        $nomeNormalizado = preg_replace('/\s+/', ' ', $nome);
        $chaveNome = function_exists('mb_strtolower')
            ? mb_strtolower($nomeNormalizado, 'UTF-8')
            : strtolower($nomeNormalizado);

        if ($nomeNormalizado === '') {
            echo json_encode(['status' => 'erro', 'msg' => 'Preencha o nome de todos os jogadores.']);
            exit;
        }

        if (isset($nomesCadastrados[$chaveNome])) {
            echo json_encode(['status' => 'erro', 'msg' => 'Nao e permitido cadastrar jogadores com nomes identicos.']);
            exit;
        }

        $nomesCadastrados[$chaveNome] = true;
        $participantes[] = [
            'id' => $i + 1,
            'nome' => $nomeNormalizado,
            'apelido' => $apelido
        ];
    }

    gravar_json('../data/participantes.json', $participantes);
    echo json_encode(['status' => 'ok', 'redirect' => '../configuracao/configuracao.php']);
}
