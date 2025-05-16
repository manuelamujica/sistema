<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Navbar links IZQUIERDA-->
    <ul class="navbar-nav">

        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" title="Menú"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="inicio" class="nav-link">Inicio</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Ayuda
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                <a class="dropdown-item" href="vista/dist/manual/manual.pdf" target="_blank">Manual de usuario</a>
            </div>
        </li>

    </ul>
    <!-- Navbar LINKS DERECHA -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar FULLSCREEN -->

        <!-- Usuario y Notificaciones -->
        <div class="user d-flex align-items-center position-relative" style="margin-left: 5px;">
            <!-- Botón de notificación -->
            <div class="notification position-relative mr-3">
                <i class="fas fa-bell fa-lg text-dark" id="bell-icon" style="cursor: pointer;"></i>
                <!-- Contenedor de notificaciones -->
                <!-- Contenedor de notificaciones -->
                <div class="notification-dropdown">
                    <!-- Notificación 1 -->
                    <div class="notification-item">
                        <div class="notification-title">El queso esta por terminarse</div>
                        <div class="notification-meta">03/04/2025 12:30:00</div>
                        <div class="notification-desc">Responsable: Admin</div>
                        <a href="index.php?pagina=productos" class="notification-link">Haz clic para más detalles</a>
                    </div>
                    <!-- Notificación 2 -->
                    <div class="notification-item">
                        <div class="notification-title">La caja 1 esta por cerrar</div>
                        <div class="notification-meta">03/04/2025 11:00:00</div>
                        <div class="notification-desc">Responsable: Angela Rojas</div>
                        <a href="index.php?pagina=cajacopia" class="notification-link">Haz clic para más detalles</a>
                    </div>
                    <!-- Notificación 3 -->
                    <div class="notification-item">
                        <div class="notification-title">Actualiza tus proyecciones financieras</div>
                        <div class="notification-meta">03/04/2025 09:15:00</div>
                        <div class="notification-desc">Responsable: Admin</div>
                        <a href="#" class="notification-link">Haz clic para más detalles</a>
                    </div>
                    <!-- Notificación 3 -->
                    <div class="notification-item text-center">
                        <a href="#" class="notification-link">Ver más notificaciones</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Navbar USUARIO -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" title="usuario">
                <i class="fa fa-user" title="Usuario"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header"> <?php echo $_SESSION["nombre"]; ?></span>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item" data-toggle="modal" data-target="#modalPerfil">
                    <i class="fas fa-user mr-2"></i>
                    Ver perfil
                </button>
                <div class="dropdown-divider"></div>
                <a href="cerrarsesion" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Pantalla completa">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Ver perfil Modal-->
<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="modalPerfilLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Perfil de usuario</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Información del perfil -->
                <div class="col-md-12">
                    <p><b>Nombre: </b><?php echo $_SESSION['nombre']; ?></p>
                    <p><b>Usuario: </b> <?php echo $_SESSION['user']; ?> </p>
                    <p><b>Rol: </b> <?php echo $_SESSION['rol']; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="vista/dist/css/notificaciones.css">
<script src="vista/dist/js/modulos-js/notificaciones.js"></script>
<!-- FontAwesome para el ícono de la campana -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">