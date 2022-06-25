<?php
require('config/config.php');
//Si existe la sesion redirigir a la pagina de miembros
if ($user->is_logged_in()) {
    header('Location: memberpage.php');
    exit();
}

//Verifica si el formulario ha sido enviado correctamente
if (isset($_POST['submit'])) {

    if (!isset($_POST['username'])) $error[] = "Por favor rellene el usuario";
    if (!isset($_POST['correo'])) $error[] = "Por favor rellene el Email";
    if (!isset($_POST['password'])) $error[] = "Por favor rellene todos los campos";
    if (!isset($_POST['ci_docente'])) $error[] = "Por favor rellene el ci docente";
    if (!isset($_POST['nombre_docente'])) $error[] = "Por favor rellene todos los campos";

    $usuario = $_POST['username'];

    //very basic validation
    if (!$user->isValidUsername($usuario)) {
        $error[] = 'Los nombres de usuario deben tener al menos 3 caracteres alfanuméricos';
    } else {
        $stmt = $db->prepare('SELECT usuario FROM docentes WHERE usuario = :usuario');
        $stmt->execute(array(':usuario' => $usuario));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['usuario'])) {
            $error[] = 'El nombre de usuario proporcionado ya está en uso.';
        }
    }

    if (strlen($_POST['password']) < 3) {
        $error[] = 'La contraseña es demasiado corta.';
    }

    if (strlen($_POST['passwordConfirm']) < 3) {
        $error[] = 'Confirmar contraseña es demasiado corta.';
    }

    if ($_POST['password'] != $_POST['passwordConfirm']) {
        $error[] = 'Las contraseñas no coinciden.';
    }

    //Validamos el correo electronico
    $correo = htmlspecialchars_decode($_POST['correo'], ENT_QUOTES);
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Por favor, introduce una dirección de correo electrónico válida';
    } else {
        $stmt = $db->prepare('SELECT correo FROM docentes WHERE correo = :correo');
        $stmt->execute(array(':correo' => $correo));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['correo'])) {
            $error[] = 'Email provided is already in use.';
        }
    }


    //Comprobamos que no exista error
    if (!isset($error)) {

        //hash the password
        $hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

        //Creamos el codigo de activacion
        $activasion = md5(uniqid(rand(), true));
        $ci_docente = $_POST['ci_docente'];
        $nombre_docente = $_POST['nombre_docente'];
        $is_admin = 0;
        $resetToken = "";
        $resetComplete = "";
        try {

            //Insertar la informacion ingresada en el formulario de registro
            $stmt = $db->prepare('INSERT INTO docentes (ci_docente,nombre_docente,correo,usuario,password,is_admin,active,resetToken,resetComplete) VALUES (:ci_docente,:nombre_docente,:correo,:usuario,:password,:is_admin,:active,:resetToken,:resetComplete)');
            $stmt->execute(array(
                ':ci_docente' => $ci_docente,
                ':nombre_docente' => $nombre_docente,
                ':correo' => $correo,
                ':usuario' => $usuario,
                ':password' => $hashedpassword,
                ':is_admin' => $is_admin,
                ':active' => $activasion,
                ':resetToken' => $resetToken,
                ':resetComplete' => $resetComplete
            ));
            $id = $db->lastInsertId('id_docente');

            //send correo
            $to = $_POST['correo'];
            $subject = "Confirmación de registro";
            $body = "<p>Gracias por registrarse en el sitio de demostración.</p>
			<p>Para activar su cuenta, haga clic en este enlace: <a href='" . DIR . "activate.php?x=$id&y=$activasion'>" . DIR . "activate.php?x=$id&y=$activasion</a></p>
			<p>Saludos al administrador del sitio</p>";

            $mail = new Mail();
            $mail->setFrom(SITEEMAIL);
            $mail->addAddress($to);
            $mail->subject($subject);
            $mail->body($body);
            $mail->send();

            //redireccionamos al index
            header('Location: index.php?action=joined');
            exit;

            //else catch the exception and show the error.
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
        }
    }
}

//definimos el titulo de la pagina
$title = 'Login y registro PDO';

//include header tema
require('layout/header.php');
?>
<main class="content">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <?php
            //check for any errors
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<p class="bg-danger">' . $error . '</p>';
                }
            }

            //if action is joined show sucess
            if (isset($_GET['action']) && $_GET['action'] == 'joined') {
                echo "<h4 class='bg-success'>Registro exitoso, por favor revise su correo electrónico para activar su cuenta.</h4>";
            }
            ?>
            <div class="row">

                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <form role="form" method="post" action="" autocomplete="off">
                        <h2>Por favor regístrese</h2>
                        <hr>

                        <?php
                        //check for any errors
                        if (isset($error)) {
                            foreach ($error as $erro) {
                                echo '<p class="bg-danger">' . $erro . '</p>';
                            }
                        }

                        //if action is joined show sucess
                        if (isset($_GET['action']) && $_GET['action'] == 'joined') {
                            echo "<h4 class='bg-success'>Registro exitoso, por favor revise su correo electrónico para activar su cuenta.</h4>";
                        }
                        ?>

                        <div class="form-group">
                            <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Usuario" value="<?php if (isset($error)) {
                                                                                                                                            echo htmlspecialchars($_POST['username'], ENT_QUOTES);
                                                                                                                                        } ?>" tabindex="1">
                        </div>
                        <div class="form-group">
                            <input type="text" name="nombre_docente" id="nombre_docente" class="form-control input-lg" placeholder="Nombre docente" value="<?php if (isset($error)) {
                                                                                                                                                                echo htmlspecialchars($_POST['nombre_docente'], ENT_QUOTES);
                                                                                                                                                            } ?>" tabindex="2">
                        </div>
                        <div class="form-group">
                            <input type="number" name="ci_docente" id="ci_docente" class="form-control input-lg" placeholder="CI docente" value="<?php if (isset($error)) {
                                                                                                                                                        echo htmlspecialchars($_POST['ci_docente'], ENT_QUOTES);
                                                                                                                                                    } ?>" tabindex="3">
                        </div>

                        <div class="form-group">
                            <input type="email" name="correo" id="correo" class="form-control input-lg" placeholder="Email Address" value="<?php if (isset($error)) {
                                                                                                                                                echo htmlspecialchars($_POST['correo'], ENT_QUOTES);
                                                                                                                                            } ?>" tabindex="4">
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="5">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirmar Password" tabindex="6">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Registrarme" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div>
                                <p>¿Ya eres usuario? <a href='login.php'>Iniciar sesion</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
//include header template
require('layout/footer.php');
?>