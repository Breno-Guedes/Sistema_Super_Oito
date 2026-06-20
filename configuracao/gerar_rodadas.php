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
    } elseif ($formato === 'fixas') {
        $idsParticipantes = array_map('intval', array_column($participantes, 'id'));
        $idsValidos = array_flip($idsParticipantes);
        $duplasInformadas = $_POST['duplas'] ?? [];
        $duplas = [];
        $idsUsados = [];

        if (count($duplasInformadas) !== 4) {
            echo json_encode(['status' => 'erro', 'msg' => 'Configure as 4 duplas fixas.']);
            exit;
        }

        foreach ($duplasInformadas as $dupla) {
            if (!is_array($dupla) || count($dupla) !== 2) {
                echo json_encode(['status' => 'erro', 'msg' => 'Cada dupla fixa deve ter 2 jogadores.']);
                exit;
            }

            $idsDupla = array_map('intval', $dupla);

            if ($idsDupla[0] === $idsDupla[1] || !isset($idsValidos[$idsDupla[0]]) || !isset($idsValidos[$idsDupla[1]])) {
                echo json_encode(['status' => 'erro', 'msg' => 'Selecione jogadores válidos para as duplas fixas.']);
                exit;
            }

            foreach ($idsDupla as $id) {
                if (isset($idsUsados[$id])) {
                    echo json_encode(['status' => 'erro', 'msg' => 'Cada jogador pode aparecer em apenas uma dupla fixa.']);
                    exit;
                }

                $idsUsados[$id] = true;
            }

            $duplas[] = $idsDupla;
        }

        $formato = 'fixas';
        $rodadas = gerar_fixas($duplas);
    } else {
        echo json_encode(['status' => 'erro', 'msg' => 'Selecione um formato valido.']);
        exit;
    }

    foreach ($rodadas as &$rodada) {
        $rodada['formato'] = $formato;
    }
    unset($rodada);

    gravar_json('../data/rodadas.json', $rodadas);
    gravar_json('../data/classificacoes.json', []);
    echo json_encode(['status' => 'ok', 'redirect' => '../rodadas/rodadas.php']);
}
