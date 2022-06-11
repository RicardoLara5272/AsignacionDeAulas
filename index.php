
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--link href="./vista/css/stylesFooter.css" rel="stylesheet">
<link rel="stylesheet" href="./vista/css/estiloSolicitud.css"-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<!------ Include the above in your HEAD tag ---------->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300&display=swap');
    * {
    padding: 0;
    margin: 0;
    /*box-sizing: border-box;*/
    font-family: 'Noto Sans', sans-serif;
    }
    body {
        margin: 0;
        padding: 0;
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        min-height:100vh;
    }
    header {
    background:black;
    display: flex;
    padding: 20px;
    }
    .pull-left h1 {
    color:white;
    text-align: left;
    font-family: 'Noto Sans', sans-serif;
    font-size: 30px;
    padding: 0px 0px 0px 10px;
    }
    .black{
    color:black;
	position: relative;
    }
.navfooter{
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
    /*min-height: 70vh;*/
    margin-top: auto;
}
.navfooter footer{
    position: relative;
    width: 100%;
    background: #E5E0DE;
    min-height: 10px;
    padding: 10px 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.navfooter footer .social_icon{
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 10px 0;
    flex-wrap: wrap;
}
.navfooter footer .social_icon li{
    list-style: none;
}
.navfooter footer .social_icon li a{
    font-size: 1em;
    color: black;
    margin: 0 15px;
    display: inline-block;
    transition: 0.5s;
}
.navfooter footer .social_icon li a:hover{
    transform: translateY(-10px);
}
.navfooter footer p{
    color: black;
    text-align: center;
    font-size: 1em;

}
    #login .container #login-row #login-column #login-box {
        margin-top: 120px;
        width: 600px;
        height: 320px;
        border: 1px solid #E5E0DE;
        background-color: #E5E0DE;
        box-shadow: 14px 14px 31px 8px rgba(0,0,0,0.1);
        border-radius: 3rem;
    }

    #login .container #login-row #login-column #login-box #login-form {
        padding: 20px;
    }

    #login .container #login-row #login-column #login-box #login-form #register-link {
        margin-top: -85px;
        font-family: sans-serif;
    }
    .alert{
    width:100%;
    background: #ccc;
    border-radius: 6px;
    margin: 20px auto;
    }
    .btn-black {
        background: #000;
    }
    .text-white {
        color: #fff;
    }
	.icon-eye {
        position: absolute;
		right: 10px;
		top: 62%;
		transform: translateY(-62%);
		cursor: pointer;
		transition: .4s ease all;
    }
	.icon-eye:hover {
        opacity: 0.8;
    }
@media (min-width: 400px) {
    #login .container #login-row #login-column #login-box {
        width: 100%;
        height: 320px;
        
    }
}

</style>
     
<body>
<header>
    <div class="pull-left">
        <h1>Sistema de asignación de aulas</h1>
    </div>
</header>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="vista/acceso.php" method="post">
                            <h3 class="text-center black">Iniciar Sesion</h3>
                            <div class="form-group">
                                <label for="username" class="black">Usuario:</label><br>
                                <input type="text" name="username" id="username" class="form-control" required placeholder="Ej. JuanPerez">
                            </div>
                            <div class="form-group">
                                <label for="password" class="black">Contraseña:</label><br>
                                <span class="icon-eye"><i class="fa-solid fa-eye-slash"></i></span>
								<input type="password" name="password" id="password" class="form-control" required placeholder="Ingrese su contraseña...">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-black text-white btn-md" value="Ingresar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="navfooter">
        <footer>
            <ul class="social_icon">
                <li><a href="#">
                        <ion-icon name="logo-facebook" role="img" class="md hydrated" aria-label="logo facebook"></ion-icon>
                    </a></li>
                <li><a href="#">
                        <ion-icon name="logo-twitter" role="img" class="md hydrated" aria-label="logo twitter"></ion-icon>
                    </a></li>
                <li><a href="#">
                        <ion-icon name="logo-linkedin" role="img" class="md hydrated" aria-label="logo linkedin"></ion-icon>
                    </a></li>
                <li><a href="#">
                        <ion-icon name="logo-instagram" role="img" class="md hydrated" aria-label="logo instagram"></ion-icon>
                    </a></li>
            </ul>
            <p>@2022 Gerf Software S.R.L | Contactos: (+591) 70791322</p>
        </footer>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
		<script src="./vista/js/code.js"></script>
    </div>
</body>