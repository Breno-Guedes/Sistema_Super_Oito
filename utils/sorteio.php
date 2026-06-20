<?php
function gerar_rotativas($jogadores) {
    $rodadas = [];
    $matriz = [
        [[0,1],[2,3], [4,5],[6,7]],
        [[0,2],[1,3], [4,6],[5,7]],
        [[0,3],[1,2], [4,7],[5,6]],
        [[0,4],[1,5], [2,6],[3,7]],
        [[0,5],[1,4], [2,7],[3,6]],
        [[0,6],[1,7], [2,4],[3,5]],
        [[0,7],[1,6], [2,5],[3,4]]
    ];
    
    foreach ($matriz as $i => $r) {
        $rodadas[] = [
            'rodada' => $i + 1,
            'status' => 'pendente',
            'partidas' => [
                ['dupla_1' => [$jogadores[$r[0][0]]['id'], $jogadores[$r[0][1]]['id']], 'dupla_2' => [$jogadores[$r[1][0]]['id'], $jogadores[$r[1][1]]['id']], 'placar_1' => '', 'placar_2' => ''],
                ['dupla_1' => [$jogadores[$r[2][0]]['id'], $jogadores[$r[2][1]]['id']], 'dupla_2' => [$jogadores[$r[3][0]]['id'], $jogadores[$r[3][1]]['id']], 'placar_1' => '', 'placar_2' => '']
            ]
        ];
    }
    return $rodadas;
}

function gerar_fixas($duplas) {
    $rodadas = [];
    $confrontos = [[0,1,2,3], [0,2,1,3], [0,3,1,2], [0,1,3,2], [0,2,3,1], [0,3,2,1], [0,1,2,3]];
    
    foreach ($confrontos as $i => $c) {
        $rodadas[] = [
            'rodada' => $i + 1,
            'status' => 'pendente',
            'partidas' => [
                ['dupla_1' => $duplas[$c[0]], 'dupla_2' => $duplas[$c[1]], 'placar_1' => '', 'placar_2' => ''],
                ['dupla_1' => $duplas[$c[2]], 'dupla_2' => $duplas[$c[3]], 'placar_1' => '', 'placar_2' => '']
            ]
        ];
    }
    return $rodadas;
}
