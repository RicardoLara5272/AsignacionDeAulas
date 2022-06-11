<?php
session_start();
session_unset($_SESSION["usuario"]);
session_unset($_SESSION["is_admin"]);
session_destroy();

header('location: ../login.php');
?>
