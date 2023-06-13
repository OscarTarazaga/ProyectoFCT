<?php
session_start();

$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

$dni_paciente = $_SESSION['dni'];

$query = "SELECT dni_doctor FROM pacientes WHERE dni = '$dni_paciente'";
$result = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($result);
$dni_doctor = $row['dni_doctor'];

if (isset($_POST['fecha_seleccionada'])) {
    $fecha_seleccionada = $_POST['fecha_seleccionada'];
    $horarios = $_POST['horario'];

    // Verificar que se ha seleccionado una fecha y hora
    if (empty($fecha_seleccionada) || empty($horarios)) {
        echo "<script>alert('Debe seleccionar una fecha y una hora')</script>";        
        flush(); // Envía el buffer de salida al navegador

        // Espera 1 segundo y redirige mediante JavaScript después de mostrar el mensaje
        echo "<script>
            setTimeout(function() {
                window.location.href = 'paciente_citamedica.php';
            }, 100);
        </script>";
        exit();
    }

    // Obtener todos los horarios seleccionados
    $horas_dias = array();
    foreach ($horarios as $horario) {
        $hora_dia = explode("|", $horario);
        $hora = $hora_dia[0];
        $dia = $hora_dia[1];
        $horas_dias[] = array('hora' => $hora, 'dia' => $dia);
    }

    // Verificar si la fecha seleccionada es un sábado o domingo
    $fecha_seleccionada_dia_semana = date('N', strtotime($fecha_seleccionada));
    if ($fecha_seleccionada_dia_semana >= 6) {
        // Almacena el mensaje en una variable
        $mensaje = "No se pueden seleccionar los días sábados y domingos";

        // Muestra el mensaje
        echo "<script>alert('$mensaje')</script>";
        flush(); // Envía el buffer de salida al navegador

        // Espera 1 segundo y redirige mediante JavaScript después de mostrar el mensaje
        echo "<script>
            setTimeout(function() {
                window.location.href = 'paciente_citamedica.php';
            }, 100);
        </script>";
        exit();
    }

    // Verificar si la fecha seleccionada es anterior a la fecha y hora actual
    $fecha_actual = date('Y-m-d H:i:s');
    $fecha_seleccionada_hora_minima = date('Y-m-d H:i:s', strtotime("$fecha_seleccionada {$horas_dias[0]['hora']}"));
    if ($fecha_seleccionada_hora_minima < $fecha_actual) {
        // Almacena el mensaje en una variable
        $mensaje = "No se puede seleccionar una fecha y hora anterior a la actual";

        // Muestra el mensaje
        echo "<script>alert('$mensaje')</script>";
        flush(); // Envía el buffer de salida al navegador

        // Espera 1 segundo y redirige mediante JavaScript después de mostrar el mensaje
        echo "<script>
            setTimeout(function() {
                window.location.href = 'paciente_citamedica.php';
            }, 100);
        </script>";
        exit();
    }

// Verificar si hay una cita agendada para alguna de las horas y días seleccionados
foreach ($horas_dias as $horas_dia) {
    $hora = $horas_dia['hora'];
    $dia = $horas_dia['dia'];

    $fecha = date('Y-m-d H:i:s', strtotime("$fecha_seleccionada $hora"));

    $query = "SELECT * FROM cita WHERE dni_doctor = '$dni_doctor' AND dia = '$fecha_seleccionada' AND hora = '$hora'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('La hora seleccionada ya está ocupada. Por favor, elija otra hora.')</script>";
        flush(); // Envía el buffer de salida al navegador

        // Espera 1 segundo y redirige mediante JavaScript después de mostrar el mensaje
        echo "<script>
            setTimeout(function() {
                window.location.href = 'paciente_citamedica.php';
            }, 100);
        </script>";
        exit();
    }        
}

    // Insertar la cita en la base de datos
    foreach ($horas_dias as $horas_dia) {
        $hora = $horas_dia['hora'];
        $dia = $horas_dia['dia'];

        $fecha = date('Y-m-d H:i:s', strtotime("$fecha_seleccionada $hora"));

        $query = "INSERT INTO cita (dni_paciente, dni_doctor, dia, hora) VALUES ('$dni_paciente', '$dni_doctor', '$fecha', '$hora')";
        mysqli_query($conexion, $query);
    }

    // Redirigir al usuario a la página paciente.php después de la inserción
    $url = 'paciente.php';
    header("Location: $url");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="CSS/pacientecss.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="jquery-3.7.0.min.js"></script>
    <script src="ajax.js"></script>
    <script src="Js/paciente.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario</title>
</head>

<body>
    <h1>Elige la hora a la que desea la consulta</h1>

    <div class="cita">
    <form method="post" name="cita-form" action="paciente_citamedica.php">
            <label for="fecha_seleccionada">Seleccione la fecha de la cita:</label>
            <input type="date" id="fecha_seleccionada" name="fecha_seleccionada" min="<?php echo date('Y-m-d'); ?>"
                onchange="checkDate()">

            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Día</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $hora_inicio = strtotime('08:00:00');
                    $hora_fin = strtotime('13:30:00');

                    $intervalo = 1800; // Media hora en segundos

                    $dia = date('Y-m-d', strtotime('next Monday'));

                    while ($hora_inicio <= $hora_fin) {
                        $hora = date('H:i', $hora_inicio);

                        echo "<tr>";
                        echo "<td>$hora</td>";
                        echo "<td><input type='checkbox' name='horario[]' value='$hora|$dia'></td>";

                        echo "</tr>";

                        $hora_inicio += $intervalo;
                    }
                    ?>
                </tbody>
            </table>
            <input type="hidden" name="hora_seleccionada" id="hora_seleccionada" value="">
            <button id="submit-btn" type="button" onclick="updateDate()">Guardar cita</button>
        </form>
    </div>
    <form action="paciente.php" class="volver-btn" method="post">
        <input type="submit" value="Volver a la selección">
    </form>
    <script>
        // Script para que solo se pueda tener activo un checkbox a la vez
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const submitBtn = document.querySelector('#submit-btn');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                checkboxes.forEach(c => {
                    if (c !== checkbox) {
                        c.checked = false;
                    }
                });
                document.querySelector('#hora_seleccionada').value = checkbox.value;
                submitBtn.disabled = false;
            });
        });
    
    </script>
</body>
</html>