<?php
require_once __DIR__ . '/acl.php';
require_admin();

require_once __DIR__ . '/modelo_grupos_pranchas.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Método não permitido.');
}

$id   = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nome = isset($_POST['nome']) ? trim((string)$_POST['nome']) : '';

if ($id <= 0 || $nome === '') {
  http_response_code(400);
  exit('Dados inválidos.');
}

if (atualizarGrupoPrancha($id, $nome)) {
  header('Location: ../pages/editar_grupo_prancha.php?id=' . $id . '&ok=1');
  exit;
}

http_response_code(500);
echo 'Falha ao atualizar grupo.';
