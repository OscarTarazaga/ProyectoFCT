<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/admincss.css">
    <title>Bienvenido doctor/a</title>
</head>
<body>
    
    <div>
    <h1> Bienvenido Administrador <!--< DEFINIR EN PHP DEPENDIENDO DEL USUARIO QUE LOGUE >--></h1>
    <h2> ¿Qué desea hacer? </h2>

    <form>
        <!--< Doctor >-->
        <input type="radio" id="horario" name="opcion" value="horario">
        <label for="horario"> Revisar el horario del doctor</label>
        <select name="doctores" id="doctores"> 
            <option value="" disabled selected> Seleccione un doctor </option>
        </select> <br>

        <input type="radio" id="informacion" name="opcion" value="informacion">
        <label for="informacion"> Revisar la información del doctor</label>
        <select name="doctores" id="doctores"> 
            <option value="" disabled selected> Seleccione un doctor </option>
        </select> <br>
        
        <!--< Paciente >-->
        <input type="radio" id="horario_pacientes" name="opcion" value="horario_pacientes">
        <label for="horario_pacientes"> Revisar el horario del paciente</label>
        <select name="pacientes" id="pacientes"> 
            <option value="" disabled selected> Seleccione un paciente </option>
        </select> <br>

        <input type="radio" id="informacion_pacientes" name="opcion" value="informacion_pacientes">
        <label for="informacion_pacientes"> Revisar la información del paciente</label>
        <select name="pacientes" id="pacientes"> 
            <option value="" disabled selected> Seleccione un paciente </option>
        </select> <br>

        <input type="submit" value="Vamos al panel">
    </form>
    </div>

</body>
</html>