<?php
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