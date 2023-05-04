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
 
    // Obtenemos el dni del doctor asignado al paciente, ya que esto también hace falta para la inserción de la cita
    $query = "SELECT dni_doctor FROM pacientes WHERE dni = '$dni_paciente'";
    $result = mysqli_query($conexion, $query);
    $row = mysqli_fetch_assoc($result);
    $dni_doctor = $row['dni_doctor'];
 
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

    // Redireccionar a paciente.php al guardar la cita (!!GUARDA LOS DÍAS DEL 1 AL 5¡¡)
    header("Location: paciente.php");
    exit;
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

    <div class="cita">
        <form action="paciente_citamedica.php" class="guardar-cita-btn" method="post">
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
                    $hora_inicio = strtotime('08:00:00');
                    $hora_fin = strtotime('13:30:00');

                    $intervalo = 1800; // Media hora en segundos

                    $dia = date('Y-m-d', strtotime('next Monday'));

                    while ($hora_inicio <= $hora_fin) {
                        $hora = date('H:i', $hora_inicio);

                        echo "<tr>";
                        echo "<td>$hora</td>";

                        for ($i = 1; $i <= 5; $i++) {
                            echo "<td><input type='checkbox' name='horario[]' value='$hora|$i|$dia'></td>";
                        }

                        echo "</tr>";

                        $hora_inicio += $intervalo;
                        $dia = date('Y-m-d', strtotime("next Monday +".($i-1)." days"));
                    }
                ?>
                </tbody>
            </table>
            <input type="hidden" name="hora" id="hora-seleccionada" value="" >
            <button type="submit" name="guardar_cita" id="guardar-cita-btn">Guardar cita</button>
        </form>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const guardarCitaBtn = document.querySelector('#guardar-cita-btn');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                checkboxes.forEach(c => {
                    if (c !== checkbox) {
                        c.checked = false;
                    }
                });
                document.querySelector('#hora-seleccionada').value = checkbox.value;
                guardarCitaBtn.disabled = false;
            });
        });
    </script>
</body>
</html>