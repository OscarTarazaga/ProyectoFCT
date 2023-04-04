<?php
// Abrimos la sesión para que todo se pueda usar en las diferentes páginas a las que puede acceder cada usuario
session_start();
// Habilitamos la conexión con la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

// Definir variables con valores por defecto, estas variables las usaremos mas a delante.
$nombre_doctor = '';
$genero_doctor = '';
$pronombre_tratamiento = '';
$dni_doctor = '';

// Obtener el dni del doctor a partir de la variable de sesión si el usuario es un doctor
if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'doctor' && isset($_SESSION['dni'])){
    $dni_doctor = $_SESSION['dni'];
}

// Obtener el nombre y género del doctor a partir del dni para poder imprimir el mensaje de bienvenida
$query = "SELECT nombre, genero FROM doctores WHERE dni = '$dni_doctor'";
$result = mysqli_query($conexion, $query);
// En este if decimos que guarde el valor de la salida de la consulta de la base de datos, esto se define así, ya que, siempre van a salir mas de 2 valores de la consulta 
if($result && mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $nombre_doctor = $row['nombre'];
    $genero_doctor = $row['genero'];

    // Determinar el pronombre de tratamiento adecuado según el género
    $pronombre_tratamiento = ($genero_doctor == 'H') ? 'Dr.' : 'Dra.';
}
// Este if es para la opción para ir al horario del doctor
if(isset($_POST['opcion']) && $_POST['opcion'] == 'horario') {
    header('Location: medico_horario.php?dni=' . $dni_doctor);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/medicocss.css">
<!--<Aquí definimos el saludo, dependiendo del genero del medico, saldrá dr o dra>-->
    <title>Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?></title>
</head>
<body>
    <div>
    <h1> Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?> </h1>
    <h2> ¿Qué desea hacer? </h2>
<!--<Aquí definimos el formulario, el gual imprime en el select todos los pacientes que puede seleccionar este doctor.>-->
    <form method="post">
        <input type="radio" id="horario" name="opcion" value="horario">
        <label for="horario"> Revisar el horario</label><br>
        <input type="radio" id="PanelControl" name="opcion" value="PanelControl">
        <label for="PanelControl"> Ir al panel del paciente</label>
        <select name="pacientes" id="pacientes"> 
            <option value="" disabled selected> Seleccione un paciente </option>
            <?php
            $query = "SELECT dni, nombre, apellidos FROM pacientes";
            $result = mysqli_query($conexion, $query);

            if($result && mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<option value="' . $row['dni'] . '">' . $row['nombre'] . ' ' . $row['apellidos'] . '</option>';
                }
            }
            ?>
        </select> <br>
<!--<Aquí definimos el botón, que dependiendo de la opción elegida, te enviará a una página o a otra.>-->
        <input type="submit" value="Vamos al panel">
    </form>
    </div>
</body>
</html>
