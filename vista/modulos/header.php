
<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="" src="vista/dist/img/logo-horizontal.png" alt="Logo-Don-Pedro" height="200" width="200">
</div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Navbar links IZQUIERDA-->
    <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="inicio" class="nav-link">Inicio</a> 
    </li>
    
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Ayuda
        </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
        <a class="dropdown-item" href="#">Manual de usuario</a>
    </div>
    </li>
    </ul>

    <!-- Navbar LINKS DERECHA -->
    <ul class="navbar-nav ml-auto">

    <!-- Navbar BUSCADOR -->
    <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
        <form class="form-inline">
            <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
                </button>
            </div>
            </div>
        </form>
        </div>
    </li>

    <!-- Navbar FULLSCREEN -->
    <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
        </a>
    </li>
    
    <!-- Navbar USUARIO -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="usuario">
            <i class="fa fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header"> <?php echo $_SESSION["nombre"] ?></span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Ver perfil
        </a>
        <div class="dropdown-divider"></div>
        <a href="cerrarsesion" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
        </a>
        </div>
    </li>
    </ul>
</nav>
<!-- /.navbar -->
