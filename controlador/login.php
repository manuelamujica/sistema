<?php

require_once "modelo/login.php"; 

$objuser= new Usuario();

if(isset($_POST["ingresar"])){

	if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
	preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])){

		$item = "user";
		$valor = $_POST["ingUsuario"];

        $respuesta = $objuser->mostrar($item, $valor);

	}

	if(($respuesta["user"]) == $_POST["ingUsuario"] && $respuesta["password"] == $_POST["ingPassword"]){
	
		$_SESSION["iniciarsesion"] = "ok";
		
		#echo '<br> <div class="alert alert-sucess">Bienvenido al Sistema</div>';

		echo '<script>
		window.location="inicio";
		</script>';

	
		}else{
			echo '<br><div class="alert alert-danger">Error al ingresar, vuelve a intentarlo</div>';
		}
}

	


