<?php
// Abrimos la sesión para poder usar algunos datos que usamos en medico.php
session_start();
// Abrimos la conexión a la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);
// Aqui le decimos que no tenga en cuenta el dni del doctor, pues en este caso no nos hace falta
$dni_doctor = isset($_POST['dni_doctor']) ? $_POST['dni_doctor'] : null;
// Definimos las variables que almacenarán los datos del paciente
$dni_paciente = "";
$nombre_paciente = "";
$apellidos_paciente = "";
$genero_paciente = "";
$edad_paciente = "";
$direccion_paciente = "";
$telefono_paciente = "";

//  En este if decimos que haga la consulta teniendo en cuenta el dni del paciente, si no, que salte un error donde dice que no se encontro el dni
if (isset($_POST['dni_paciente'])) {
    $dni_paciente = $_POST['dni_paciente'];
    $query = "SELECT * FROM pacientes WHERE dni = '$dni_paciente';";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $nombre_paciente = $row['nombre'];
        $apellidos_paciente = $row['apellidos'];
        $genero_paciente = $row['genero'];
        $edad_paciente = $row['edad'];
        $direccion_paciente = $row['direccion'];
        $telefono_paciente = $row['telefono'];
    } else {
        echo "No se encontró ningún paciente con el DNI $dni_paciente.";
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/panel_css.css">
    <title>Panel de control del médico</title>
</head>
 <!--<Aquí imprimimos los datos de los pacientes teniendo en cuenta el retorno de la consulta de a la tabla pacientes>-->
<body>
    <div class="aizqui">
        <h4> Información del paciente (Datos personales)</h4>
        <?php if (isset($nombre_paciente)) { ?>
            <p>Nombre: <?php echo $nombre_paciente; ?></p>
            <p>Apellidos: <?php echo $apellidos_paciente; ?></p>
            <p>Género: <?php echo $genero_paciente; ?></p>
            <p>Edad: <?php echo $edad_paciente; ?></p>
            <p>Dirección: <?php echo $direccion_paciente; ?></p>
            <p>Teléfono: <?php echo $telefono_paciente; ?></p>
        <?php } ?>
    </div>

    <div class="bizqui">
        <h4> Motivo de la cita (Dolencias o para que le receten mas de un medicamento)</h4>
        <form action="" method="post">
            <textarea name="motivo_cita" rows="8" cols="50"></textarea>
        </form>
    </div>

    <div class="derecha">
        <h4> Receta (Receta y/o recomendaciones para el paciente)</h4>
        <form action="" method="post">
            <textarea name="receta" rows="8" cols="50"></textarea>
            <br>
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>

</html>