<?php

class Docente extends Conexion{
    
    public function getDocenteGrupo($id_materia){
        $conexion = parent::Conectar();
        $sql = "SELECT d.nombre_docente,dm.id_docente, dm.id_materia, dm.id_grupo FROM docente_materia dm INNER JOIN docentes d ON dm.id_docente=d.id_docente WHERE id_materia = :id_materia";
        $sql = $conexion -> prepare($sql);
        $sql -> bindValue(":id_materia",$id_materia);
        $sql -> execute();
        $resultado = $sql -> fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    
}


?>