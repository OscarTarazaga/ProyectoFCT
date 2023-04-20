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

// Definimos las variables que almacenarán los datos del paciente
$dni_paciente = "";
$nombre_paciente = "";
$apellidos_paciente = "";
$genero_paciente = "";
$edad_paciente = "";
$direccion_paciente = "";
$telefono_paciente = "";

//  En este if decimos que haga la consulta teniendo en cuenta el dni del paciente, si no, que salte un error donde dice que no se encontro el dni
if (isset($_SESSION['dni'])) {
    $dni_paciente = $_SESSION['dni'];
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
    <link rel="stylesheet" type="text/css" href="CSS/pacientecss.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Personales</title>
</head>
<body>

    <div>
        <h1>Datos Personales</h1>
        <p>Nombre: <?php echo $nombre_paciente; ?></p>
        <p>Apellidos: <?php echo $apellidos_paciente; ?></p>
        <p>Género: <?php echo $genero_paciente; ?></p>
        <p>Edad: <?php echo $edad_paciente; ?> años</p>
        <p>Dirección: <?php echo $direccion_paciente; ?></p>
        <p>Teléfono: <?php echo $telefono_paciente; ?></p>
    </div>
    
    <form action="paciente.php" class="volver-btn" method="post">
        <input type="submit" value="Volver a la selección">
    </form>


</body>
</html>
