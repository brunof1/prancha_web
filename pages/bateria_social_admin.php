<?php
include '../includes/cabecalho.php';
require_once '../includes/acl.php';
require_admin();
require_once '../includes/modelo_usuarios.php';

$usuarios = listarUsuariosComBateria();

function rotulo_bat($n) {
    $map = [
        0 => ['😵','Esgotado'],
        1 => ['😟','Baixíssimo'],
        2 => ['🙁','Baixo'],
        3 => ['😐','Neutro'],
        4 => ['🙂','Bom'],
        5 => ['🤩','Cheio'],
    ];
    return $map[$n] ?? ['😐','Neutro'];
}
?>
<link rel="stylesheet" href="../assets/css/bateria.css">

<h2>Visão Geral — Bateria Social</h2>
<p class="help">Apenas administradores veem esta página.</p>

<table class="tabela" aria-describedby="legend-bateria">
  <colgroup>
    <col class="nome" />
    <col class="email" />
    <col class="tipo" />
    <col class="tema" />
    <col class="bat" />
    <col class="atual" />
  </colgroup>
  <thead>
    <tr>
      <th>Nome</th>
      <th>Email</th>
      <th>Tipo</th>
      <th>Tema</th>
      <th>Bateria</th>
      <th>Atualizado em</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($usuarios as $u): 
      $n = isset($u['bateria_social']) ? (int)$u['bateria_social'] : 3;
      [$emoji, $txt] = rotulo_bat($n);
      $quando = $u['bateria_atualizado_em'] ? date('d/m/Y H:i', strtotime($u['bateria_atualizado_em'])) : '—';
    ?>
    <tr>
      <td><?php echo htmlspecialchars($u['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($u['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($u['tipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($u['tema_preferido'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
      <td>
        <span class="bat-pill lvl-<?php echo $n; ?>">
          <span aria-hidden="true" class="bat-pill__emoji"><?php echo $emoji; ?></span>
          <span class="bat-pill__text"><?php echo $txt; ?></span>
        </span>
      </td>
      <td><?php echo $quando; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p id="legend-bateria" class="help">Legenda: 0=Esgotado … 5=Cheio.</p>

<?php include '../includes/rodape.php'; ?>
