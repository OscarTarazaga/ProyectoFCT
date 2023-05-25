<?php
// Abrimos la sesión para que todo se pueda usar en las diferentes páginas a las que puede acceder cada usuario
session_start();
// Definimos las variables de horario_inicio y horario_fin que usaremos mas adelante
$horario_inicio = "";
$horario_fin = "";
// Habilitamos la conexión con la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

// Definimos un array asociativo para traducir los días de la semana de inglés a español
$dias_semana = array(
    "Monday" => "Lunes",
    "Tuesday" => "Martes",
    "Wednesday" => "Miércoles",
    "Thursday" => "Jueves",
    "Friday" => "Viernes"
);

// Comprobamos si el usuario ha iniciado sesión correctamente
if (!isset($_SESSION['dni'])) {
    header("Location: medico_login.php");
    exit;
}

// Recuperamos el dni del médico de la sesión
$dni = $_SESSION['dni'];

$query = "SELECT horario_inicio, horario_fin FROM doctores WHERE dni='$dni'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $horario_inicio = $row['horario_inicio'];
    $horario_fin = $row['horario_fin'];
} else {
    echo "Error al obtener el horario del médico.";
    exit;
}

// Consultamos las citas asignadas al médico para la semana actual
$fecha_actual = date("Y-m-d");
$fecha_inicio_semana = date("Y-m-d", strtotime("last Monday", strtotime($fecha_actual)));
$fecha_fin_semana = date("Y-m-d", strtotime("next Friday", strtotime($fecha_actual)));
$query_citas = "SELECT cita.hora, cita.dia, pacientes.nombre, pacientes.apellidos FROM cita 
                INNER JOIN pacientes ON cita.dni_paciente = pacientes.dni
                WHERE cita.dni_doctor='$dni' AND cita.dia BETWEEN '$fecha_inicio_semana' AND '$fecha_fin_semana'";
$result_citas = mysqli_query($conexion, $query_citas);

// Creamos un array asociativo para almacenar las citas
$citas = array();
while ($row_citas = mysqli_fetch_assoc($result_citas)) {
    $dia = $row_citas['dia'];
    $hora = $row_citas['hora'];
    $citas[$dia][$hora] = $row_citas['nombre'] . " " . $row_citas['apellidos'];
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/medicocss.css">
    <title>Horario</title>
    <!--<Aquí he definido el estilo de estos elementos ya que no me dejaba hacerlo desde el css que hay desde un inicio auqnque si aplica los cambios al reste de 
elementos asociados al css.
En este caso, he definido que el titulo salga en el centro del div, y el formato de la tabla, para que salga dentro de celdas, excepto la primera celda de todas, ya que esta 
aparece sin borde alguno>-->
</head>
<body>
    <div>
        <h1>Horario del doctor</h1>
        <table>
            <tr>
                <th class="a"></th>
                <th><?php echo $dias_semana[date("l", strtotime($fecha_inicio_semana))]; ?></th>
                <th><?php echo $dias_semana[date("l", strtotime($fecha_inicio_semana . " +1 day"))]; ?></th>
                <th><?php echo $dias_semana[date("l", strtotime($fecha_inicio_semana . " +2 days"))]; ?></th>
                <th><?php echo $dias_semana[date("l", strtotime($fecha_inicio_semana . " +3 days"))]; ?></th>
                <th><?php echo $dias_semana[date("l", strtotime($fecha_inicio_semana . " +4 days"))]; ?></th>
            </tr>
            <?php
            // Genera cada fila de la tabla usando como inicio la hora actual que es la variable horario_inicio y usando también el día actual, esto para poder
            // generar las celdas en cada día de la semana desde el lunes hasta el viernes.
            $hora_actual = $horario_inicio;
            while (strtotime($hora_actual) <= strtotime($horario_fin)) {
                echo "<tr>";
                echo "<td>$hora_actual</td>";
                $fecha_comparar = $fecha_inicio_semana;
                for ($j = 0; $j < 5; $j++) {
                    if (isset($citas[$fecha_comparar][$hora_actual])) {
                        $cita = $citas[$fecha_comparar][$hora_actual];
                        echo "<td>$cita</td>";
                    } else {
                        echo "<td></td>";
                    }
                    $fecha_comparar = date("Y-m-d", strtotime($fecha_comparar . " +1 day"));
                }
                echo "</tr>";
                // Esto hace que se imprima el horario en cada celda con una diferencia de media hora.
                $hora_actual = date('H:i:s', strtotime("$hora_actual + 30 minutes"));
            }
            ?>
        </table>
    </div>
    <form action="medico.php" class="volver-btn" method="Post">
        <input type="submit" value="Volver a la selección">
    </form>
</body>
</html>
