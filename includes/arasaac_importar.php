<?php
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