<?php
session_start();

$horario_inicio = "";
$horario_fin = "";

$host="127.0.0.1";
$port=3306;
$user="root";
$password="root";
$dbname="proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

if(isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    $query = "SELECT horario_inicio, horario_fin FROM doctores WHERE dni='$dni'";
    $result = mysqli_query($conexion, $query);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $horario_inicio = $row['horario_inicio'];
        $horario_fin = $row['horario_fin'];
    } else {
        echo "Error al obtener el horario del médico.";
    }

    mysqli_close($conexion);
} else {
    echo "El campo DNI no fue proporcionado.";
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
    <title>Horario</title>

    <style>
    h1 {
        text-align: center;
    }
    
    th {
    font-weight: bold;
    text-align: center;
    padding: 10px;
    border: 1px solid black;
    border-radius: 10px;
    }

    td {
    padding: 3px;
    border: 1px solid black;
    border-radius: 10px;
    }
    
    th.a {
    border: none;
    }


    </style>
</head>
<body>

    <div>
        <h1>Horario del doctor</h1>
        <table>
            <tr>
                <th class="a"></th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
            </tr>
            <?php
            // Genera cada fila de la tabla
            $hora_actual = $horario_inicio;
            $dia_actual = 1;
            while (strtotime($hora_actual) <= strtotime($horario_fin)) {
                echo "<tr>";
                echo "<td>$hora_actual</td>";
                for ($j = 0; $j < 5; $j++) {
                    echo "<td></td>";
                }
                echo "</tr>";
                // Aumenta la hora en intervalos de 30 minutos
                $hora_actual = date('H:i:s', strtotime("$hora_actual + 30 minutes"));
                $dia_actual++;
            }
            ?>
        </table>
    </div>
</body>
</html>
