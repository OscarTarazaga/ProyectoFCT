<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/pacientecss.css">
    <title>Panel del paciente</title>
</head>
<body>
    <div>
        <h1>Bienvenido a la interfaz del usuario</h1>
        <h2>¿Qué desea hacer?</h2>
    <form>
        <input type="radio" id="horario" name="opcion" value="horario">
        <label for="horario"> Cita Médica</label><br>

        <input type="radio" id="PanelControl" name="opcion" value="PanelControl">
        <label for="PanelControl"> Receta médica</label><br>

        <input type="radio" id="infopaciente" name="opcion" value="infopaciente">
        <label for="infopaciente"> Información del usuario</label>
    </form>
    </div>
</body>
</html>