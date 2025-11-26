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

// includes/acl.php

function ensure_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function is_admin(): bool {
    ensure_session();
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';
}

function require_admin(): void {
    ensure_session();
    if (!is_admin()) {
        http_response_code(403);
        exit('Acesso restrito ao administrador.');
    }
}
