<?php
// include_once("layouts/head.php");
// include_once("../conexiones/conexion.php");
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
    exit();
}

//define page title
$title = 'Home Docente';

//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
$_POST["fecha"] = date("Y-m-d");
?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <img src="../img.jpg" alt="" style="object-fit: cover;" srcset="">
            </div>
        </div>
    </div>
</main>

<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>