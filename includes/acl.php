<?php
// includes/acl.php
function is_admin(): bool {
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';
}
function require_admin(): void {
    if (!is_admin()) {
        http_response_code(403);
        exit('Acesso negado.');
    }
}
