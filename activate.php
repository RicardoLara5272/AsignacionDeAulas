<?php
require('config/config.php');

//collect values from the url
$id_docente = trim($_GET['x']);
$active = trim($_GET['y']);

//if id is number and the active token is not empty carry on
if(is_numeric($id_docente) && !empty($active)){

	//update users record set the active column to Yes where the id_docente and active value match the ones provided in the array
	$stmt = $db->prepare("UPDATE docentes SET active = 'Yes' WHERE id_docente = :id_docente AND active = :active");
	$stmt->execute(array(
		':id_docente' => $id_docente,
		':active' => $active
	));

	//if the row was updated redirect the user
	if($stmt->rowCount() == 1){

		//redirect to login page
		header('Location: login.php?action=active');
		exit;

	} else {
		echo "Your account could not be activated."; 
	}
	
}
?>