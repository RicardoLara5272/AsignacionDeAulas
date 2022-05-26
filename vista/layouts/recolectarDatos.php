<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<script>
      function enviar(destino){
       document.formulario.action=destino;
       document.formulario.submit();
      }
  </script>    
  <form  method="post" name="formulario">
      <h1>Formulario</h1>
      <label for="">Nombre</label>
      <input type="text" name="Nombre" id="">
      <label for="">Materia</label>
      <input type="text" name="Materia">
      <label for="">Grupo</label>
      <input type="text" name="Grupo">
      <label for="" name="Id">Id</label>
      <button id="asignar" type="button" onClick="enviar('recibir.php')">Asignar</button>
      <button id="rechazar" type="button"  onClick="enviar('formulario.php')" >Rechazar</button>
  </form>
  <style>
          #rechazar{
              background:red;
              color: white;
          }
          #asignar{
              background:green;
              color:white;
          }
  </style>

</body>
</html>