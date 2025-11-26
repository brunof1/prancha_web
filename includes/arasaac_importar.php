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

// includes/arasaac_importar.php
require_once __DIR__ . '/acl.php';
require_admin();
header('Content-Type: application/json; charset=UTF-8');

$id   = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$lang = isset($_POST['lang']) ? trim((string)$_POST['lang']) : 'pt';

if ($id <= 0) {
    echo json_encode(['ok'=>false, 'msg'=>'ID inválido.'], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/arasaac.php';

$res = arasaac_import($id, $lang);
if (!empty($res['ok'])) {
    echo json_encode(['ok'=>true, 'filename'=>$res['filename'], 'mime'=>$res['mime'] ?? null], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(['ok'=>false, 'msg'=>$res['error'] ?? 'Não foi possível importar.'], JSON_UNESCAPED_UNICODE);
}
?>