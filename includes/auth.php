<?php
// includes/auth.php
require_once __DIR__ . '/conexao.php';

function getUsuarioAtualId(): int {
  return isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0;
}

function getUsuarioTipo(int $id, mysqli $conn): ?string {
  if ($stmt = $conn->prepare("SELECT tipo FROM usuarios WHERE id = ? LIMIT 1")) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($tipo);
    $ok = $stmt->fetch();
    $stmt->close();
    return $ok ? $tipo : null;
  }
  return null;
}

function isAdmin(int $id, mysqli $conn): bool {
  $tipo = getUsuarioTipo($id, $conn);
  return strtolower((string)$tipo) === 'admin';
}

/** Bloqueia a página para não-admins (use no topo de páginas restritas). */
function exigirAdmin(mysqli $conn): void {
  $uid = getUsuarioAtualId();
  if ($uid <= 0 || !isAdmin($uid, $conn)) {
    http_response_code(403);
    exit('Acesso negado.');
  }
}