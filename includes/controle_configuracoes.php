<?php
// Processa os POSTs da página de configurações, carrega prefs para o formulário.
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/modelo_preferencias.php';

$mensagem_perfil       = '';
$mensagem_senha        = '';
$mensagem_preferencias = '';
$classe_msg_perfil     = 'alert--success';
$classe_msg_senha      = 'alert--success';
$classe_msg_prefs      = 'alert--success';

$id_usuario = (int) $_SESSION['id_usuario'];
$preferencias = obterPreferenciasUsuario($id_usuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';

    if ($acao === 'perfil') {
        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nome === '' || $email === '') {
            $mensagem_perfil   = 'Preencha nome e email.';
            $classe_msg_perfil = 'alert--danger';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensagem_perfil   = 'Email inválido.';
            $classe_msg_perfil = 'alert--danger';
        } else {
            $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
            $cx->set_charset('utf8mb4');

            // Verifica unicidade do email (exceto o próprio usuário)
            $st = $cx->prepare("SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1");
            $st->bind_param("si", $email, $id_usuario);
            $st->execute(); $st->store_result();
            if ($st->num_rows > 0) {
                $mensagem_perfil   = 'Este email já está em uso.';
                $classe_msg_perfil = 'alert--danger';
            } else {
                $st->close();
                $st2 = $cx->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $st2->bind_param("ssi", $nome, $email, $id_usuario);
                if ($st2->execute()) {
                    // Ajuste de encoding defensivo
                    if (!function_exists('mb_check_encoding') || !mb_check_encoding($nome, 'UTF-8')) {
                        $nome = utf8_encode($nome);
                    }
                    $_SESSION['nome_usuario'] = $nome;
                    $mensagem_perfil = 'Dados atualizados com sucesso.';
                } else {
                    $mensagem_perfil   = 'Falha ao atualizar perfil.';
                    $classe_msg_perfil = 'alert--danger';
                }
                $st2->close();
                $cx->close();
            }
        }
    }

    if ($acao === 'senha') {
        $senha_atual   = $_POST['senha_atual'] ?? '';
        $nova_senha    = $_POST['nova_senha'] ?? '';
        $confirma_senha= $_POST['confirma_senha'] ?? '';

        if ($nova_senha === '' || $confirma_senha === '' || $senha_atual === '') {
            $mensagem_senha   = 'Preencha todas as senhas.';
            $classe_msg_senha = 'alert--danger';
        } elseif ($nova_senha !== $confirma_senha) {
            $mensagem_senha   = 'A confirmação não confere.';
            $classe_msg_senha = 'alert--danger';
        } elseif (strlen($nova_senha) < 6) {
            $mensagem_senha   = 'A nova senha deve ter pelo menos 6 caracteres.';
            $classe_msg_senha = 'alert--danger';
        } else {
            // Busca hash atual
            $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
            $cx->set_charset('utf8mb4');
            $st = $cx->prepare("SELECT senha FROM usuarios WHERE id = ? LIMIT 1");
            $st->bind_param("i", $id_usuario);
            $st->execute(); $st->bind_result($hash_atual);
            if ($st->fetch()) {
                if (password_verify($senha_atual, $hash_atual)) {
                    $st->close();
                    $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $up = $cx->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                    $up->bind_param("si", $novo_hash, $id_usuario);
                    if ($up->execute()) {
                        $mensagem_senha = 'Senha alterada com sucesso.';
                    } else {
                        $mensagem_senha   = 'Falha ao salvar a nova senha.';
                        $classe_msg_senha = 'alert--danger';
                    }
                    $up->close();
                } else {
                    $mensagem_senha   = 'Senha atual incorreta.';
                    $classe_msg_senha = 'alert--danger';
                }
            } else {
                $mensagem_senha   = 'Usuário não encontrado.';
                $classe_msg_senha = 'alert--danger';
            }
            $cx->close();
        }
    }

    if ($acao === 'preferencias') {
        $voz_uri         = isset($_POST['voz_uri']) ? trim($_POST['voz_uri']) : null;
        $tts_rate        = (float)($_POST['tts_rate'] ?? 1.0);
        $tts_pitch       = (float)($_POST['tts_pitch'] ?? 1.0);
        $tts_volume      = (float)($_POST['tts_volume'] ?? 1.0);
        $font_base_px    = (int)($_POST['font_base_px'] ?? 16);
        $falar_ao_clicar = isset($_POST['falar_ao_clicar']) ? 1 : 0;

        $ok = salvarPreferenciasUsuario(
            $id_usuario,
            $voz_uri !== '' ? $voz_uri : null,
            $tts_rate,
            $tts_pitch,
            $tts_volume,
            $font_base_px,
            $falar_ao_clicar
        );

        if ($ok) {
            $mensagem_preferencias = 'Preferências salvas.';
            $preferencias = obterPreferenciasUsuario($id_usuario); // recarrega p/ refletir
        } else {
            $mensagem_preferencias = 'Falha ao salvar preferências.';
            $classe_msg_prefs      = 'alert--danger';
        }
    }
}
?>