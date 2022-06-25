
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>UMSS fcyt</title>
</head>
<body>
<?php
	$db_host="localhost";
	$db_nombre="umss_tis";
	$db_usuario="root";
	$db_contrasenia="";
	$conexion=mysqli_connect($db_host,$db_usuario,$db_contrasenia,$db_nombre);
	if(mysqli_connect_errno()){
		echo "fallo al conectar con la Base de Datos";
		exit();
	}
	mysqli_set_charset($conexion, "utf8");
	//Datos de la tabla a pasar son :
		$consulta="SELECT * FROM `enero`";
		$resultado_consulta=mysqli_query($conexion,$consulta);
		$mes=$_GET["mes"];
		
		if($mes=="enero")
		{
			while(($fila=mysqli_fetch_row($resultado_consulta))==true){
				echo $fila[0] . "			";
				echo $fila[1] . "			";
				echo $fila[2] . "			";
				echo $fila[3] . "			";
				echo $fila[4] . "			";
				echo $fila[5] . "			";
				echo $fila[6] . "			";
				echo $fila[7] . "			";
				echo $fila[8] . "			";
				echo $fila[9] . "			";
				echo $fila[10] . "	";
				echo $fila[11] . "	";
				echo $fila[12] . "	";
				echo $fila[13] . "	";
				echo $fila[14] . "	<br>";
				echo "	";
			//https://github.com/201400076/Sistema_cotizaciones.git
			}
		}else{
			echo "Elija otro mes";
		}
	mysqli_close($conexion);
?>