<?php
require_once 'modelo_pranchas.php';

// Carregar as pranchas
$lista_pranchas = listarPranchas();

// Carregar os grupos de pranchas (para o topo da página)
$lista_grupos_pranchas = listarGruposPranchasPranchas();
?>
