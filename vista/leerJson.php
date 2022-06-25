<?php 

$arreglo=unserialize($_POST["cadena"]);
for($i=0; $i<6; $i++){
	echo "numero $i " . $arreglo[$i] . " -> ";
}

$json = json_encode($arreglo);
echo $json;
//$cadenas = ('/[0-9\@\.\;\,\" "{}]+/', json_decode($json));
$cadenas = str_replace(array("#", "'", ";", "[]"), '', json_decode($json));
for($i=0; $i<count($cadenas); $i++){
	echo "<br>numero : $i y el json : " . $cadenas[$i] . " -> ";
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

$leer="SELECT numeros FROM pruebajsson"; 
$result=mysqli_query($conexion,$leer);
if($result == true){
	echo "<br>lectura con exito<br>";
}else{
	echo "<br>error al leer";
}
while(($fila = mysqli_fetch_row($result)) == true){
	//$captura=str_replace(array("#", "'", ";", "[]"), '', json_decode($fila[0]));
	$captura=json_decode($fila[0]);
	//$encadenado=implode("[] , ", $captura);
	if(is_array($captura)){
		$cap1=implode(" ", $captura);
		echo "<br>es arreglo" . count($captura);
		for($i=0; $i<count($captura); $i++){
			echo "<br>" . $captura[$i];
		}
		if(is_string($cap1)){
			echo "<br>es un string";
		}else{
			echo "<br>no es arreglo";
		}
	}/*/else{
		$cap=implode(" ", $captura);
		echo "<br> no es cadena " . print_r($cap);
	}*/
	//$cap=implode(" ", $captura);
	//echo "<br>datos " . $cap[];
}


mysqli_close($conexion);

rrrrrrrr
?>