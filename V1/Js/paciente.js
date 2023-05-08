
function updateDate() {
    checkDate(); // llamamos a la función checkDate() antes de desactivar el evento submit
    var fechaSeleccionada = document.getElementsByName("Fecha")[0].value;
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
        window.location.href = "paciente.php";
    } else {
        document.getElementById("hora-imprimir").textContent = "";
    }

    document.getElementById("fecha-imprimir").textContent = "Fecha seleccionada: " + fechaSeleccionada;
    document.getElementById("hora-imprimir").textContent = "Hora seleccionada: " + hora + ", día: " + dia + ", fecha: " + fechaSeleccionada;
    document.getElementById("submit-btn").disabled = true;
    document.getElementById("fecha_seleccionada").value = fechaSeleccionada;
    document.forms["cita-form"].submit();
}


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
//<script src="Js/paciente.js"></script>