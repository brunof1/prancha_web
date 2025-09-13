BEGIN FILE: C:\Users\Bruno\OneDrive\Documentos\GitHub\prancha_web\includes\controle_usuarios_admin.php
----------------------------------------------------------------------------------------------------
<?php
// includes/controle_usuarios_admin.php
// Processa criação/edição/senha/exclusão e entrega $lista_usuarios + mensagens para a view.

require_once __DIR__ . '/acl.php';
require_admin();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/modelo_usuarios.php';

$mensagem_usuarios = '';
$classe_msg_usuarios = 'alert--success';

function limpa(string $v): string { return trim($v); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        $nome  = limpa($_POST['nome'] ?? '');
        $email = limpa($_POST['email'] ?? '');
        $tipo  = limpa($_POST['tipo'] ?? 'user');
        $tema  = limpa($_POST['tema_preferido'] ?? 'light');
        $senha = $_POST['senha'] ?? '';

        if ($nome === '' || $email === '' || $senha === '') {
            $mensagem_usuarios = 'Preencha nome, email e senha.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensagem_usuarios = 'Email inválido.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (!tipoValido($tipo)) {
            $mensagem_usuarios = 'Tipo inválido.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (strlen($senha) < 6) {
            $mensagem_usuarios = 'A senha deve ter pelo menos 6 caracteres.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (emailJaExiste($email, null)) {
            $mensagem_usuarios = 'Este email já está em uso.';
            $classe_msg_usuarios = 'alert--danger';
        } else {
            if (criarUsuario($nome, $email, $senha, $tipo, in_array($tema, ['light','dark'], true) ? $tema : 'light')) {
                $mensagem_usuarios = 'Usuário criado com sucesso.';
            } else {
                $mensagem_usuarios = 'Falha ao criar usuário.';
                $classe_msg_usuarios = 'alert--danger';
            }
        }
    }

    if ($acao === 'editar') {
        $id    = (int)($_POST['id'] ?? 0);
        $nome  = limpa($_POST['nome'] ?? '');
        $email = limpa($_POST['email'] ?? '');
        $tipo  = limpa($_POST['tipo'] ?? '');
        $tema  = limpa($_POST['tema_preferido'] ?? 'light');

        if ($id <= 0 || $nome === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensagem_usuarios = 'Dados inválidos para edição.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (!tipoValido($tipo)) {
            $mensagem_usuarios = 'Tipo inválido.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (emailJaExiste($email, $id)) {
            $mensagem_usuarios = 'Este email já está em uso por outro usuário.';
            $classe_msg_usuarios = 'alert--danger';
        } else {
            // Proteção: não permitir rebaixar o ÚLTIMO admin
            $usuarioAtual = buscarUsuarioPorId($id);
            if ($usuarioAtual && $usuarioAtual['tipo'] === 'admin' && $tipo !== 'admin' && contarAdmins() <= 1) {
                $mensagem_usuarios = 'Não é possível rebaixar o último administrador.';
                $classe_msg_usuarios = 'alert--danger';
            } else {
                if (atualizarUsuario($id, $nome, $email, $tipo, in_array($tema, ['light','dark'], true) ? $tema : 'light')) {
                    $mensagem_usuarios = 'Usuário atualizado.';
                } else {
                    $mensagem_usuarios = 'Falha ao atualizar usuário.';
                    $classe_msg_usuarios = 'alert--danger';
                }
            }
        }
    }

    if ($acao === 'senha') {
        $id = (int)($_POST['id'] ?? 0);
        $nova = $_POST['nova_senha'] ?? '';
        $conf = $_POST['confirma_senha'] ?? '';

        if ($id <= 0 || $nova === '' || $conf === '') {
            $mensagem_usuarios = 'Preencha a nova senha e a confirmação.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif ($nova !== $conf) {
            $mensagem_usuarios = 'A confirmação não confere.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif (strlen($nova) < 6) {
            $mensagem_usuarios = 'A nova senha deve ter pelo menos 6 caracteres.';
            $classe_msg_usuarios = 'alert--danger';
        } else {
            if (alterarSenhaUsuario($id, $nova)) {
                $mensagem_usuarios = 'Senha redefinida.';
            } else {
                $mensagem_usuarios = 'Falha ao redefinir senha.';
                $classe_msg_usuarios = 'alert--danger';
            }
        }
    }

    if ($acao === 'excluir') {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $mensagem_usuarios = 'ID inválido.';
            $classe_msg_usuarios = 'alert--danger';
        } elseif ($id === (int)$_SESSION['id_usuario']) {
            $mensagem_usuarios = 'Você não pode excluir o próprio usuário durante a sessão.';
            $classe_msg_usuarios = 'alert--danger';
        } else {
            $usr = buscarUsuarioPorId($id);
            if ($usr && $usr['tipo'] === 'admin' && contarAdmins() <= 1) {
                $mensagem_usuarios = 'Não é possível excluir o último administrador.';
                $classe_msg_usuarios = 'alert--danger';
            } else {
                if (excluirUsuario($id)) {
                    $mensagem_usuarios = 'Usuário excluído.';
                } else {
                    $mensagem_usuarios = 'Falha ao excluir usuário.';
                    $classe_msg_usuarios = 'alert--danger';
                }
            }
        }
    }
}

// Sempre recarrega lista para a view
$lista_usuarios = listarUsuarios();
?>
END FILE: C:\Users\Bruno\OneDrive\Documentos\GitHub\prancha_web\includes\controle_usuarios_admin.php
