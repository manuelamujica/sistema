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

$cloudflare = $hayinternet; //true si hay o false si no

$obj = new General();
$objuser= new Usuario();
$objRol= new Rol();
$objbitacora = new Bitacora();

if (isset($_POST["ingresar"])) {

		if ($cloudflare && isset($_POST['cf-turnstile-response'])) {
			$token = $_POST['cf-turnstile-response'];
			$secret_key = '0x4AAAAAABUTeqxI-BRIgdI3RunK5-wAFfc';
			$ip = $_SERVER['REMOTE_ADDR'];

			$data = [
				'secret' => $secret_key,
				'response' => $token,
				'remoteip' => $ip
			];

			// Inicializar cURL
			$ch = curl_init();

			// Configurar opciones de cURL
			curl_setopt($ch, CURLOPT_URL, 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Obtener la respuesta en variable, no en pantalla
			curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Opcional: evitar que quede colgado si hay problemas

			// Ejecutar la solicitud
			$response = curl_exec($ch);

			// Verificar errores de cURL
			if (curl_errno($ch)) {
				curl_close($ch);
				$_SESSION['login'] = [
					"title" => "Error",
					"message" => "Error al verificar el CAPTCHA de Cloudflare.",
					"icon" => "error"
				];
				header('Location: login');
				exit;
			}

			// Cerrar cURL
			curl_close($ch);

			// Decodificar respuesta JSON
			$result_json = json_decode($response, true);

			// Validar si el CAPTCHA fue exitoso
			if (!$result_json['success']) {
				$_SESSION['captcha'] = '';
				$_SESSION['login'] = [
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
			exit; // pendiente
		}


		//Validar el usuario y la contraseña
		$errores=[];

		try{
			$objuser->setDatos($_POST);
			$objuser->check();

		} catch(Exception $e){

			$errores[] = $e->getMessage();
		}

		if(!empty($errores)){
			$login = [
				"title" => "Error",
				"message" => implode(" ", $errores),
				"icon" => "error"
			];
			header('Location: login');
			exit;

		} else {

			//Si no hay errores, procedemos a validar el usuario y la contraseña
			$respuesta = $objuser->mostrar($_POST['ingUsuario']);

			if (!empty($respuesta) && isset($respuesta["user"]) && $respuesta["status"] == 1) {

				if ($respuesta["user"] == $_POST["ingUsuario"] && password_verify($_POST["ingPassword"], $respuesta["password"])) {

					$_SESSION["iniciarsesion"] = "ok";
					$_SESSION["user"] = $respuesta["user"];
					$_SESSION["nombre"] = $respuesta["nombre"];
					$_SESSION["cod_usuario"]=$respuesta["cod_usuario"];
					$rol=$objRol->consultarLogin($respuesta["cod_tipo_usuario"]);
					$_SESSION["rol"] = $rol["rol"];

					$_SESSION["permisos"] = [];
					
					$accesos = $objuser->accesos($respuesta["cod_usuario"]);

					foreach ($accesos as $permisos) {
						$modulo = $permisos["modulos"];
						$accion = $permisos["accion"];
					
						if (!isset($_SESSION["permisos"][$modulo])) {
							$_SESSION["permisos"][$modulo] = [];
						}
					
						// Marcamos la acción permitida con 1
						$_SESSION["permisos"][$modulo][$accion] = 1;
						} 

						echo '<script>
						window.location="inicio";
						</script>';
						$objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Acceso al sistema', $_POST["ingUsuario"], 'Inicio');

					} else {
						$login = [
							"title" => "Error",
							"message" => "Usuario o contraseña incorrecta.",
							"icon" => "error"
						];
					}
			} else {
				$login = [
					"title" => "Error",
					"message" => "Error de acceso",
					"icon" => "error"
				];
			}
			
		}
	}
}

