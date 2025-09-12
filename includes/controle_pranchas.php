<?php
require_once 'modelo_pranchas.php';

$idUsuario = (int)$_SESSION['id_usuario'];
$isAdmin   = ($_SESSION['tipo_usuario'] === 'admin');

// Pranchas visíveis para quem está logado
$lista_pranchas = listarPranchasPorUsuario($idUsuario, $isAdmin);

// Grupos de pranchas: só precisa para telas de admin (criar/editar)
$lista_grupos_pranchas = listarGruposPranchasPranchas();
?>