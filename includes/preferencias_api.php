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

session_start();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'Não autenticado']);
    exit;
}

require_once __DIR__ . '/modelo_preferencias.php';

$id_usuario = (int) $_SESSION['id_usuario'];
$prefs = obterPreferenciasUsuario($id_usuario);

echo json_encode(['ok' => true, 'preferencias' => $prefs], JSON_UNESCAPED_UNICODE);
?>