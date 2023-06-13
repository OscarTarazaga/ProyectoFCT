// Ajax para obtener los horarios ocupados
function checkDate() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const fechaSeleccionada = document.getElementById('fecha_seleccionada').value;
    $.ajax({
        url: 'procesar_fecha.php',
        type: 'POST',
        data: {
            fecha_seleccionada: fechaSeleccionada
        },
        dataType: 'json',
        success: function(response) {
            // Habilitar todos los checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });

            // Deshabilitar los checkboxes de los horarios ocupados
            const horariosOcupados = response.horarios_ocupados;
            for (const dia in horariosOcupados) {
                horariosOcupados[dia].forEach(hora => {
                    const checkbox = document.querySelector(`input[value="${hora}|${dia}"]`);
                    if (checkbox) {
                        checkbox.disabled = true;
                    }
                });
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}