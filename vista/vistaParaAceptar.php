<?php 

    $db_host="localhost";
    $db_nombre="umss_tis";
    $db_usuario="root";
    $db_contrasenia="";
    $conexion=mysqli_connect($db_host,$db_usuario,$db_contrasenia,$db_nombre);
    if(mysqli_connect_errno()){
        echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
        exit();
    }
    mysqli_set_charset($conexion, "utf8");

    $consulta="SELECT * FROM `solicitud`";

    $resultado_consulta=mysqli_query($conexion,$consulta);
    echo "<form action='AsignacionDeAulas.php' method='get'>";
    while(($fila=mysqli_fetch_row($resultado_consulta))==true){
        echo "<table ><tr><td name='id'>";
        echo $fila[0] . "</td><td type='text' name='nom'>";
        echo $fila[1] . "</td><td>";
        echo $fila[2] . "</td><td>";
        echo "<a href='AsignacionDeAulas.php' action='AsignacionDeAulas.php' method='get' type='submit' id='aceptar' name='aceptar'>aceptar</td><td>";
        echo "<a href='aceptar_rechazar.php' type='submit' id='rechazar' name='aceptar'>rechazar</a></td>";
        echo "</table>";

    }
    echo "</form>";

    mysqli_close($conexion);
?>