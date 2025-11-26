<?php

/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

// includes/arasaac_buscar.php
require_once __DIR__ . '/acl.php';
require_admin();
header('Content-Type: application/json; charset=UTF-8');

$q    = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$lang = isset($_GET['lang']) ? trim((string)$_GET['lang']) : 'pt';

if ($q === '') {
    echo json_encode(['ok'=>false, 'msg'=>'Parâmetro q obrigatório.'], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/arasaac.php';

try {
    $res = arasaac_search($q, $lang, 40);
    echo json_encode(['ok'=>true, 'results'=>$res], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false, 'msg'=>'Falha na busca ARASAAC.'], JSON_UNESCAPED_UNICODE);
}
?>