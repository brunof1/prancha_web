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
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'Não autenticado']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método não permitido']);
    exit;
}

$tema = isset($_POST['tema']) ? $_POST['tema'] : '';
if (!in_array($tema, ['light','dark'], true)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Tema inválido']);
    exit;
}

require_once __DIR__ . '/conexao.php';

$isAdmin   = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$idUsuario = (int) $_SESSION['id_usuario'];

if (isset($_POST['id'])) {
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(['ok'=>false,'msg'=>'Apenas admins podem alterar o tema de outros usuários']);
        exit;
    }
    $idUsuario = max(1, (int)$_POST['id']);
}

$stmt = $conn->prepare("UPDATE usuarios SET tema_preferido = ? WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Falha ao preparar statement']);
    exit;
}
$stmt->bind_param("si", $tema, $idUsuario);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'id' => $idUsuario, 'tema' => $tema]);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Falha ao salvar']);
}
$stmt->close();
