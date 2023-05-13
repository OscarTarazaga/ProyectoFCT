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

if(isset($_POST['fecha_seleccionada'])) {
    $fecha_seleccionada = $_POST['fecha_seleccionada'];
    $horarios = $_POST['horario'];

    // Verificar que se ha seleccionado una fecha y hora
    if (empty($fecha_seleccionada) || empty($horarios)) {
        echo "<script>alert('Debe seleccionar una fecha y una hora')</script>";
        exit();
    }

    foreach ($horarios as $horario) {
        $hora_dia = explode("|", $horario);
        $hora = $hora_dia[0];
        $dia = $hora_dia[1];

        $fecha = date('Y-m-d H:i:s', strtotime("$fecha_seleccionada $hora"));
    }    

    // Verificar si hay una cita agendada para esa hora y día
    $query = "SELECT * FROM cita WHERE dia = '$fecha' AND hora = '$hora' AND dni_paciente = '$dni_paciente'";
    $result = mysqli_query($conexion, $query);

    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Ya hay una cita agendada para esa hora y día')</script>";
        exit();
    } 

    // Verificar si los datos a insertar son iguales a los que ya existen en la base de datos
    $query = "SELECT * FROM cita WHERE dni_paciente = '$dni_paciente' AND dni_doctor = '$dni_doctor' AND dia = '$fecha' AND hora = '$hora'";
    $result = mysqli_query($conexion, $query);

    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Ya existe una cita con los mismos datos')</script>";
        exit();
    } 

    // Insertar la cita en la base de datos
    $query = "INSERT INTO cita (dni_paciente, dni_doctor, dia, hora) VALUES ('$dni_paciente', '$dni_doctor', '$fecha', '$hora')";
    mysqli_query($conexion, $query);

    
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
    <script src="Js/paciente.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario</title>
</head>
<body>
    <h1>Elige la hora a la que desea la consulta</h1>

    <div class="cita">
        <form method="post" name="cita-form" action="">
        <label for="fecha_seleccionada">Seleccione la fecha de la cita:</label>
        <input type="date" id="fecha_seleccionada" name="fecha_seleccionada" min="<?php echo date('Y-m-d'); ?>" onchange="checkDate()">  

            <table>
                <thead>
                <tr>
                    <th>Hora</th>
                    <th>Dia</th>
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
    <!--
        <p>Fecha seleccionada: <span id="fecha-imprimir"></span></p>
        <p>Hora seleccionada: <span id="hora-imprimir"></span></p>
    -->
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