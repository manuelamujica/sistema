<?php 
#Requerir al controlador
require_once "controlador/login.php";
?>
<div class="login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h1 class="h1"><b>SAVYC</h1>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Iniciar sesión</p>
            <form method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword" required >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!--<div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember"> Recuérdame </label>
                        </div>
                    </div>-->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block" name="ingresar">Ingresar</button>
                    </div>
                </div>
                </form>
            <!--<p class="mb-1">
                <a href="#">Olvidé mi contraseña</a>
            </p>-->
        </div>
    </div>
</div>
</div>
