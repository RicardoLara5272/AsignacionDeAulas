<?php

class Formulario{

	private $nombre;
	private $apellido;
	private $correo;

	function __Formulario($nom, $ape){

		$this->nombre=$nom;
		$this->apellido=$ape;	

	}

	function mostrar_datos(){
		global $nombre;
		global $apellido;
		echo "el nombre es  $nombre , y el apellido es $apellido";
	}

}

?>