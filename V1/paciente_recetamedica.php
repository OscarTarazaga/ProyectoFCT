<?php
session_start();

$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port); 

if (!$conexion) {
    die("La conexión con la base de datos ha fallado: " . mysqli_connect_error());
}

$id_receta ="";
$fecha_receta ="";
$medicina ="";
$cantidad ="";
$comentario ="";
$dni_paciente ="";
$result = null;

if (isset($_SESSION['dni'])) {
    $dni_paciente = $_SESSION['dni'];
    $query = "SELECT fecha_receta, medicina, cantidad, comentario FROM receta WHERE dni_paciente = '$dni_paciente';";
    $result = mysqli_query($conexion, $query);
}

if ($result && mysqli_num_rows($result) > 0) {
    echo "<div>";
    echo "<h1>Receta medica</h1>";
    while ($row = mysqli_fetch_array($result)) {
        $fecha_receta = $row['fecha_receta'];
        $medicina = $row['medicina'];
        $cantidad = $row['cantidad'];
        $comentario = $row['comentario'];
        echo "<p>Fecha de la receta: $fecha_receta</p>";
        echo "<p>Medicamento a tomar: $medicina</p>";
        echo "<p>Cantidad: $cantidad</p>";
        echo "<p>Comentario del doctor: $comentario</p>";
        echo "<hr>";
    }
    echo "</div>";
} else {
    echo 'No se encontró ninguna receta para el paciente con DNI '. $dni_paciente;
}

if (!$result) {
    printf("Error en la consulta: %s\n", mysqli_error($conexion));
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
    <title>Receta médica</title>
</head>
<body>
    <form action="paciente.php" class="volver-btn" method="post">
        <input type="submit" value="Volver a la selección">
    </form>
</body>
</html>
