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

function listarUsuariosComBateria(): array {
    $cx = conexao();
    $usuarios = [];
    $sql = "SELECT id, nome, email, tipo, tema_preferido,
                   COALESCE(bateria_social,3) AS bateria_social,
                   bateria_atualizado_em
            FROM usuarios
            ORDER BY nome";
    if ($rs = $cx->query($sql)) {
        while ($r = $rs->fetch_assoc()) { $usuarios[] = $r; }
        $rs->close();
    }
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

/* Criação já com bateria (opcional) */
function criarUsuarioComBateria(string $nome, string $email, string $senha_plana, string $tipo, string $tema = 'light', int $bateria = 3): bool {
    if (!tipoValido($tipo)) return false;
    $bateria = max(0, min(5, $bateria));
    $hash = password_hash($senha_plana, PASSWORD_DEFAULT);
    $cx = conexao();
    $st = $cx->prepare("INSERT INTO usuarios (nome, email, senha, tipo, tema_preferido, bateria_social, bateria_atualizado_em) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $st->bind_param("sssssi", $nome, $email, $hash, $tipo, $tema, $bateria);
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

/* BATERIA SOCIAL */
function obterBateriaSocial(int $id_usuario): array {
    $cx = conexao();
    $st = $cx->prepare("SELECT COALESCE(bateria_social,3), bateria_atualizado_em FROM usuarios WHERE id = ? LIMIT 1");
    $st->bind_param("i", $id_usuario);
    $st->execute();
    $st->bind_result($nivel, $quando);
    $out = ['nivel'=>3, 'atualizado_em'=>null];
    if ($st->fetch()) {
        $out['nivel'] = (int)$nivel;
        $out['atualizado_em'] = $quando;
    }
    $st->close(); $cx->close();
    return $out;
}

function atualizarBateriaSocial(int $id_usuario, int $nivel): bool {
    $nivel = max(0, min(5, $nivel));
    $cx = conexao();
    $agora = date('Y-m-d H:i:s');
    $st = $cx->prepare("UPDATE usuarios SET bateria_social = ?, bateria_atualizado_em = ? WHERE id = ?");
    $st->bind_param("isi", $nivel, $agora, $id_usuario);
    $ok = $st->execute();
    $st->close(); $cx->close();
    return $ok;
}
