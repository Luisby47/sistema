document.addEventListener('DOMContentLoaded', function () {
    const tipoValor = document.getElementById('tipo_valor'); // Campo Select
    const valorInput = document.getElementById('valor_input'); // Campo Number

    if (tipoValor && valorInput) {
        tipoValor.addEventListener('change', function () {
            const selectedValue = tipoValor.value;

            if (selectedValue === 'PORC') {
                valorInput.placeholder = '0.00 - 100.00'; // Placeholder para Porcentaje
            } else {
                valorInput.placeholder = '0.00'; // Placeholder para Monto
            }
        });
    }
});
