<?php
session_start();
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

$dni_paciente = isset($_POST['dni_paciente']) ? $_POST['dni_paciente'] : null;
$nombre_paciente = "";
$apellidos_paciente = "";
$genero_paciente = "";
$edad_paciente = "";
$direccion_paciente = "";
$telefono_paciente = "";
$dni_doctor = "";

if (!empty($dni_paciente)) {
    $query = "SELECT * FROM pacientes WHERE dni = '$dni_paciente'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $nombre_paciente = $row['nombre'];
        $apellidos_paciente = $row['apellidos'];
        $genero_paciente = $row['genero'];
        $edad_paciente = $row['edad'];
        $direccion_paciente = $row['direccion'];
        $telefono_paciente = $row['telefono'];
        $dni_doctor = $row['dni_doctor'];
    } else {
        echo "No se encontró ningún paciente con el DNI $dni_paciente.";
    }
}

if (isset($_POST['guardar'], $_POST['receta'], $_POST['dni_doctor'])) {
    $receta = $_POST['receta'];
    $dni_doctor = $_POST['dni_doctor'];

    // Verificar si el DNI del doctor existe en la tabla "doctores"
    $query = "SELECT * FROM doctores WHERE dni = '".trim($dni_doctor)."'";
    print $query;
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $fecha_actual = date("Y-m-d");

        $query = "INSERT INTO receta (fecha_receta, comentario, dni_paciente, dni_doctor) VALUES (?, ?, ?, ?)";
        $statement = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($statement, "ssss", $fecha_actual, $receta, $dni_paciente, $dni_doctor);
        mysqli_stmt_execute($statement);

        mysqli_stmt_close($statement); // Cerrar la sentencia preparada

        header("Location: medico.php");
        exit();
    } else {
        echo "No se encontró ningún doctor con el DNI $dni_doctor.";
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/panel_css.css">
    <title>Panel de control del médico</title>
</head>
<body>
    <div class="aizqui">
        <h4> Información del paciente (Datos personales)</h4>
        <?php if (!empty($nombre_paciente)) { ?>
            <p>Nombre: <?php echo $nombre_paciente; ?></p>
            <p>Apellidos: <?php echo $apellidos_paciente; ?></p>
            <p>Género: <?php echo $genero_paciente; ?></p>
            <p>Edad: <?php echo $edad_paciente; ?> años</p>
            <p>Dirección: <?php echo $direccion_paciente; ?></p>
            <p>Teléfono: <?php echo $telefono_paciente; ?></p>
            <!--<p>DNI del doctor: <?php echo $dni_doctor; ?> </p>-->
        <?php } ?>
    </div>

    <div class="derecha">
        <h2>Inserción en la tabla Receta</h2>
        <form method="post" action="medico_panel.php">
            <label for="fecha_receta">Fecha de la receta:</label>
            <input type="date" name="fecha_receta" required><br><br>
            
            <input type="hidden" name="dni_paciente" value="<?php echo $dni_paciente; ?>">

            <label for="receta">Receta:</label><br>
            <textarea name="receta" rows="25" cols="90" required></textarea><br><br>

            <label for="dni_doctor">DNI del Doctor:  <?php echo $dni_doctor; ?></label>
            <input type="hidden" name="dni_doctor" value="<?php echo $dni_doctor; ?>"><br><br>

            <input type="submit" name="guardar" value="Insertar Receta">
        </form>

        <form action="medico.php" class="volver-btn" method="post">
            <input type="submit" value="Volver a la selección">
        </form>
    </div>
</body>
</html>
