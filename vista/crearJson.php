<?php

$arreglo=array();

for($i=0; $i<6; $i++){
	$arreglo[]=$i;
}

  $db_host = "localhost";
  $db_nombre = "asignacionaulas";
  $db_usuario = "root";
  $db_contrasenia = "";
  $conexion = mysqli_connect($db_host, $db_usuario, $db_contrasenia, $db_nombre);
  if (mysqli_connect_errno()) {
    echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
    exit();
  }
mysqli_set_charset($conexion, "utf8");
$json = json_encode($arreglo);
$file = 'arreglo.json';
file_put_contents($file, $json);

$guardar="INSERT INTO pruebajsson (numeros) VALUES ('$json')"; 
$result=mysqli_query($conexion,$guardar);
if($result == true){
	echo "<br>guardado con exito";
}else{
	echo "<br>error al guardar";
}

mysqli_close($conexion);

echo "<form action='leerJson.php' method='post'><input type='hidden' name='cadena' value=". htmlspecialchars(serialize($arreglo)) ."><input type='submit' name='envio' value='enviar'></form>";
?>