<?php

      if(isset($_POST["Enviar"])){
            $id_solicitud=$_POST["id_solicitud"];
            $fecha_solicitud=$_POST["fecha_solicitud"];
            $fecha_reserva=$_POST["fecha_reserva"];
            $cantidad_estudiantes=$_POST["cantidad_estudiantes"];
            //$detalle=$_POST["detalle"];
            //$id_docente_materia=$_POST["id_docente_materia"];
            //$id_docente=$_POST["id_docente"];
            $nombre=$_POST["nombre"];
            $apellido=$_POST["apellido"];
            $correo=$_POST["correo"];
            $aula=$_POST["aula"];
            $mensaje=$_POST["mensaje"];

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

             //Datos de la tabla a pasar son :
            //id, nombre, apellido, correo, identificacion_solicitud, fecha_hora_inicio, fecha_inicio_final, texto

            
            $guardar="INSERT INTO asignacionaula(id_aula, nombre, apellido, correo, identificacion_solicitud, fecha_hora_inicio, fecha_hora_final, comentario) VALUES ('$aula', '$nombre', '$apellido', '$correo', '$id_solicitud', '$fecha_solicitud', '$fecha_solicitud', '$mensaje')"; 
            $consulta="SELECT * FROM `asignacionaula`";

            $resultado_guardar=mysqli_query($conexion,$guardar);
            $resultado_consulta=mysqli_query($conexion,$consulta);
           
            echo '<script language="javascript">alert("asignacion realizada exitosamente ");</script>';
            echo "<script language='javascript'>window.location.replace('http://localhost/pruebasTis/aceptar_rechazar.php')</script>";
            mysqli_close($conexion);
      } else if(isset($_POST["Cancelado"])){
            echo "<a href='aceptar_rechazar.php'><script language='javascript'>alert('usted cancelo la asignacion');</script></a>";
      }

?>
