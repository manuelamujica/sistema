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
                        <!-- Campo de contraseña con el ojito y el candado -->
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="pass" placeholder="Contraseña" name="ingPassword" required>
                            <span class="fas fa-eye icon-password"></span>
                            
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

    <!--OJITO CONTRASEÑA-->
    <script>
        const 
        icon = document.querySelector('.icon-password'),
        pass = document.getElementById('pass');
        
        icon.addEventListener('click', function(){
        const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
        pass.setAttribute('type',type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
        
        });

    /*
        modo con IF-ELSE
         //Seleccionar el campo y el icono
        const pass = document.getElementById('pass');
              icon = document.querySelector('.icon-password'),

        //Agregar evento de click al icono
        icon.addEventListener('click', e=> {
            if(pass.type === 'password'){
                pass.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else{
                pass.type='password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    
        */</script>