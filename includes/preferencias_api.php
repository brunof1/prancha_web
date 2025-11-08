<?php
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