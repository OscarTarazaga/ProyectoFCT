<?php
// Abrimos la sesión para que los pacientes al entrar en este panel, puedan seguir teniendo acceso mediante el dni introducido anteriormente
session_start();

// Creamos la conexión con la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port); 
// Aqui declaro los dias de la semana para a continuacion meterlos en un array para asociar cada día con la posicion deseada
$l="Lunes";
$m="Martes";
$x="Miercoles";
$j="Jueves";
$v="Viernes";

$dias_semana = array(1 => "Lunes", 2 => "Martes", 3 => "Miercoles", 4 => "Jueves", 5 => "Viernes");

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

    // Verificamos si ya existe una cita programada para ese día y hora
    $query = "SELECT * FROM cita WHERE dia = '$dia' AND hora = '$hora'";
    $result = mysqli_query($conexion, $query);
    $num_rows = mysqli_num_rows($result);
    
    // En este if lo que hago es que si existe una cita programada para esa hora, salga este mensaje de error y nos permite agregar otra cita
    if ($num_rows > 0) {
        echo "<script>alert('Lo sentimos, ya hay una cita programada para el día $dia y la hora $hora. Por favor, seleccione otra hora.')</script>";
    } else {
        // Insertamos la cita en la tabla cita
        $query = "INSERT INTO cita (dni_paciente, dni_doctor, dia, hora) VALUES ('$dni_paciente', '$dni_doctor', '$dia', '$hora')";
        $result = mysqli_query($conexion, $query);
    
        if($result) {
           echo "<script>alert('La cita ha sido guardada correctamente.')</script>";
        } else {
           echo "<script>alert('Error al guardar la cita.')</script>";
        }
        // Redireccionar a paciente.php al guardar la cita (!!GUARDA LOS DÍAS DEL 1 AL 5, A NO SER QUE ESTE COGIENDO LA DECHA ACTUAL, LO CUAL SERIA RARO PERO MIRAR POR SI ACASO¡¡)
        header("Location: paciente.php");
        exit;
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

    <div class="cita">
        <form action="paciente_citamedica.php" class="guardar-cita-btn" method="post">
            <table>
                <thead>
                <tr>
                    <th>Hora</th>
                    <th><?php echo $l ?></th>
                    <th><?php echo $m ?></th>
                    <th><?php echo $x ?></th>
                    <th><?php echo $j ?></th>
                    <th><?php echo $v ?></th>
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
                            echo "<td><input type='checkbox' name='horario[]' value='$hora|$dias_semana[$i]|$dia'></td>";
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