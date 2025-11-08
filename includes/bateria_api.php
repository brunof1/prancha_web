<?php
require_once __DIR__ . '/acl.php';
ensure_session();
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok'=>false,'msg'=>'Não autenticado']);
    exit;
}

require_once __DIR__ . '/modelo_usuarios.php';

function human_datetime(?string $dt): string {
    if (!$dt) return 'nunca';
    $ts = strtotime($dt);
    return date('d/m/Y H:i', $ts);
}

header('Content-Type: application/json; charset=UTF-8');

$method   = $_SERVER['REQUEST_METHOD'];
$idSessao = (int)$_SESSION['id_usuario'];
$isAdmin  = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

/** Resolve o alvo (por padrão, o próprio usuário; admin pode passar id=...) */
$alvo = $idSessao;
if ($isAdmin) {
    if ($method === 'GET'  && isset($_GET['id'])  && ctype_digit((string)$_GET['id']))  { $alvo = (int)$_GET['id']; }
    if ($method === 'POST' && isset($_POST['id']) && is_numeric($_POST['id']))          { $alvo = (int)$_POST['id']; }
}

if ($method === 'GET') {
    // Admin: lista completa
    if ($isAdmin && isset($_GET['all']) && $_GET['all'] === '1') {
        $todos = listarUsuariosComBateria();
        $map = array_map(function($u){
            return [
                'id' => (int)$u['id'],
                'nome' => $u['nome'],
                'email' => $u['email'],
                'tipo' => $u['tipo'],
                'nivel' => isset($u['bateria_social']) ? (int)$u['bateria_social'] : 3,
                'atualizado_em' => $u['bateria_atualizado_em'],
                'atualizado_em_human' => human_datetime($u['bateria_atualizado_em']),
            ];
        }, $todos);
        echo json_encode(['ok'=>true,'lista'=>$map], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $dados = obterBateriaSocial($alvo);
    echo json_encode([
        'ok'=>true,
        'id'=>$alvo,
        'nivel'=>$dados['nivel'],
        'atualizado_em'=>$dados['atualizado_em'],
        'atualizado_em_human'=>human_datetime($dados['atualizado_em'])
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    $nivel = isset($_POST['nivel']) ? (int)$_POST['nivel'] : -1;
    if ($nivel < 0 || $nivel > 5) {
        http_response_code(400);
        echo json_encode(['ok'=>false,'msg'=>'Nível inválido (0–5).'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $ok = atualizarBateriaSocial($alvo, $nivel);
    $dados = obterBateriaSocial($alvo);
    echo json_encode([
        'ok'=> (bool)$ok,
        'id'=> $alvo,
        'nivel'=>$dados['nivel'],
        'atualizado_em'=>$dados['atualizado_em'],
        'atualizado_em_human'=>human_datetime($dados['atualizado_em'])
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['ok'=>false,'msg'=>'Método não permitido'], JSON_UNESCAPED_UNICODE);
