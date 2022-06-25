<?php require('config/config.php');

//if logged in redirect to docentes page
if( $user->is_logged_in() ){ header('Location: memberpage.php'); exit(); }

//if form has been submitted process it
if(isset($_POST['submit'])){

	//Make sure all POSTS are declared
	if (!isset($_POST['correo'])) $error[] = "Por favor, rellene todos los campos";


	//correo validation
	if(!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid correo address';
	} else {
		$stmt = $db->prepare('SELECT correo FROM docentes WHERE correo = :correo');
		$stmt->execute(array(':correo' => $_POST['correo']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['correo'])){
			$error[] = 'No se reconoce el correo electrónico proporcionado.';
		}

	}

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activation code
		$stmt = $db->prepare('SELECT password, correo FROM docentes WHERE correo = :correo');
		$stmt->execute(array(':correo' => $_POST['correo']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$token = hash_hmac('SHA256', $user->generate_entropy(8), $row['password']);//Hash and Key the random data
        $storedToken = hash('SHA256', ($token));//Hash the key stored in the database, the normal value is sent to the user

		try {

			$stmt = $db->prepare("UPDATE docentes SET resetToken = :token, resetComplete='No' WHERE correo = :correo");
			$stmt->execute(array(
				':correo' => $row['correo'],
				':token' => $storedToken
			));

			//send correo
			$to = $row['correo'];
			$subject = "Restablecer Password";
			$body = "<p>Alguien solicitó que se restablezca la contraseña.</p>
			<p>Si esto fue un error, simplemente ignore este correo electrónico y no pasará nada.</p>
			<p>Para restablecer tu contraseña, visita la siguiente dirección: <a href='".DIR."resetPassword.php?key=$token'>".DIR."resetPassword.php?key=$token</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: login.php?action=reset');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Restablecer cuenta';

//include header template
require('layout/header.php');
?>

<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Restablecer Contraseña</h2>
				<p><a href='login.php'>Volver a la página de inicio de sesión</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				if(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 class='bg-success'>Ahora tu cuenta está activa, ya que puedes iniciar sesión.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Por favor revise su bandeja de entrada para un enlace de restablecimiento.</h2>";
							break;
					}
				}
				?>

				<div class="form-group">
					<input type="email" name="correo" id="correo" class="form-control input-lg" placeholder="Email" value="" tabindex="1">
				</div>

				<hr>
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Enviar Vinculo" class="btn btn-primary btn-block btn-lg" tabindex="2"></div>
				</div>
			</form>
		</div>
	</div>


</div>

<?php
//include header template
require('layout/footer.php');
?>
