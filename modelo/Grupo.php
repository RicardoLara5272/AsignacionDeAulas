<?php

class Grupo extends Conexion{
    
    public function getGrupo($id_materia, $id_docente){
        $conexion = parent::Conectar();
        $sql = "SELECT id_grupo FROM docente_materia WHERE id_docente = :id_docente AND id_materia = :id_materia";
        $sql = $conexion -> prepare($sql);
        $sql -> bindValue(":id_docente",$id_docente);
        $sql -> bindValue(":id_materia",$id_materia);
        $sql -> execute();
        $resultado = $sql -> fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    
}


?>