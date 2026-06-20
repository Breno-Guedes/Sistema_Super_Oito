<?php
function obter_nome_participante($participante) {
    return $participante['nome'] . ($participante['apelido'] ? " ({$participante['apelido']})" : "");
}

function mapear_participantes_por_id($participantes) {
    $mapa = [];

    foreach ($participantes as $participante) {
        $mapa[$participante['id']] = $participante;
    }

    return $mapa;
}

function chave_dupla($dupla) {
    $ids = array_map('intval', $dupla);
    sort($ids);
    return implode('-', $ids);
}

function obter_formato_rodadas($rodadas) {
    if (!empty($rodadas) && isset($rodadas[0]['formato'])) {
        return $rodadas[0]['formato'];
    }

    $duplas = [];
    foreach ($rodadas as $rodada) {
        foreach ($rodada['partidas'] as $partida) {
            $duplas[chave_dupla($partida['dupla_1'])] = true;
            $duplas[chave_dupla($partida['dupla_2'])] = true;
        }
    }

    return count($duplas) === 4 ? 'fixas' : 'rotativas';
}

function criar_linha_ranking($nome) {
    return [
        'nome' => $nome,
        'jogos' => 0,
        'vitorias' => 0,
        'derrotas' => 0,
        'games_pro' => 0,
        'games_contra' => 0,
        'pontos' => 0
    ];
}

function registrar_resultado_ranking(&$ranking, $chave, $gamesPro, $gamesContra) {
    if (!isset($ranking[$chave])) {
        return;
    }

    $ranking[$chave]['jogos']++;
    $ranking[$chave]['games_pro'] += $gamesPro;
    $ranking[$chave]['games_contra'] += $gamesContra;

    if ($gamesPro > $gamesContra) {
        $ranking[$chave]['vitorias']++;
        $ranking[$chave]['pontos'] += 3;
    } else {
        $ranking[$chave]['derrotas']++;
    }
}

function ordenar_ranking($ranking) {
    usort($ranking, function($a, $b) {
        if ($a['pontos'] !== $b['pontos']) return $b['pontos'] - $a['pontos'];
        if ($a['games_pro'] !== $b['games_pro']) return $b['games_pro'] - $a['games_pro'];
        $saldoA = $a['games_pro'] - $a['games_contra'];
        $saldoB = $b['games_pro'] - $b['games_contra'];
        if ($saldoA !== $saldoB) return $saldoB - $saldoA;
        return strcmp($a['nome'], $b['nome']);
    });

    return $ranking;
}

function calcular_ranking_jogadores($participantes, $rodadas, $ateRodada = null) {
    $ranking = [];

    foreach ($participantes as $participante) {
        $ranking[$participante['id']] = criar_linha_ranking(obter_nome_participante($participante));
    }

    foreach ($rodadas as $rodada) {
        if ($ateRodada !== null && (int)$rodada['rodada'] > $ateRodada) {
            continue;
        }

        foreach ($rodada['partidas'] as $partida) {
            if ($partida['placar_1'] === "" || $partida['placar_2'] === "") {
                continue;
            }

            $g1 = (int)$partida['placar_1'];
            $g2 = (int)$partida['placar_2'];

            if ($g1 === $g2) {
                continue;
            }

            foreach ($partida['dupla_1'] as $id) {
                registrar_resultado_ranking($ranking, $id, $g1, $g2);
            }

            foreach ($partida['dupla_2'] as $id) {
                registrar_resultado_ranking($ranking, $id, $g2, $g1);
            }
        }
    }

    return ordenar_ranking($ranking);
}

function obter_duplas_fixas($rodadas, $participantesPorId) {
    $duplas = [];

    foreach ($rodadas as $rodada) {
        foreach ($rodada['partidas'] as $partida) {
            foreach (['dupla_1', 'dupla_2'] as $campo) {
                $chave = chave_dupla($partida[$campo]);

                if (isset($duplas[$chave])) {
                    continue;
                }

                $nomes = [];
                foreach ($partida[$campo] as $id) {
                    if (isset($participantesPorId[$id])) {
                        $nomes[] = obter_nome_participante($participantesPorId[$id]);
                    }
                }

                $duplas[$chave] = criar_linha_ranking(implode(' / ', $nomes));
            }
        }
    }

    return $duplas;
}

function calcular_ranking_duplas_fixas($participantes, $rodadas, $ateRodada = null) {
    $participantesPorId = mapear_participantes_por_id($participantes);
    $ranking = obter_duplas_fixas($rodadas, $participantesPorId);

    foreach ($rodadas as $rodada) {
        if ($ateRodada !== null && (int)$rodada['rodada'] > $ateRodada) {
            continue;
        }

        foreach ($rodada['partidas'] as $partida) {
            if ($partida['placar_1'] === "" || $partida['placar_2'] === "") {
                continue;
            }

            $g1 = (int)$partida['placar_1'];
            $g2 = (int)$partida['placar_2'];

            if ($g1 === $g2) {
                continue;
            }

            registrar_resultado_ranking($ranking, chave_dupla($partida['dupla_1']), $g1, $g2);
            registrar_resultado_ranking($ranking, chave_dupla($partida['dupla_2']), $g2, $g1);
        }
    }

    return ordenar_ranking($ranking);
}

function calcular_ranking($participantes, $rodadas, $ateRodada = null, $formato = null) {
    $formato = $formato ?: obter_formato_rodadas($rodadas);

    if ($formato === 'fixas') {
        return calcular_ranking_duplas_fixas($participantes, $rodadas, $ateRodada);
    }

    return calcular_ranking_jogadores($participantes, $rodadas, $ateRodada);
}

function calcular_rankings_por_rodada($participantes, $rodadas, $somenteConcluidas = false) {
    $rankings = [];
    $formato = obter_formato_rodadas($rodadas);

    foreach ($rodadas as $rodada) {
        if ($somenteConcluidas && $rodada['status'] !== 'concluida') {
            continue;
        }

        $numeroRodada = (int)$rodada['rodada'];
        $rankings[$numeroRodada] = calcular_ranking($participantes, $rodadas, $numeroRodada, $formato);
    }

    ksort($rankings);
    return $rankings;
}
