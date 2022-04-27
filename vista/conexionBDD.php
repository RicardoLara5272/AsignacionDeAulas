<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
 <?php

    $nombre="Daniel";
    $materia="Intro";
    $fecha='2022-04-08';
    $id=4;
    $motivo="aldssssd";
    date_default_timezone_set('America/Mexico_City');
    $fecha_Actual=date("Y-m-d");
    echo "$fecha_Actual";
      $db_host="localhost";
      $db_nombre="prueba";
      $db_usuario="root";
      $db_contra="";
      $conexion=mysqli_connect($db_host,$db_usuario,$db_contra,$db_nombre);
      $insertar2="INSERT INTO `rechazados` (`Id`, `Doecente`, `Fecha`, `Materia`, `Motivo_Rechazo`) VALUES ('9', '$nombre', '2022-04-13', '$materia', '$motivo')";
      $consulta=mysqli_query($conexion,$insertar2);

      $ultimo_dato=mysqli_insert_id($conexion);
      echo $ultimo_dato;
      
      if($consulta){
          echo "correcto";
      }
      else{
          echo "mal";
      }

 ?>
</body>
</html>