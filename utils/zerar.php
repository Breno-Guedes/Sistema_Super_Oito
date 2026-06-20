<?php
$fileP = __DIR__ . '/../data/participantes.json';
$fileR = __DIR__ . '/../data/rodadas.json';
if (file_exists($fileP)) unlink($fileP);
if (file_exists($fileR)) unlink($fileR);
echo json_encode(['status' => 'ok']);