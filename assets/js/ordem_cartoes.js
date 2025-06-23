document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('input[name="cartoes[]"]');
    const ordemInput = document.getElementById('ordem_cartoes');
    let ordem = [];

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const id = checkbox.value;

            if (checkbox.checked) {
                // Se marcou, adiciona ao final
                ordem.push(id);
            } else {
                // Se desmarcou, remove da ordem
                ordem = ordem.filter(function (item) {
                    return item !== id;
                });
            }

            // Atualiza o campo hidden
            ordemInput.value = ordem.join(',');
        });
    });
});
