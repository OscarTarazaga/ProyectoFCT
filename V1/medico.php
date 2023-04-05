<?php
session_start();
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

$nombre_doctor = '';
$genero_doctor = '';
$pronombre_tratamiento = '';
$dni_doctor = '';

if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'doctor' && isset($_SESSION['dni'])){
    $dni_doctor = $_SESSION['dni'];
}

$query = "SELECT nombre, genero FROM doctores WHERE dni = '$dni_doctor'";
$result = mysqli_query($conexion, $query);

if($result && mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $nombre_doctor = $row['nombre'];
    $genero_doctor = $row['genero'];

    $pronombre_tratamiento = ($genero_doctor == 'H') ? 'Dr.' : 'Dra.';
}

if(isset($_POST['opcion']) && $_POST['opcion'] == 'horario') {
    $dni = $_POST['dni'];
    header('Location: medico_horario.php?dni=' . $dni);
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
    <title>Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?></title>
</head>
<body>
    <div>
    <h1> Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?> </h1>
    <h2> ¿Qué desea hacer? </h2>
    <form method="post" action="medico_horario.php">
    <input type="radio" id="horario" name="opcion" value="horario">
    <label for="horario"> Revisar el horario</label><br>
    <input type="hidden" name="dni" value="<?php echo $dni_doctor ?>">
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
    <input type="hidden" name="dni_doctor" value="<?php echo $dni_doctor ?>">
    <input type="submit" value="Vamos al panel">
</form>
    </div>
</body>
</html>
