<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/medicocss.css">
    <title>Bienvenido doctor/a</title>
</head>
<body>
    
    <div>
    <h1> Bienvenido doctor/a <!--< DEFINIR EN PHP DEPENDIENDO DEL USUARIO QUE LOGUE >--></h1>
    <h2> ¿Qué desea hacer? </h2>

    <form>
        <input type="radio" id="horario" name="opcion" value="horario">
        <label for="horario"> Revisar el horario</label><br>
        <input type="radio" id="PanelControl" name="opcion" value="PanelControl">
        <label for="PanelControl"> Ir al panel del paciente</label>
        <select name="pacientes" id="pacientes"> 
            <option value="" disabled selected> Seleccione un paciente </option>
        </select> <br>
        <input type="submit" value="Vamos al panel">
    </form>
    </div>

</body>
</html>