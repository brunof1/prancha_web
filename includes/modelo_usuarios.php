<?php
// includes/modelo_usuarios.php
require_once __DIR__ . '/../config/config.php';

function conexao(): mysqli {
    $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($cx->connect_error) { die('Erro de conexão: ' . $cx->connect_error); }
    $cx->set_charset('utf8mb4');
    @$cx->query("SET collation_connection = 'utf8mb4_unicode_ci'");
    return $cx;
}

function tiposPermitidos(): array { return ['admin','user']; }
function tipoValido(string $tipo): bool { return in_array($tipo, tiposPermitidos(), true); }

function listarUsuarios(): array {
    $cx = conexao();
    $usuarios = [];
    $rs = $cx->query("SELECT id, nome, email, tipo, tema_preferido FROM usuarios ORDER BY nome");
    if ($rs) { while ($r = $rs->fetch_assoc()) { $usuarios[] = $r; } $rs->close(); }
    $cx->close();
    return $usuarios;
}

function buscarUsuarioPorId(int $id): ?array {
    $cx = conexao();
    $st = $cx->prepare("SELECT id, nome, email, tipo, tema_preferido FROM usuarios WHERE id = ? LIMIT 1");
    $st->bind_param("i", $id);
    $st->execute();
    $st->bind_result($idOut, $nome, $email, $tipo, $tema);
    $usuario = null;
    if ($st->fetch()) {
        $usuario = ['id'=>$idOut,'nome'=>$nome,'email'=>$email,'tipo'=>$tipo,'tema_preferido'=>$tema];
    }
    $st->close(); $cx->close();
    return $usuario;
}

function emailJaExiste(string $email, ?int $ignorarId = null): bool {
    $cx = conexao();
    if ($ignorarId) {
        $st = $cx->prepare("SELECT 1 FROM usuarios WHERE email = ? AND id <> ? LIMIT 1");
        $st->bind_param("si", $email, $ignorarId);
    } else {
        $st = $cx->prepare("SELECT 1 FROM usuarios WHERE email = ? LIMIT 1");
        $st->bind_param("s", $email);
    }
    $st->execute(); $st->store_result();
    $existe = $st->num_rows > 0;
    $st->close(); $cx->close();
    return $existe;
}

function contarAdmins(): int {
    $cx = conexao();
    $rs = $cx->query("SELECT COUNT(*) AS total FROM usuarios WHERE tipo = 'admin'");
    $total = 0;
    if ($rs && ($row = $rs->fetch_assoc())) { $total = (int)$row['total']; }
    $cx->close();
    return $total;
}

function criarUsuario(string $nome, string $email, string $senha_plana, string $tipo, string $tema = 'light'): bool {
    if (!tipoValido($tipo)) return false;
    $hash = password_hash($senha_plana, PASSWORD_DEFAULT);
    $cx = conexao();
    $st = $cx->prepare("INSERT INTO usuarios (nome, email, senha, tipo, tema_preferido) VALUES (?, ?, ?, ?, ?)");
    $st->bind_param("sssss", $nome, $email, $hash, $tipo, $tema);
    $ok = $st->execute();
    $st->close(); $cx->close();
    return $ok;
}

function atualizarUsuario(int $id, string $nome, string $email, string $tipo, string $tema): bool {
    if (!tipoValido($tipo)) return false;
    $cx = conexao();
    $st = $cx->prepare("UPDATE usuarios SET nome = ?, email = ?, tipo = ?, tema_preferido = ? WHERE id = ?");
    $st->bind_param("ssssi", $nome, $email, $tipo, $tema, $id);
    $ok = $st->execute();
    $st->close(); $cx->close();
    return $ok;
}

function alterarSenhaUsuario(int $id, string $senha_plana): bool {
    $hash = password_hash($senha_plana, PASSWORD_DEFAULT);
    $cx = conexao();
    $st = $cx->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $st->bind_param("si", $hash, $id);
    $ok = $st->execute();
    $st->close(); $cx->close();
    return $ok;
}

function excluirUsuario(int $id): bool {
    $cx = conexao();
    $st = $cx->prepare("DELETE FROM usuarios WHERE id = ?");
    $st->bind_param("i", $id);
    $ok = $st->execute();
    $st->close(); $cx->close();
    return $ok;
}
?>