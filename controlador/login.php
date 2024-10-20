<?php

require_once "modelo/usuarios.php"; 
require_once "modelo/general.php";

$obj = new General();
$objuser= new Usuario();

if(isset($_POST["ingresar"])){

	if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
	preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])){ //Pendiente validar caracteres especiales...

		$item = "user";
		$valor = $_POST["ingUsuario"];

        $respuesta = $objuser->mostrar($item, $valor);

	}

	if (!empty($respuesta) && isset($respuesta["user"])) {
		// Verificamos la contraseña utilizando password_verify()
		if ($respuesta["user"] == $_POST["ingUsuario"] && password_verify($_POST["ingPassword"], $respuesta["password"])) {
			
			$_SESSION["iniciarsesion"] = "ok";
			$_SESSION["user"] = $respuesta["user"];
			$_SESSION["nombre"] = $respuesta["nombre"];

			$_SESSION["configuracion"]=0;
			$_SESSION["inventario"]=0;
			$_SESSION["venta"]=0;
			$_SESSION["compra"]=0;

			//Obtenemos los permisos asociados al usuario
			$accesos = $objuser->accesos($respuesta["cod_usuario"]);
			foreach($accesos as $cod_permiso){
				if ($cod_permiso["cod_permiso"] == 1) {
					$_SESSION["configuracion"] = 1;
				} else if ($cod_permiso["cod_permiso"] == 2) {
					$_SESSION["inventario"] = 1;
				} else if ($cod_permiso["cod_permiso"] == 3) {
					$_SESSION["venta"] = 1;
				} else if ($cod_permiso["cod_permiso"] == 4) {
					$_SESSION["compra"] = 1;
				}
			}
			
			//obtenemos el logo de la empresa
			$logo = $obj->mostrar();
			if(!empty($logo)){
			$_SESSION["logo"] = $logo[0]["logo"];
			}
			echo '<script>
			window.location="inicio";
			</script>';

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

