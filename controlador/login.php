<?php

require_once "modelo/usuarios.php";
require_once "modelo/general.php";
require_once "modelo/roles.php";
require_once "modelo/bitacora.php";

$conexion = @fsockopen("www.google.com", 80, $errno, $errstr, 2);
$hayinternet = false;
if ($conexion) {
    fclose($conexion);
    $hayinternet = true;
}
$cloudflare = $hayinternet;

$obj = new General();
$objuser= new Usuario();
$objRol= new Rol();
$objbitacora = new Bitacora();


if (isset($_POST["ingresar"])) {
	// Validar CAPTCHA Cloudflare (si hay internet)
	if ($cloudflare && isset($_POST['cf-turnstile-response'])) {
		$token = $_POST['cf-turnstile-response'];
		$secret_key = '0x4AAAAAABUTeqxI-BRIgdI3RunK5-wAFfc'; // tu clave secreta real
		$ip = $_SERVER['REMOTE_ADDR'];
	
		$data = [
			'secret' => $secret_key,
			'response' => $token,
			'remoteip' => $ip
		];
	
		$options = [
			'http' => [
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => http_build_query($data),
			]
		];
	
		$context = stream_context_create($options);
		$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
	
		// SUPRIMIR WARNING EN CASO DE ERROR
		$result = @file_get_contents($url, false, $context);
	
	}else if (!$cloudflare && !isset($_POST['cf-turnstile-response'])){
			$result_json = json_decode($result, true);
	
			if (!$result_json['success']) {
				$_SESSION['captcha'] = '';
				$login = [
					"title" => "Error",
					"message" => "Código CLOUDFLARE incorrecto.",
					"icon" => "error"
				];
				header('Location: login');
				exit;
			}
		}
		
	// Validar CAPTCHA PHP
	if (isset($_POST['captchaCodigo'])) {
		$captchaCodigo = $_POST['captchaCodigo'];
		if ($captchaCodigo != $_SESSION['captcha']) {
			$_SESSION['captcha'] = ''; // Limpiar código
			
			$_SESSION['login'] = [
				"title" => "Error",
				"message" => "Usuario o contraseña incorrecta.",
				"icon" => "error"
			];
			header('Location: login');
			exit;
		}

		if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) && preg_match('/^[a-zA-Z0-9!@#$%^&*()\/,.?":{}|<>]+$/', $_POST["ingPassword"])){ 
		$valor = $_POST["ingUsuario"];
		$respuesta = $objuser->mostrar($valor);

		if (!empty($respuesta) && isset($respuesta["user"]) && $respuesta["status"] == 1) {
			
			// Verificamos la contraseña utilizando password_verify()
			if ($respuesta["user"] == $_POST["ingUsuario"] && password_verify($_POST["ingPassword"], $respuesta["password"])) {

				$_SESSION["iniciarsesion"] = "ok";
				$_SESSION["user"] = $respuesta["user"];
				$_SESSION["nombre"] = $respuesta["nombre"];
				$_SESSION["cod_usuario"]=$respuesta["cod_usuario"];
			// Para acceder al nombre del rol y guardarlo en una variable SESSION
				$rol=$objRol->consultarLogin($respuesta["cod_tipo_usuario"]);
				$_SESSION["rol"] = $rol["rol"];

				$_SESSION["producto"] = 0;
				$_SESSION["inventario"] = 0;
				$_SESSION["categoria"] = 0;
				$_SESSION["marca"] = 0;
				$_SESSION["venta"] = 0;
				$_SESSION["compra"] = 0;
				$_SESSION["cliente"] = 0;
				$_SESSION["proveedor"] = 0;
				$_SESSION["usuario"] = 0;
				$_SESSION["reporte"] = 0;
				$_SESSION["configuracion"] = 0;
				//$_SESSION["permisos"] = []; // Inicializamos el array de permisos
			
				//Obtenemos los modulos asociados al usuario
				//Obtenemos los permisos asociados al usuario
				$accesos = $objuser->accesos($respuesta["cod_usuario"]);
				foreach ($accesos as $permisos) {
					if ($permisos["cod_modulo"] == 1) {
						$_SESSION["producto"] = 1;
					} else if ($permisos["cod_modulo"] == 2) {
						$_SESSION["inventario"] = 1;
					} else if ($permisos["cod_modulo"] == 3) {
						$_SESSION["categoria"] = 1;
					} else if ($permisos["cod_modulo"] == 4) {
						$_SESSION["compra"] = 1;
					} else if ($permisos["cod_modulo"] == 5) {
						$_SESSION["venta"] = 1;
					} else if ($permisos["cod_modulo"] == 6) {
						$_SESSION["cliente"] = 1;
					} else if ($permisos["cod_modulo"] == 7) {
						$_SESSION["proveedor"] = 1;
					} else if ($permisos["cod_modulo"] == 8) {
						$_SESSION["usuario"] = 1;
					} else if ($permisos["cod_modulo"] == 9) {
						$_SESSION["reporte"] = 1;
					} else if ($permisos["cod_modulo"] == 10) {
						$_SESSION["configuracion"] = 1;
					} else if ($cod_permiso["cod_modulo"] == 11) {
						$_SESSION["marca"] = 1;
					}
					/*$modulo = $permisos["cod_modulo"];
					$accion = $permisos["cod_crud"];
				
					// Si no existe el módulo aún, lo inicializamos como array vacío
					if (!isset($_SESSION["permisos"][$modulo])) {
						$_SESSION["permisos"][$modulo] = [];
					}
				
					// Marcamos la acción permitida con 1
					$_SESSION["permisos"][$modulo][$accion] = 1;*/
					} 
				//Obtenemos la informacion de la empresa
				$logo = $obj->mostrar();
				if(!empty($logo)){
				$_SESSION["logo"] = $logo[0]["logo"];
				$_SESSION["n_empresa"] = $logo[0]["nombre"];
				$_SESSION["rif"] = $logo[0]["rif"];
				$_SESSION["telefono"] = $logo[0]["telefono"];
				$_SESSION["email"] = $logo[0]["email"];
				$_SESSION["direccion"] = $logo[0]["direccion"];
				}

				echo '<script>
				window.location="inicio";
				</script>';
				$objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Acceso al sistema', $_POST["ingUsuario"], 'Inicio');
			}//usuario o contra;sena incorrecta
			else {
				$login = [
					"title" => "Error",
					"message" => "Usuario o contraseña incorrecta.",
					"icon" => "error"
				];
			} 
		}//Si esta en la BD y esta activo, si no:
		else {
			$login = [
				"title" => "Error",
				"message" => "Error de acceso",
				"icon" => "error"
			];
		}
	} else{
		$login = [
			"title" => "Error",
			"message" => "Intenta nuevamente.",
			"icon" => "error"
		];
	}
	}
}