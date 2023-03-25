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
    
    <div>
        <form action="/action_page.php">
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" placeholder="Nombre del usuario"> <br>
            <label for="DNI">DNI:</label><br>
            <input type="text" id="DNI" name="DNI" placeholder="DNI del usuario"> <br>
            <label for="passwd">Contraseña:</label><br>
            <input type="text" id="passwd" name="passwd" placeholder="contraseña"><br><br>
            <input type="submit" value="Login">
         </form> 
    </div>
</body>
</html>
