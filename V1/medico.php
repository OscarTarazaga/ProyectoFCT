<?php
// Abrimos la sesión para que los doctores al entrar en este panel, puedan seguir teniendo acceso mediante el dni introducido anteriormente
session_start();
// Creamos la conexión con la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

// Creamos estas variables para poder usar el nombre y el genero del doctor, a parte de almacenar el dni introducido el cual es el del doctor y la variable pronombre_tratamiento,
// la cual usare para dar la bienvenida dependiendo de si el usuario es hombre o mujer.
$nombre_doctor = '';
$genero_doctor = '';
$pronombre_tratamiento = '';
$dni_doctor = '';

// Aqui se define que si el tipo de usuario que intenta entrar es doctor, se almacene su dni en la variable declarada anteriormente llamada dni_doctor
if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'doctor' && isset($_SESSION['dni'])){
    $dni_doctor = $_SESSION['dni'];
}

// Aqui lo único que hacemos es definir la consulta a usar y el retorno del resultado
$query = "SELECT nombre, genero FROM doctores WHERE dni = '$dni_doctor'";
$result = mysqli_query($conexion, $query);

// En este bloque if, lo que hace es decir que devuelva un nombre y el genero, que devolverá mas de una salida, asi que el nombre se guarda en una variable y el genero en otra, para así,
// si el genero del doctor es "H" entonces se imprimirá Dr y si no, Dra.
if($result && mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $nombre_doctor = $row['nombre'];
    $genero_doctor = $row['genero'];

// Comparación del genero para el uso del pronombre dependiendo de si es Dr o Dra
    $pronombre_tratamiento = ($genero_doctor == 'H') ? 'Dr.' : 'Dra.';
}

// Este primer if lo que hace es que te envie al horario cuando pulses la opción de revisar el horario del doctor
if(isset($_POST['opcion']) && $_POST['opcion'] == 'horario') {
    if(isset($dni_doctor) && !empty($dni_doctor)){
        header('Location: /V1/medico_horario.php');
        exit();
    }
// Este otro, al seleccionar el paciente y pulsar el boton del formulario, te enviará al panel donde se verá la información del paciente, también tiene una alerta con JavaScript la cual es una ventana emergente que saltará si no se elige un paciente.    
}elseif(isset($_POST['opcion']) && $_POST['opcion'] == 'PanelControl') {
    $selectedPatient = $_POST['pacientes'];

    if ($selectedPatient) {
        $dni_doctor = $_POST['dni_doctor'];
        header("Location: medico_panel.php?dni={$selectedPatient}&doctor={$dni_doctor}");
        exit;
    } else {
        echo "<script>alert('Debe seleccionar un paciente.')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/medicocss.css">
    <!--<Aquí definimos el saludo que saldra en el título y en el h1, el cual dirá el pronombre y el nombre del usuario que entre>-->
    <title>Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?></title>
</head>
<body>
    <div>
        <h1> Bienvenido <?php echo $pronombre_tratamiento . ' ' . $nombre_doctor ?> </h1>
        <h2> ¿Qué desea hacer? </h2>
        <!--<Aquí definimos el formulario, el cual tiene trazas de php, esto con el fin de enviar en cada caso el dni del doctor>-->
        <form method="post">
            <input type="radio" id="horario" name="opcion" value="horario">
            <label for="horario"> Revisar el horario</label><br>
            <input type="hidden" name="dni" value="<?php echo $dni_doctor ?>">
            <input type="radio" id="PanelControl" name="opcion" value="PanelControl">
            <label for="PanelControl"> Ir al panel del paciente</label>
            <input type="hidden" name="dni_doctor" value="<?php echo $dni_doctor ?>">
            <select name="pacientes" id="pacientes" name="dni_paciente"> 
                <option value="" disabled selected> Seleccione un paciente </option>
                <?php
                // Aqui lo que decimos es que use la siguiente consulta y que en el select salgan los nombres de los pacientes dentro de la tabla pacientes, con esto, se tendrá en cuenta su dni que se usará en el panel que imprime la 
                // información de los pacientes para los doctores
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
