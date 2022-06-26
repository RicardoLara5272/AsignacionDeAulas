<?php
include('password.php');
class User extends Password{

    private $_db;

    function __construct($db){
    	parent::__construct();

    	$this->_db = $db;
    }

	private function get_user_hash($usuario){

		try {
			$stmt = $this->_db->prepare('SELECT password, usuario, id_docente FROM docentes WHERE usuario = :usuario AND active="Yes" ');
			$stmt->execute(array('usuario' => $usuario));

			return $stmt->fetch();

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function isValidUsername($usuario){
		if (strlen($usuario) < 3) return false;
		if (strlen($usuario) > 17) return false;
		if (!ctype_alnum($usuario)) return false;
		return true;
	}

	public function login($usuario,$password){
		if (!$this->isValidUsername($usuario)) return false;
		if (strlen($password) < 3) return false;

		$row = $this->get_user_hash($usuario);

		if($this->password_verify($password,$row['password']) == 1){

		    $_SESSION['loggedin'] = true;
		    $_SESSION['usuario'] = $row['usuario'];
		    $_SESSION['id_docente'] = $row['id_docente'];
			$_SESSION['is_admin'] = $row['is_admin'];
		    return true;
		}
	}

	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}
	public function activeUrl($requestUri)
	{
		$current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
		if ($current_file_name == $requestUri){
			return 'active';

		}
		return '';
		
	}

}
