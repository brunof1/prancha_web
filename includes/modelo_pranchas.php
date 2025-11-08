<?php
require_once '../config/config.php';

function cx_pranchas(): mysqli {
    $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($cx->connect_error) { die('Erro de conexão: ' . $cx->connect_error); }
    $cx->set_charset('utf8mb4');
    @$cx->query("SET collation_connection = 'utf8mb4_unicode_ci'");
    return $cx;
}

function listarPranchasPorUsuario(int $idUsuario, bool $isAdmin): array {
    $cx = cx_pranchas();
    $pranchas = [];

    if ($isAdmin) {
        $sql = "SELECT p.id, p.nome, p.descricao, g.nome AS grupo
                FROM pranchas p
                JOIN grupos_pranchas g ON g.id = p.id_grupo
                ORDER BY p.nome";
        if ($rs = $cx->query($sql)) {
            while ($row = $rs->fetch_assoc()) {
                // garante as chaves esperadas
                $pranchas[] = [
                    'id'        => (int)$row['id'],
                    'nome'      => $row['nome'],
                    'descricao' => (string)($row['descricao'] ?? ''),
                    'grupo'     => $row['grupo'],
                ];
            }
            $rs->close();
        }
    } else {
        $sql = "SELECT p.id, p.nome, p.descricao, g.nome AS grupo
                FROM pranchas p
                JOIN grupos_pranchas g ON g.id = p.id_grupo
                JOIN pranchas_usuarios pu ON pu.id_prancha = p.id
                WHERE pu.id_usuario = ?
                ORDER BY p.nome";
        if ($st = $cx->prepare($sql)) {
            $st->bind_param("i", $idUsuario);
            $st->execute();
            $st->store_result();

            $st->bind_result($id, $nome, $descricao, $grupo);
            while ($st->fetch()) {
                $pranchas[] = [
                    'id'        => $id,
                    'nome'      => $nome,
                    'descricao' => (string)$descricao,
                    'grupo'     => $grupo,
                ];
            }
            $st->close();
        }
    }

    $cx->close();
    return $pranchas;
}

function usuarioPodeVerPrancha(int $idPrancha, int $idUsuario, bool $isAdmin): bool {
    if ($isAdmin) return true;
    $cx = cx_pranchas();
    $sql = "SELECT 1 FROM pranchas_usuarios WHERE id_prancha = ? AND id_usuario = ? LIMIT 1";
    $st = $cx->prepare($sql);
    $st->bind_param("ii", $idPrancha, $idUsuario);
    $st->execute();
    $st->store_result();
    $ok = $st->num_rows > 0;
    $st->close();
    $cx->close();
    return $ok;
}

function listarUsuariosNaoAdmin(): array {
    $cx = cx_pranchas();
    $out = [];
    $rs = $cx->query("SELECT id, nome, email FROM usuarios WHERE tipo <> 'admin' ORDER BY nome");
    if ($rs) { while ($r = $rs->fetch_assoc()) { $out[] = $r; } $rs->close(); }
    $cx->close();
    return $out;
}

function listarUsuariosDaPrancha(int $idPrancha): array {
    $cx = cx_pranchas();
    $ids = [];
    $st = $cx->prepare("SELECT id_usuario FROM pranchas_usuarios WHERE id_prancha = ?");
    $st->bind_param("i", $idPrancha);
    $st->execute();
    $st->bind_result($uid);
    while ($st->fetch()) { $ids[] = $uid; }
    $st->close(); $cx->close();
    return $ids;
}

function listarPranchas() {
    $conexao = cx_pranchas();
    $pranchas = [];

    $sql = "SELECT p.id, p.nome, g.nome AS grupo FROM pranchas p 
            INNER JOIN grupos_pranchas g ON p.id_grupo = g.id
            ORDER BY p.nome";

    $resultado = $conexao->query($sql);

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $pranchas[] = [
                'id' => $linha['id'],
                'nome' => $linha['nome'],
                'grupo' => $linha['grupo']
            ];
        }
        $resultado->close();
    }

    $conexao->close();
    return $pranchas;
}

function listarGruposPranchasPranchas() {
    $conexao = cx_pranchas();
    $grupos = [];
    $resultado = $conexao->query("SELECT id, nome FROM grupos_pranchas ORDER BY nome");

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $grupos[] = $linha;
        }
        $resultado->close();
    }

    $conexao->close();
    return $grupos;
}

function listarCartoes() {
    $conexao = cx_pranchas();
    $cartoes = [];
    $resultado = $conexao->query("SELECT id, titulo, imagem, texto_alternativo FROM cartoes ORDER BY titulo");

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $cartoes[] = $linha;
        }
        $resultado->close();
    }

    $conexao->close();
    return $cartoes;
}

function salvarPrancha(string $nome, string $descricao, int $id_grupo, array $ordem_cartoes, array $usuariosSelecionados): bool {
    $cx = cx_pranchas();
    $cx->begin_transaction();
    try {
        $sql = "INSERT INTO pranchas (nome, descricao, id_grupo) VALUES (?, ?, ?)";
        $st = $cx->prepare($sql);
        $st->bind_param("ssi", $nome, $descricao, $id_grupo);
        $st->execute();
        $id_prancha = $cx->insert_id;
        $st->close();

        foreach ($ordem_cartoes as $i => $id_cartao) {
            $ordem = $i + 1;
            $st2 = $cx->prepare("INSERT INTO pranchas_cartoes (id_prancha, id_cartao, ordem) VALUES (?, ?, ?)");
            $st2->bind_param("iii", $id_prancha, $id_cartao, $ordem);
            $st2->execute(); $st2->close();
        }

        if (!empty($usuariosSelecionados)) {
            $st3 = $cx->prepare("INSERT IGNORE INTO pranchas_usuarios (id_prancha, id_usuario) VALUES (?, ?)");
            foreach ($usuariosSelecionados as $uid) {
                $u = (int)$uid;
                if ($u > 0) { $st3->bind_param("ii", $id_prancha, $u); $st3->execute(); }
            }
            $st3->close();
        }

        $cx->commit(); $cx->close();
        return true;
    } catch (Exception $e) {
        $cx->rollback(); $cx->close(); return false;
    }
}

function buscarPranchaPorId($id_prancha) {
    $conexao = cx_pranchas();
    $sql = "SELECT id, nome, descricao, id_grupo FROM pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_prancha);
    $comando->execute();
    $comando->bind_result($id, $nome, $descricao, $id_grupo);
    if ($comando->fetch()) {
        $prancha = [
            'id' => $id,
            'nome' => $nome,
            'descricao' => $descricao,
            'id_grupo' => $id_grupo
        ];
    } else {
        $prancha = null;
    }
    $comando->close();
    $conexao->close();
    return $prancha;
}

function buscarCartoesDaPrancha($id_prancha) {
    $conexao = cx_pranchas();
    $cartoes = [];

    $sql = "SELECT id_cartao FROM pranchas_cartoes WHERE id_prancha = ? ORDER BY ordem ASC";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_prancha);
    $comando->execute();
    $comando->bind_result($id_cartao);

    while ($comando->fetch()) {
        $cartoes[] = $id_cartao;
    }

    $comando->close();
    $conexao->close();
    return $cartoes;
}

function buscarCartoesPorIds($ids) {
    if (empty($ids)) return [];

    $conexao = cx_pranchas();

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $tipos = str_repeat('i', count($ids));

    $sql = "SELECT id, titulo, imagem, texto_alternativo FROM cartoes WHERE id IN ($placeholders)";
    $comando = $conexao->prepare($sql);

    $bind_params = array_merge([$tipos], $ids);
    call_user_func_array([$comando, 'bind_param'], refValues($bind_params));

    $comando->execute();
    $comando->store_result();

    $cartoes = [];
    $id = $titulo = $imagem = $texto_alternativo = "";

    $comando->bind_result($id, $titulo, $imagem, $texto_alternativo);

    while ($comando->fetch()) {
        $cartoes[$id] = [
            'id' => $id,
            'titulo' => $titulo,
            'imagem' => $imagem,
            'texto_alternativo' => $texto_alternativo
        ];
    }

    $comando->close();
    $conexao->close();

    // Retorna os resultados na ordem dos IDs passados
    $ordenados = [];
    foreach ($ids as $id_busca) {
        if (isset($cartoes[$id_busca])) {
            $ordenados[] = $cartoes[$id_busca];
        }
    }

    return $ordenados;
}

// Função auxiliar necessária para bind_param dinâmico
function refValues($arr) {
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

function atualizarPrancha(int $id_prancha, string $nome, string $descricao, int $id_grupo, array $ordem_cartoes, array $usuariosSelecionados): bool {
    $cx = cx_pranchas();
    $cx->begin_transaction();
    try {
        $st = $cx->prepare("UPDATE pranchas SET nome = ?, descricao = ?, id_grupo = ? WHERE id = ?");
        $st->bind_param("ssii", $nome, $descricao, $id_grupo, $id_prancha);
        $st->execute(); $st->close();

        // cartões
        $stDel = $cx->prepare("DELETE FROM pranchas_cartoes WHERE id_prancha = ?");
        $stDel->bind_param("i", $id_prancha); $stDel->execute(); $stDel->close();

        foreach ($ordem_cartoes as $i => $id_cartao) {
            $ordem = $i + 1;
            $st2 = $cx->prepare("INSERT INTO pranchas_cartoes (id_prancha, id_cartao, ordem) VALUES (?, ?, ?)");
            $st2->bind_param("iii", $id_prancha, $id_cartao, $ordem);
            $st2->execute(); $st2->close();
        }

        // usuários vinculados
        $stDelU = $cx->prepare("DELETE FROM pranchas_usuarios WHERE id_prancha = ?");
        $stDelU->bind_param("i", $id_prancha); $stDelU->execute(); $stDelU->close();

        if (!empty($usuariosSelecionados)) {
            $st3 = $cx->prepare("INSERT IGNORE INTO pranchas_usuarios (id_prancha, id_usuario) VALUES (?, ?)");
            foreach ($usuariosSelecionados as $uid) {
                $u = (int)$uid;
                if ($u > 0) { $st3->bind_param("ii", $id_prancha, $u); $st3->execute(); }
            }
            $st3->close();
        }

        $cx->commit(); $cx->close();
        return true;
    } catch (Exception $e) {
        $cx->rollback(); $cx->close(); return false;
    }
}

function excluirPrancha($id_prancha) {
    $conexao = cx_pranchas();

    // Remove vínculos de cartões
    $sql_delete_cartoes = "DELETE FROM pranchas_cartoes WHERE id_prancha = ?";
    $comando1 = $conexao->prepare($sql_delete_cartoes);
    $comando1->bind_param("i", $id_prancha);
    $comando1->execute();
    $comando1->close();

    // Remove vínculos de usuários (correção)
    $sql_delete_users = "DELETE FROM pranchas_usuarios WHERE id_prancha = ?";
    $comandoUsers = $conexao->prepare($sql_delete_users);
    $comandoUsers->bind_param("i", $id_prancha);
    $comandoUsers->execute();
    $comandoUsers->close();

    // Agora deleta a prancha
    $sql_delete_prancha = "DELETE FROM pranchas WHERE id = ?";
    $comando2 = $conexao->prepare($sql_delete_prancha);
    $comando2->bind_param("i", $id_prancha);
    $resultado = $comando2->execute();
    $comando2->close();

    $conexao->close();
    return $resultado;
}
?>
