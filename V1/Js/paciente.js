function updateDate() {
    const fechaSeleccionada = document.querySelector('#fecha_seleccionada').value;
    if (!fechaSeleccionada) {
        alert('Debe seleccionar una fecha y una hora');
        return;
    }
    document.forms['cita-form'].submit();
}
/*
function updateDate() {
    checkDate(); // llamamos a la función checkDate() antes de desactivar el evento submit
    var fechaSeleccionada = document.getElementById("fecha_seleccionada").value;
    var checkboxes = document.getElementsByName("horario[]");
    var horaSeleccionada;
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            horaSeleccionada = checkboxes[i].value;
            break;
        }
    }

    if (horaSeleccionada) {
        var horaSeleccionadaSplit = horaSeleccionada.split("|");
        var hora = horaSeleccionadaSplit[0];
        var dia = horaSeleccionadaSplit[1];
        var mensaje = "Cita guardada para el día " + fechaSeleccionada + " a las " + hora;
        alert(mensaje);
        document.getElementById("submit-btn").removeAttribute("onclick");
        document.forms[0].submit();
    } else {
        alert("Debe seleccionar una hora");
    }
}
*/
    function checkDate() {
        var fechaSeleccionada = document.getElementsByName("Fecha")[0].value;
        var fecha = new Date(fechaSeleccionada);
        var diaSemana = fecha.getDay();
        if (diaSemana === 6 || diaSemana === 0) {
            document.getElementById("submit-btn").disabled = true;
        } else {
            document.getElementById("submit-btn").disabled = false;
        }
    }

// Código de AJAX para la gestión de los checkboxes
$(document).ready(function() {
  $('#fecha_seleccionada').on('change', function() {
    var fechaSeleccionada = $(this).val();
    
    // Enviar una solicitud AJAX al servidor
    $.ajax({
      url: 'V1/procesar_fecha.php',
      type: 'POST',
      data: { fechaSeleccionada: fechaSeleccionada },
      success: function(response) {
        // Deshabilitar los checkboxes según la respuesta del servidor
        var horariosOcupados = JSON.parse(response);
        $('input[type="checkbox"]').each(function() {
          var horaDia = $(this).val().split('|');
          var hora = horaDia[0];
          var dia = horaDia[1];
          
          if (horariosOcupados.hasOwnProperty(dia) && horariosOcupados[dia].includes(hora)) {
            $(this).prop('disabled', true);
          } else {
            $(this).prop('disabled', false);
          }
        });
      }
    });
  });
});

//<script src="Js/paciente.js"></script>