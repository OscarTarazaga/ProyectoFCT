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
// Abrimos la sesión para que todo se pueda usar en las diferentes páginas a las que puede acceder cada usuario
session_start();
// Habilitamos la conexión con la base de datos
$host="127.0.0.1";
$port=3306;
$user="root";
$password="root";
$dbname="proyectofct";

$conexion = mysqli_connect($host, $user, $password, $dbname, $port);
// En este if definimos que si las credenciales introducidas son correctas, las compare dentro de la tabla de usuarios
if(isset($_POST['login'])){
    $dni = $_POST['DNI'];
    $passwd = $_POST['passwd'];

    $query = "SELECT * FROM usuarios WHERE dni='$dni' AND passwd='$passwd'";
    $result = mysqli_query($conexion, $query);
// En este segundo if, es donde se compara el dni para saber que tipo de usuario es, y dependiendo del tipo de usuario, lo envie a un panel o a otro,
// si no, saldra un mensaje el cual dice que las credenciales son incorrectas
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['tipo'] = $row['tipo']; // Almacenar el tipo de usuario en una variable de sesión
        $_SESSION['dni'] = $row['dni'];
    
        if($row['tipo'] == 'paciente'){
            header('Location: /V1/paciente.php');
            exit();
        } elseif($row['tipo'] == 'doctor'){
            header('Location: /V1/medico.php');
            exit();
        } elseif($row['tipo'] == 'administrador'){
            header('Location: /V1/admin.php');
            exit();
        }
    } else {
        echo "<script>alert('El usuario y/o contraseña son incorrectos.')</script>";
    }
    
}
?>
<!--<Aquí definimos el formulario a usar>-->
    <div>
        <form action="" method="post">
            <label for="DNI">DNI:</label><br>
            <input type="text" id="DNI" name="DNI" placeholder="DNI del usuario"> <br>
            <label for="passwd">Contraseña:</label><br>
            <input type="password" id="passwd" name="passwd" placeholder="contraseña"><br><br>
            <input type="submit" name="login" value="Login">
         </form> 
    </div>
</body>
</html>
