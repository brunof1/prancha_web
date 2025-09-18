// assets/js/pranchas_admin.js
function confirmarExclusaoPrancha(id){
  return confirm('Tem certeza que deseja excluir a prancha #' + id + '? Esta ação é irreversível.');
}

function confirmarExclusaoGrupo(id){
  return confirm('Tem certeza que deseja excluir o grupo #' + id + '?\nSe existirem pranchas vinculadas, remova ou mova-as antes.');
}
