<?php 
#Requerir al controlador
require_once "controlador/login.php";
?>
<!--<div id="wallpaper">-->
    <div class="login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <h1 class="h1"><b>SAVYC</h1>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Ingresa tus datos para iniciar sesión</p>
                    <form method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Campo de contraseña con el ojito y el candado -->
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="pass" placeholder="Contraseña" name="ingPassword" required>
                            <span class="fas fa-eye icon-password" data-target="pass"></span>
                            
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block" name="ingresar">Ingresar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
if (isset($login)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $login["title"]; ?>',
            text: '<?php echo $login["message"]; ?>',
            icon: '<?php echo $login["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'login';
            }
        });
    </script>
<?php endif; ?>

    <script src='vista/dist/js/modulos-js/usuarios.js'></script>
