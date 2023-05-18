<?php
// Abrimos la sesión para que el administrador al entrar en este panel, pueda seguir teniendo acceso mediante el dni introducido anteriormente
session_start();
// Creamos la conexión con la base de datos
$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port); 

// Aqui se define que si el tipo de usuario que intenta entrar es administrador, se almacene su dni en la variable declarada anteriormente llamada dni_admin
if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'administrador' && isset($_SESSION['dni'])){
    $dni_admin = $_SESSION['dni'];
}

// Este if lo que controla es la opción seleccionada en este panel, para que envíe también el dni del administrador
if (isset($_POST['opcion'])) {
    if ($_POST['opcion'] == 'insercion_doctor') {
        header('Location: admin_inserciondoctor.php');
        exit;
    } elseif ($_POST['opcion'] == 'informacion') {
        // Verificamos si se ha seleccionado un doctor
        if (empty($_POST['doctores'])) {
            header('Location: admin.php');
            exit;
        }
        $dni_doctor = $_POST['doctores'];
        header('Location: admin_infodoctor.php?dni_doctor=' . $dni_doctor);
        exit;
    } elseif ($_POST['opcion'] == 'insercion_paciente') {
        header('Location: admin_insercionpaciente.php');
        exit;
    } elseif ($_POST['opcion'] == 'ticket') {
        // Verificamos si se ha seleccionado un paciente
        if (empty($_POST['paciente'])) {
            header('Location: admin.php');
            exit;
        }
        $dni_paciente = $_POST['paciente'];
        header('Location: admin_tickets.php?paciente=' . $dni_paciente);
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
    <link rel="stylesheet" type="text/css" href="CSS/admincss.css">
    <title>Bienvenido administrador</title>
</head>
<body>
    
    <div>
    <h1> Bienvenido Administrador</h1>
    <h2> ¿Qué desea hacer? </h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3> - Opciones de referentes a los doctores </h3>
        <!--< Doctor >-->
        <input type="radio" id="insercion_doctor" name="opcion" value="insercion_doctor">
        <label for="insercion_doctor"> Hacer una inserción de un doctor</label>
        </select> <br>

        <input type="radio" id="informacion" name="opcion" value="informacion">
        <label for="informacion"> Revisar la información del doctor</label>
        <select name="doctores" id="doctores"> 
            <option value="" disabled selected> Seleccione un doctor </option>
            <?php
                // Aqui lo que decimos es que use la siguiente consulta y que en el select salgan los nombres de los doctores dentro de la tabla doctor, con esto, se tendrá en cuenta su dni que se usará en el panel que imprime la 
                // información de los doctores para el administrador
                $query = "SELECT dni, nombre, apellidos FROM doctores";
                $result = mysqli_query($conexion, $query);

                if($result && mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="' . $row['dni'] . '">' . $row['nombre'] . ' ' . $row['apellidos'] . '</option>';
                        $dni_doctor = $row['dni'];
                    }
                } 
            ?>
        </select> 
    <h3> - Opciones de referentes a los pacientes </h3>
        <!--< Paciente >-->
        <input type="radio" id="insercion_paciente" name="opcion" value="insercion_paciente">
        <label for="insercion_paciente"> Hacer una inserción de un paciente</label><br>

        <input type="radio" id="ticket" name="opcion" value="ticket">
        <label for="ticket"> Tickets de los pacientes</label>
        <!-- selector del paciente en el formulario -->
        <select name="paciente" id="paciente">
            <option value="" disabled selected>Seleccione un paciente</option>
            <?php
            // Consulta modificada para filtrar solo los pacientes que hayan enviado un ticket
            $query = "SELECT p.dni, p.nombre, p.apellidos 
                    FROM pacientes p
                    INNER JOIN tickets t ON p.dni = t.dni_paciente";
            $result = mysqli_query($conexion, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['dni'] . '">' . $row['nombre'] . ' ' . $row['apellidos'] . '</option>';
                    $dni_paciente = $row['dni'];
                }
            }
            ?>
        </select><br>

        <input type="submit" value="Vamos al panel">
    </form>
    </div>

</body>
</html>
