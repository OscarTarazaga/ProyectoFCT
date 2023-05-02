<?php
// Abrimos la sesión para que los doctores al entrar en este panel, puedan seguir teniendo acceso mediante el dni introducido anteriormente
session_start();

// Creamos la conexión con la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port); 

if(isset($_POST['guardar_cita'])) {
    // Obtenemos la hora seleccionada
    $hora = $_POST['hora'];
    if ($hora == "") {
        echo "<script>alert('Debe seleccionar una hora.')</script>";
        exit;
    }
 
    // Obtenemos el dni del paciente
    $dni_paciente = $_SESSION['dni'];
 
    // Obtenemos el dni del doctor asignado al paciente
    $query = "SELECT dni_doctor FROM pacientes WHERE dni = '$dni_paciente'";
    $result = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($result);
    $dni_doctor = $row['dni_doctor'];
 
    // Obtenemos la fecha actual
    $fecha = date('Y-m-d');
 
    // Obtenemos el día y la hora seleccionados
    $horario = explode("|", $hora);
    $dia = $horario[1];
    $hora = $horario[0];
 
    // Insertamos la cita en la tabla cita
    $query = "INSERT INTO cita (dni_paciente, dni_doctor, dia, hora) VALUES ('$dni_paciente', '$dni_doctor', '$dia', '$hora')";
    $result = mysqli_query($conexion, $query);
 
    if($result) {
       echo "<script>alert('La cita ha sido guardada correctamente.')</script>";
    } else {
       echo "<script>alert('Error al guardar la cita.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="CSS/pacientecss.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario</title>
</head>
<body>
    <h1>Elige la hora a la que desea la consulta</h1>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Definimos la hora de inicio y fin
                $hora_inicio = strtotime('08:00:00');
                $hora_fin = strtotime('13:30:00');

                // Definimos el intervalo de tiempo
                $intervalo = 1800; // Media hora en segundos

                // Obtenemos el día correspondiente al primer checkbox (lunes)
                $dia = date('Y-m-d', strtotime('next Monday'));

                // Iteramos por cada media hora del día
                while ($hora_inicio <= $hora_fin) {
                    // Convertimos la hora a un formato legible
                    $hora = date('H:i', $hora_inicio);

                    // Creamos una fila de la tabla para esta hora
                    echo "<tr>";
                    echo "<td>$hora</td>";

                    // Iteramos por cada día de la semana
                    for ($i = 1; $i <= 5; $i++) {
                        // Creamos un checkbox para este horario
                        echo "<td><input type='checkbox' name='horarios[]' value='$hora|$i|$dia'></td>";
                    }

                    echo "</tr>";

                    // Agregamos el intervalo a la hora de inicio
                    $hora_inicio += $intervalo;

                    // Actualizamos el día correspondiente en base al día de la semana actual
                    $dia = date('Y-m-d', strtotime("next Monday +".($i-1)." days"));
                }
            ?>
            </tbody>
        </table>
        <form action="paciente_citamedica.php" class="guardar-cita-btn" method="post">
            <input type="hidden" name="hora" id="hora-seleccionada" value="" >
            <button type="submit" name="guardar_cita" id="guardar-cita-btn">Guardar cita</button>
        </form>
    </div>
    <!--Mediante el siguiente script, lo que hago es que cuando se pulse un checkbox, el resto se desactiven, es decir, que solo se pueda pulsar uno a la vez-->
    <script>
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const guardarCitaBtn = document.getElementById('guardar-cita-btn');
        const horaSeleccionada = document.getElementById('hora-seleccionada');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
            // Desactivar todos los checkboxes
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            // Activar el checkbox seleccionado
            this.checked = true;

            // Actualizar la hora seleccionada
            horaSeleccionada.textContent = this.value;
            });
        });

        guardarCitaBtn.addEventListener('click', function() {
            // Obtener la hora seleccionada
            const horaSeleccionadaValor = horaSeleccionada.textContent;

            alert('La cita ha sido guardada para las ' + horaSeleccionadaValor);
        });
    </script>

    </div>

    <form action="paciente.php" class="volver-btn" method="post">
        <input type="submit" value="Volver a la selección">
    </form>
</body>
</html>