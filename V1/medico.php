<?php
session_start();

$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

// Definir variables con valores por defecto
$nombre_doctor = '';
$genero_doctor = '';
$pronombre_tratamiento = '';
$dni_doctor = '';

// Obtener el dni del doctor a partir de la variable de sesión si el usuario es un doctor
if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'doctor' && isset($_SESSION['dni'])){
    $dni_doctor = $_SESSION['dni'];
} else {
    // Otras acciones en caso de que el tipo de usuario no sea doctor o el dni no esté definido en la sesión
}

// Obtener el nombre y género del doctor a partir del dni
$query = "SELECT nombre, genero FROM doctores WHERE dni = '$dni_doctor'";
$result = mysqli_query($conexion, $query);

if($result && mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $nombre_doctor = $row['nombre'];
    $genero_doctor = $row['genero'];

    // Determinar el pronombre de tratamiento adecuado según el género
    $pronombre_tratamiento = ($genero_doctor == 'H') ? 'Dr.' : 'Dra.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/medicocss.css">
    <title>Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?></title>
</head>
<body>
    <div>
    <h1> Bienvenido <?php echo $pronombre_tratamiento ?> <?php echo $nombre_doctor ?> </h1>
    <h2> ¿Qué desea hacer? </h2>

    <form method="post">
        <input type="radio" id="horario" name="opcion" value="horario">
        <label for="horario"> Revisar el horario</label><br>
        <input type="radio" id="PanelControl" name="opcion" value="PanelControl">
        <label for="PanelControl"> Ir al panel del paciente</label>
        <select name="pacientes" id="pacientes"> 
            <option value="" disabled selected> Seleccione un paciente </option>
        </select> <br>
        <input type="submit" value="Vamos al panel">
    </form>
    </div>
</body>
</html>
