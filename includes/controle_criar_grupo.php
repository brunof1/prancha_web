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

require_once 'modelo_grupos.php';
require_once 'funcoes.php';
require_once __DIR__ . '/acl.php';
require_admin();

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_grupo = limparEntrada($_POST['nome_grupo']);

    if (criarGrupo($nome_grupo)) {
        header('Location: ../pages/gerenciar_cartoes.php?gsucesso=1');
        exit;
    } else {
        header('Location: ../pages/gerenciar_cartoes.php?erro=1');
        exit;
    }
} else {
    echo "Requisição inválida.";
}
?>
