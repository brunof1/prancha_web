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
