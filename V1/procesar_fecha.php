<?php
session_start();

$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "root";
$dbname = "proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

// Obtener la fecha seleccionada del parámetro POST
$fechaSeleccionada = $_POST['fecha_seleccionada'];

// Consultar la base de datos para obtener los horarios ocupados
$query = "SELECT hora, dia FROM cita WHERE dia = '$fecha_seleccionada'";
$result = mysqli_query($conexion, $query);

$horariosOcupados = array();
while ($row = mysqli_fetch_assoc($result)) {
  $hora = $row['hora'];
  $dia = $row['dia'];

  if (!isset($horariosOcupados[$dia])) {
    $horariosOcupados[$dia] = array();
  }

  $horariosOcupados[$dia][] = $hora;
}

// Devolver los horarios ocupados como una respuesta JSON
echo json_encode($horariosOcupados);
?>
