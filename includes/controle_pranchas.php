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

require_once 'modelo_pranchas.php';

$idUsuario = (int)$_SESSION['id_usuario'];
$isAdmin   = ($_SESSION['tipo_usuario'] === 'admin');

// Pranchas visíveis para quem está logado
$lista_pranchas = listarPranchasPorUsuario($idUsuario, $isAdmin);

// Grupos de pranchas: só precisa para telas de admin (criar/editar)
$lista_grupos_pranchas = listarGruposPranchasPranchas();
?>