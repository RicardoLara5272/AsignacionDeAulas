
<!doctype html>
<hmtl>
<head>
    <meta charset="utf-8">
    <title>UMSS fcyt</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="aparienciaFormularioAsignacion.css">

</head>
<body>

<header class="conteiner-fluid">
    <h1>SISTEMA DE ASIGNACION DE AULAS</h1>
</header>
<form method="post">
  <div class="elem-group">
    <center><label for="nombre">Nombre</label>
    <input type="text" id="name" name="nombre_docente" placeholder="nombre" pattern=[A-Z\sa-z]{3,20} required></center><br>
  </div>
  <div class="elem-group">
    <center><label for="apellido">Apellido</label>
    <input type="text" id="apellido" name="apellido_docente" placeholder="Apellido" pattern=[A-Z\sa-z]{3,20} required></center><br>
  </div>
  <div class="elem-group">
    <center><label for="email">Correo electrónico</label>
    <input type="email" id="email" name="correo_docente" placeholder="ejemplo@email.com" required></center><br>
  </div>
  <div>
    <center><label for="meeting-time">fecha y hora inicio</label>
    <input type="datetime-local" id="hora_inicio" name="hora_inicio" value="2022-04-12T06:30" min="2022-01-07T06:00" max="2022-12-14T16:00"></center><br>
  </div>
  <div>
    <center><label for="meeting-time">fecha y hora fin</label>
    <input type="datetime-local" id="hora_fin" name="hora_fin" value="2022-04-12T06:30" min="2022-01-07T06:00" max="2022-12-14T16:00"></center><br>
  </div>
  <div class="elem-group">
    <center><label for="message">Respuesta de asignacion</label></center>
    <textarea id="respuesta" name="mensaje" placeholder="Escribe tu mensaje aquí." required></textarea>
  </div>
   <input type="submit" name="Enviar" id="Enviar" value="Guardar/Enviar">
  <input type="submit" name="Cancelado" id="Cancelado" value="Cancelar">
</form>
<footer class="conteiner-fluid">
  <center><h2>Universidad Mayor de San Simon</h2></center>
</footer>
</body>
<?php
include("funciones_asignacion.php");
?>
</html>