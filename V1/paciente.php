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

// Aqui se define que si el tipo de usuario que intenta entrar es paciente, se almacene su dni en la variable declarada anteriormente llamada dni_paciente
if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'paciente' && isset($_SESSION['dni'])){
    $dni_paciente = $_SESSION['dni'];
}
// Este if lo que controla es la opción seleccionada en este panel, para que envie también el dni_del paciente
if(isset($_POST['opcion'])) {
    if($_POST['opcion'] == 'infopaciente') {
        header('Location: paciente_informacion.php');
        exit;
    } elseif ($_POST['opcion'] == 'horario') {
        header('Location: Paciente_citamedica.php');
        exit;
    } elseif ($_POST['opcion'] == 'PanelControl') {
        header('Location: paciente_recetamedica.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/pacientecss.css">
    <!--Aqui lo unico que tenemos es el formulario el cual usando el dni del paciente nos redirige a las diferentes opciones que tenemos-->
    <title>Panel del paciente</title>
</head>
<body>
    <div>
        <h1>Bienvenido a la interfaz del usuario</h1>
        <h2>¿Qué desea hacer?</h2>
        <form method="POST" action="">
            <input type="hidden" name="dni" value="<?php echo $dni_paciente; ?>">
            <input type="radio" id="infopaciente" name="opcion" value="infopaciente">
            <label for="infopaciente"> Datos personales</label> <br>

            <input type="radio" id="horario" name="opcion" value="horario">
            <label for="horario"> Cita Médica</label><br>

            <input type="radio" id="PanelControl" name="opcion" value="PanelControl">
            <label for="PanelControl"> Receta médica</label><br>

            <input type="submit" name="login" value="Vamos a la selección" class="boton">
        </form>

    </div>
</body>
</html>
