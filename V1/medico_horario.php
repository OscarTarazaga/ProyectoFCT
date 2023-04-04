<?php
// Abrimos la sesión para que todo se pueda usar en las diferentes páginas a las que puede acceder cada usuario
session_start();
// Definimos las variables de horario_inicio y horario_fin que usaremos mas adelante
$horario_inicio = "";
$horario_fin = "";
// Habilitamos la conexión con la base de datos
$host="127.0.0.1";
$port=3306;
$user="root";
$password="root";
$dbname="proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);
// en este if lo que hacemos es que si hay algun error con el dni o este no fue proporcionado correctamente, salte un error que no permita hacer nada
// por otro lado, si el dni es correcto imprimira el horario del doctor asociado a ese dni
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
<!--<Aquí he definido el estilo de estos elementos ya que no me dejaba hacerlo desde el css que hay desde un inicio auqnque si aplica los cambios al reste de 
elementos asociados al css.
En este caso, he definido que el titulo salga en el centro del div, y el formato de la tabla, para que salga dentro de celdas, excepto la primera celda de todas, ya que esta 
aparece sin borde alguno>-->
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
<!--<Aquí definimos la tabla>-->
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
            // Genera cada fila de la tabla usando como inicio la hora actual que es la variable horario_inicio y usando tambien el dia actual, esto para poder
            // generar las celdas en cada día de la semana desde el lunes hasta el viernes.
            $hora_actual = $horario_inicio;
            $dia_actual = 1;
            while (strtotime($hora_actual) <= strtotime($horario_fin)) {
                echo "<tr>";
                echo "<td>$hora_actual</td>";
                for ($j = 0; $j < 5; $j++) {
                    echo "<td></td>";
                }
                echo "</tr>";
                // Esto hace que se imprima el horario en cada celda con una diferencia de media hora.
                $hora_actual = date('H:i:s', strtotime("$hora_actual + 30 minutes"));
                $dia_actual++;
            }
            ?>
        </table>
    </div>
</body>
</html>
