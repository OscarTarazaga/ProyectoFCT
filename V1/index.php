<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/indexcss.css">
    <title>Pagina Principal</title>
</head>
<body>

<?php
$host="127.0.0.1";
$port=3306;
$user="root";
$password="root";
$dbname="proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

if(isset($_POST['login'])){
    $dni = $_POST['DNI'];
    $passwd = $_POST['passwd'];

    $query = "SELECT * FROM usuarios WHERE dni='$dni' AND passwd='$passwd'";
    $result = mysqli_query($conexion, $query);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        if($row['tipo'] == 'paciente'){
            header('Location: /V1/paciente.php');
            exit();
        } elseif($row['tipo'] == 'medico'){
            header('Location: /V1/medico.php');
            exit();
        } elseif($row['tipo'] == 'administrador'){
            header('Location: /V1/admin.php');
            exit();
        }
    } else {
        echo "El usuario y/o contraseña son incorrectos.";
    }
}
?>

    <div>
        <form action="" method="post">
            <label for="DNI">DNI:</label><br>
            <input type="text" id="DNI" name="DNI" placeholder="DNI del usuario"> <br>
            <label for="passwd">Contraseña:</label><br>
            <input type="text" id="passwd" name="passwd" placeholder="contraseña"><br><br>
            <input type="submit" name="login" value="Login">
         </form> 
    </div>
</body>
</html>
