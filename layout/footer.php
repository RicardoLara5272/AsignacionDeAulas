<footer class="footer">
    <ul class="social_icon">
        <li><a href="https://www.facebook.com/Noticias-FCyT-UMSS-1239463812757536" target="_black">
                <ion-icon name="logo-facebook" role="img" class="md hydrated" aria-label="logo facebook"></ion-icon>
            </a></li>
        <li><a href="https://twitter.com/UmssFcyt" target="_black">
                <ion-icon name="logo-twitter" role="img" class="md hydrated" aria-label="logo twitter"></ion-icon>
            </a></li>
        <li><a href="https://github.com/RicardoLara5272/AsignacionDeAulas" target="_black">
                <ion-icon name="logo-github" role="img" class="md hydrated" aria-label="logo github"></ion-icon>
            </a></li>
            <li><a href="https://www.instagram.com/fcytoficial/" target="_black">
                <ion-icon name="logo-instagram" role="img" class="md hydrated" aria-label="logo instagram"></ion-icon>
            </a></li>
    </ul>
    <p>@2022 Gerf Software S.R.L | Contactos: (+591) 70791322</p>
</footer>
<script>
    $('#topheader .navbar-nav li').on('click', function() {
        $('#topheader .navbar-nav').find('li.active').removeClass('active');
        $(this).parent('li').addClass('active');
    });
    const iconEye = document.querySelector(".icon-eye");
    iconEye.addEventListener("click", function() {
        const icon = this.querySelector("i");

        if (this.nextElementSibling.type === 'password') {
            this.nextElementSibling.type = 'text';
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            this.nextElementSibling.type = 'password';
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });
</script>
<!-- 
<script src="http://asignaciondeaulas/vista/scrip.js"></script>
<script src="http://asignaciondeaulas/controlador/controladorReserva.js"></script>
<script src="http://asignaciondeaulas/controlador/controladorCompartida.js"></script> -->
</body>

</html>