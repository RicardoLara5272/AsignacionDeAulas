<!doctype html>
<hmtl>
<head>
    <meta charset="utf-8">
    <title>UMSS fcyt</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="apariencia_aceptar_rechazar.css">

</head>

<body>
<header class="conteiner-fluid">
    <h1>SISTEMA DE ASIGNACION DE AULAS</h1>
</header>

<form>
    <table>
        <tr>
            <th>id</th>
            <th>nombre</th>
            <th>correo</th>
            <th>aceptar</th>
            <th>rechazar</th>
        </tr>
        <?php
            include("vistaParaAceptar.php");
        ?>
    </table>
</form>

<footer class="conteiner-fluid">
  <center><h2>Universidad Mayor de San Simon</h2></center>
</footer>
</body>

</html>