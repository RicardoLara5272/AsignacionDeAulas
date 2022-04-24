<?php

      if(isset($_POST["Enviar"])){
            $nombre=$_POST["nombre_docente"];
            $apellido=$_POST["apellido_docente"];
            $correo=$_POST["correo_docente"];
            $respuesta=$_POST["mensaje"];
            $horaIni=$_POST["hora_inicio"];
            $horaFin=$_POST["hora_fin"];
            $id_cualquiera= 88;
            $respuesta=$_POST["mensaje"];

            $db_host="localhost";
            $db_nombre="umss_tis";
            $db_usuario="root";
            $db_contrasenia="";
            $conexion=mysqli_connect($db_host,$db_usuario,$db_contrasenia,$db_nombre);

            if(mysqli_connect_errno()){
                  echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
                  exit();
            }

            //echo " $nombre , $apellido , $correo , $respuesta , $horaIni , $horaFin, estos datos fueron guardados";

            mysqli_set_charset($conexion, "utf8");

            //Datos de la tabla a pasar son :
            //id, nombre, apellido, correo, identificacion_solicitud, fecha_hora_inicio, fecha_inicio_final, texto

            
            $guardar="INSERT INTO asignacionaula(nombre, apellido, correo, identificacion_solicitud, fecha_hora_inicio, fecha_hora_final, comentario) VALUES ('$nombre', '$apellido', '$correo', '$id_cualquiera', '$horaIni', '$horaFin', '$respuesta')";
            $consulta="SELECT * FROM `asignacionaula`";

            $resultado_guardar=mysqli_query($conexion,$guardar);
            $resultado_consulta=mysqli_query($conexion,$consulta);
            /*
            while(($fila=mysqli_fetch_row($resultado_consulta))==true){
                  echo "hay datos '";
                  echo "$fila[0] . ' '";
                  echo "$fila[1] . ' '";
                  echo "$fila[2] . ' '";
                  echo "$fila[3] . ' '";
                  echo "$fila[4] . ' '";
                  echo "$fila[5] . ' '";
                  echo "$fila[6] . ' '";
                  echo "$fila[7] . ' '";
                  echo "$fila[8] . ' '";
            }*/
            echo '<script language="javascript">alert("asignacion realizada exitosamente ");</script>';
            mysqli_close($conexion);
      } else if(isset($_POST["Cancelado"])){
            echo '<script language="javascript">alert("usted cancelo la asignacion");</script>';
      }

?>
