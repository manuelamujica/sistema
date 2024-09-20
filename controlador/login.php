<?php

require_once "modelo/usuario.php"; 

$objuser= new Usuario();

if(isset($_POST["ingresar"])){

	if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
	preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])){

		$item = "user";
		$valor = $_POST["ingUsuario"];

        $respuesta = $objuser->mostrar($item, $valor);

	}

	if (!empty($respuesta) && isset($respuesta["user"])) {
		// Verificamos la contraseña utilizando password_verify() para mayor seguridad
		if ($respuesta["user"] == $_POST["ingUsuario"] && password_verify($_POST["ingPassword"], $respuesta["password"])) {
			$_SESSION["iniciarsesion"] = "ok";
			echo '<script>window.location="inicio";</script>';
		} else {
			echo "<script>
			alert('Error al ingresar, vuelve a intentarlo');
			location = 'login';
			</script>";
			#echo '<div class="alert alert-danger">Error al ingresar, vuelve a intentarlo</div>';
		}
	} else {
		echo "<script>
		alert('Usuario no encontrado');
		location = 'login';
		</script>";
	}
		#echo: Lo muestra bonito pero no agarra las clases de adminLTE
		#'<div class="alert alert-danger">Usuario no encontrado</div>';
	
	
}

