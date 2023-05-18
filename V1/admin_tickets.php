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

if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}


// Definimos las variables que almacenarán los datos del paciente para mostrarlos
$dni_paciente = "";
$id = "";
$nombre_paciente = "";
$apellidos_paciente = "";
$genero_paciente = "";
$edad_paciente = "";
$direccion_paciente = "";
$telefono_paciente = "";
$contraseña = "";
$dni_doctor = "";

// En este if decimos que haga la consulta teniendo en cuenta el dni del paciente, si no, que salte un error donde dice que no se encontró el dni
if (isset($_GET['paciente'])) {
    $dni_paciente = $_GET['paciente'];
    $query = "SELECT * FROM pacientes WHERE dni = '$dni_paciente'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $dni_paciente = $row['dni'];
        $id = $row['id'];
        $nombre_paciente = $row['nombre'];
        $apellidos_paciente = $row['apellidos'];
        $genero_paciente = $row['genero'];
        $edad_paciente = $row['edad'];
        $direccion_paciente = $row['direccion'];
        $telefono_paciente = $row['telefono'];
        $contraseña = $row['passwd'];
        $dni_doctor = $row['dni_doctor'];
    } else {
        echo "No se encontró ningún paciente con el DNI $dni_paciente.";
    }
}

if (isset($dni_paciente)) {
    $query_tickets = "SELECT * FROM tickets WHERE dni_paciente = '$dni_paciente';";
    $result_tickets = mysqli_query($conexion, $query_tickets);
    $tickets = mysqli_fetch_all($result_tickets, MYSQLI_ASSOC);

    // Desde aqui pondré todo lo referente al update
    // Definimos las variables que almacenarán los datos del paciente para hacer el update
    $dni_paciente_upda = "";
    $id_upda = "";
    $nombre_paciente_upda = "";
    $apellidos_paciente_upda = "";
    $genero_paciente_upda = "";
    $edad_upda = "";
    $direccion_upda = "";
    $telefono_upda = "";
    $contraseña_upda = "";
    $dni_doctor_upda = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar"])) {
        // Verificar que todos los campos estén completos
        if (!empty($_POST["dni"]) && !empty($_POST["id"]) && !empty($_POST["nombre"]) && !empty($_POST["apellidos"]) && isset($_POST["genero"]) && !empty($_POST["edad"]) && !empty($_POST["direccion"]) && !empty($_POST["telefono"]) && !empty($_POST["contrasena"]) && !empty($_POST["dni_doctor"])) {
    
            // Asignar los valores de los campos a las variables
            $dni_paciente_upda = $_POST["dni"];
            $id_upda = $_POST["id"];
            $nombre_paciente_upda = $_POST["nombre"];
            $apellidos_paciente_upda = $_POST["apellidos"];
            $genero_paciente_upda = $_POST["genero"];
            $edad_upda = $_POST["edad"];
            $direccion_upda = $_POST["direccion"];
            $telefono_upda = $_POST["telefono"];
            $contraseña_upda = $_POST["contrasena"];
            $dni_doctor_upda = $_POST["dni_doctor"];
    
            // Verificar si el dni_doctor existe en la tabla doctores
            $consulta_doctor = "SELECT dni FROM doctores WHERE dni = ?";
            $stmt = mysqli_prepare($conexion, $consulta_doctor);
            mysqli_stmt_bind_param($stmt, "s", $dni_doctor_upda);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
    
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Preparar la consulta SQL para verificar si ya existe un paciente con ese DNI
                $consulta = "SELECT dni FROM pacientes WHERE dni = ?";
    
                // Ejecutar la consulta SQL
                $stmt = mysqli_prepare($conexion, $consulta);
                mysqli_stmt_bind_param($stmt, "s", $dni_paciente_upda);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
    
                // Verificar si ya existe un paciente con ese DNI
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    // Preparar la consulta SQL para actualizar los datos en la tabla pacientes
                    $consulta_actualizar = "UPDATE pacientes SET id = ?, nombre = ?, apellidos = ?, genero = ?, edad = ?, direccion = ?, telefono = ?, passwd = ?, dni_doctor = ? WHERE dni = ?";
    
                    // Ejecutar la consulta SQL de actualización
                    $stmt = mysqli_prepare($conexion, $consulta_actualizar);
                    mysqli_stmt_bind_param($stmt, "isisisssss", $id_upda, $nombre_paciente_upda, $apellidos_paciente_upda, $genero_paciente_upda, $edad_upda, $direccion_upda, $telefono_upda, $contraseña_upda, $dni_doctor_upda, $dni_paciente_upda);
    
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>alert('Registro actualizado correctamente.')</script>";
                        header("Location: admin.php");
                        exit();
                    } else {
                        echo "Error al actualizar el registro: " . mysqli_error($conexion);
                    }
                } else {
                    echo "<script>alert('No existe un paciente registrado con ese DNI.')</script>";
                }
            } else {
                echo "<script>alert('El DNI del doctor no existe.')</script>";
            }
        }
    }
}
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/tickets.css">
    <title>Tickets</title>
</head>
<body>
    <div class="izquierda">
        <h4> Información del paciente (Datos personales)</h4>
        <?php if (isset($nombre_paciente)) { ?>
            <p>DNI: <?php echo $dni_paciente; ?></p>
            <p>ID: <?php echo $id; ?></p>
            <p>Nombre: <?php echo $nombre_paciente; ?></p>
            <p>Apellidos: <?php echo $apellidos_paciente; ?></p>
            <p>Género: <?php echo $genero_paciente; ?></p>
            <p>Edad: <?php echo $edad_paciente; ?> años</p>
            <p>Dirección: <?php echo $direccion_paciente; ?></p>
            <p>Teléfono: <?php echo $telefono_paciente; ?></p>
            <p>Contraseña: <?php echo $contraseña; ?></p>
            <p>DNI del Doctor: <?php echo $dni_doctor; ?></p>
        <?php } ?>
    </div>

    <div class="medio">
        <?php if (isset($tickets)) { ?>
            <h4>Información de los tickets:</h4>
            <?php foreach ($tickets as $ticket) { ?>
                <p>ID del ticket: <?php echo $ticket['id_ticket']; ?></p>
                <p>Texto: <?php echo $ticket['texto']; ?></p>
            <?php } ?>
        <?php } ?>
                
        <form action="admin.php" class="volver-btn" method="post">
            <input type="submit" value="Volver a la selección">
        </form>
    </div>

    <div class="derecha">
    <h4> Insertar un paciente: </h4>
    <form action="admin_tickets.php" method="post">

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" maxlength="9" value="<?php echo $dni_paciente_upda; ?>"><br>

        <label for="id">ID:</label>
        <input type="number" id="id" name="id" value="<?php echo $id_upda; ?>"><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre_paciente_upda; ?>"><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos_paciente_upda; ?>"><br>

        <label for="genero">Género:</label>
        <select id="genero" name="genero">
            <option value="H" <?php if ($genero_paciente_upda == "H") { echo "selected"; } ?>>Hombre</option>
            <option value="M" <?php if ($genero_paciente_upda == "M") { echo "selected"; } ?>>Mujer</option>
        </select><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?php echo $edad_upda; ?>"><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $direccion_upda; ?>"><br>

        <label for="telefono">Teléfono:</label>
        <input type="number" id="telefono" name="telefono" value="<?php echo $telefono_upda; ?>"><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" value="<?php echo $contraseña_upda; ?>"><br>

        <label for="dni_doctor">DNI del Doctor:</label>
        <input type="text" id="dni_doctor" name="dni_doctor" maxlength="9" value="<?php echo $dni_doctor_upda; ?>"><br>

        <input type="submit" name="enviar" value="Actualizar">
    </form>
    </div>
</body>
</html>
