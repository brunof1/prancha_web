<?php
// includes/atualizar_tema.php
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

require_once __DIR__ . '../config/config.php'; // mysqli $conn

$idUsuario = (int) $_SESSION['id_usuario'];
$stmt = $conn->prepare("UPDATE usuarios SET tema_preferido = ? WHERE id = ?");
$stmt->bind_param("si", $tema, $idUsuario);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'tema' => $tema]);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Falha ao salvar']);
}
$stmt->close();
