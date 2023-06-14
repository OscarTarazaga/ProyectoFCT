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

if (!$conexion) {
    die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
}

// Definimos las variables que almacenarán los datos del paciente
$dni_paciente = "";
$id = "";
$nombre_paciente = "";
$apellidos_paciente = "";
$genero_paciente = "";
$edad = "";
$direccion = "";
$telefono = "";
$contraseña = "";
$dni_doctor = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar"])) {
    // Verificar que todos los campos estén completos
    if (!empty($_POST["dni"]) && !empty($_POST["id"]) && !empty($_POST["nombre"]) && !empty($_POST["apellidos"]) && isset($_POST["genero"]) && !empty($_POST["edad"]) && !empty($_POST["direccion"]) && !empty($_POST["telefono"]) && !empty($_POST["contrasena"])) {

        // Asignar los valores de los campos a las variables
        $dni_paciente = $_POST["dni"];
        $id = $_POST["id"];
        $nombre_paciente = $_POST["nombre"];
        $apellidos_paciente = $_POST["apellidos"];
        $genero_paciente = $_POST["genero"];
        $edad = $_POST["edad"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $contraseña = $_POST["contrasena"];
        $dni_doctor = $_POST["dni_doctor"];

        // Preparar la consulta SQL para verificar si ya existe un paciente con ese DNI
        $consulta = "SELECT dni FROM pacientes WHERE dni = ?";

        // Preparar el statement
        $stmt = mysqli_prepare($conexion, $consulta);

        // Vincular el parámetro
        mysqli_stmt_bind_param($stmt, "s", $dni_paciente);

        // Ejecutar la consulta SQL
        mysqli_stmt_execute($stmt);

        // Obtener el resultado de la consulta
        $resultado = mysqli_stmt_get_result($stmt);

        // Verificar si ya existe un paciente con ese DNI
        if (mysqli_num_rows($resultado) > 0) {
            echo "<script>alert('Ya existe un paciente registrado con ese DNI.')</script>";
        } else {
            // Preparar la consulta SQL para insertar los datos en la tabla pacientes
            $consulta = "INSERT INTO pacientes (dni, id, nombre, apellidos, genero, edad, direccion, telefono, passwd, dni_doctor) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Preparar el statement
            $stmt = mysqli_prepare($conexion, $consulta);

            // Vincular los parámetros
            mysqli_stmt_bind_param($stmt, "sisssissss", $dni_paciente, $id, $nombre_paciente, $apellidos_paciente, $genero_paciente, $edad, $direccion, $telefono, $contraseña, $dni_doctor);

            // Ejecutar la consulta SQL
            mysqli_stmt_execute($stmt);

            // Verificar si la inserción fue exitosa
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<script>alert('Registro insertado correctamente.')</script>";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            // Cerrar el statement
            mysqli_stmt_close($stmt);

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);

            // Redirigir al usuario a la página admin.php
            header("Location: admin.php");
            exit();
        }
    } else {
        echo "<script>alert('Por favor, rellena todos los campos.')</script>";
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
    <title>Inserción del doctor</title>
</head>
<body>
    
    <div>
        <h4> Insertar un paciente</h4>
        <form action="admin_insercionpaciente.php" method="post">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" maxlength="9"><br>

        <label for="id">ID:</label>
        <input type="number" id="id" name="id"><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos"><br>

        <label for="genero">Género:</label>
        <select id="genero" name="genero">
            <option value="H">Hombre</option>
            <option value="M">Mujer</option>
        </select><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad"><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion"><br>

        <label for="telefono">Teléfono:</label>
        <input type="number" id="telefono" name="telefono"><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" maxlength="16"><br>

        <label for="dni_doctor">Doctor:</label>
        <select id="dni_doctor" name="dni_doctor">
            <option value="" disabled selected>Seleccione un doctor para el paciente</option>
            <?php
                $query = "SELECT dni, nombre, apellidos FROM doctores";
                $resultado = mysqli_query($conexion, $query);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<option value='" . $fila["dni"] . "'>" . $fila["nombre"] . " " . $fila["apellidos"] . "</option>";
                }
            ?>
        </select><br>

        <input type="submit" name="enviar" value="Enviar">
    </form>
    </div>

<form action="admin.php" class="volver-btn" method="post">
        <input type="submit" value="Volver a la selección">
    </form>
</body>
</html>
