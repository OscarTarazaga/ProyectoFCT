<?php
// Abrimos la sesión para poder usar algunos datos que usamos en admin.php
session_start();
// Abrimos la conexión a la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);
// Definimos las variables que almacenarán los datos del doctor
$nombre_doctor = "";
$dni_doctor = "";
$apellidos_doctor = "";
$genero_doctor = "";
$edad = "";
$especialidad = "";
$salario = "";

//  En este if decimos que haga la consulta teniendo en cuenta el dni del doctor, si no, que salte un error donde dice que no se encontro el dni
if (isset($_GET['dni_doctor'])) {
    $dni_doctor = $_GET['dni_doctor'];
    $query = "SELECT * FROM doctores WHERE dni = '$dni_doctor';";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $dni_doctor = $row['dni'];
        $nombre_doctor = $row['nombre'];
        $apellidos_doctor = $row['apellidos'];
        $genero_doctor = $row['genero'];
        $edad = $row['edad'];
        $especialidad = $row['especialidad'];
        $salario = $row['salario'];
    } else {
        echo "<script>alert('No se encontró ningún doctor con el DNI $dni_doctor.')</script>";
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
    <link rel="stylesheet" type="text/css" href="CSS/admincss.css">
    <title>Info del doctor</title>
</head>
<body>
    
    <div>
        <h4> Información del doctor (Datos personales)</h4>
        <?php if (isset($nombre_doctor)) { ?>
            <p>DNI: <?php echo $dni_doctor; ?>.</p>
            <p>Nombre: <?php echo $nombre_doctor; ?>.</p>
            <p>Apellidos: <?php echo $apellidos_doctor; ?>.</p>
            <p>Género: <?php echo $genero_doctor; ?>.</p>
            <p>Edad: <?php echo $edad; ?> años.</p>
            <p>Especialidad: <?php echo $especialidad; ?>.</p>
            <p>Salario: <?php echo $salario; ?> €.</p>
        <?php } ?>
    </div>

<form action="admin.php" class="volver-btn" method="post">
        <input type="submit" value="Volver a la selección">
    </form>
</body>
</html>