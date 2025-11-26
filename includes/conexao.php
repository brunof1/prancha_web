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

// includes/conexao.php
require_once __DIR__ . '/../config/config.php';

$conn = @new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
@$conn->query("SET collation_connection = 'utf8mb4_unicode_ci'");
?>