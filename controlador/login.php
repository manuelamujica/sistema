<?php

require_once "modelo/usuarios.php";
require_once "modelo/general.php";
require_once "modelo/roles.php";
require_once "modelo/bitacora.php";

$obj = new General();
$objuser= new Usuario();
$objRol= new Rol();
$objbitacora = new Bitacora();


if (isset($_POST["ingresar"])) {
	
	if (isset($_POST['captchaCodigo'])) {
        $captchaCodigo = $_POST['captchaCodigo'];

        //Verificamos que el código ingresado sea el mismo que el que se encuentra en la sesión
        if ($captchaCodigo != $_SESSION['captcha']) {
            $_SESSION['captcha'] = ''; // Limpiar el código CAPTCHA en la sesión
			$_SESSION['login'] = [
				"title" => "Error",
				"message" => "Usuario o contraseña incorrecta.",
				"icon" => "error"
			];
			header('Location: login');
			exit;
	}
	
	$secret_key = '0x4AAAAAABUTeqxI-BRIgdI3RunK5-wAFfc';
	$token = $_POST['cf-turnstile-response']; // Token recibido del frontend
	$ip = $_SERVER['REMOTE_ADDR']; // Dirección IP del cliente

	// Datos para la solicitud POST
	$data = [
		'secret' => $secret_key,
		'response' => $token,
		'remoteip' => $ip
	];

	// Configuración de las opciones para la solicitud POST
	$options = [
		'http' => [
			'method'  => 'POST',
			'header'  => 'Content-Type: application/x-www-form-urlencoded',
			'content' => http_build_query($data),
		]
	];
	
	$context  = stream_context_create($options);

	// Realiza la solicitud POST
	$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
	$result = file_get_contents($url, false, $context);

	// Si la solicitud falla
	if ($result === FALSE) {
		die('Error al realizar la solicitud.');
	}

	// Decodificar la respuesta JSON
	$result_json = json_decode($result, true);

	/*if ($result_json['success']) {
		echo "CAPTCHA validado con éxito.";
	} else {
		echo "Error en la validación del CAPTCHA.";
	}*/

	if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
	preg_match('/^[a-zA-Z0-9!@#$%^&*()\/,.?":{}|<>]+$/', $_POST["ingPassword"])){ 
		$valor = $_POST["ingUsuario"];
		$respuesta = $objuser->mostrar($valor);
	}

	if (!empty($respuesta) && isset($respuesta["user"]) && $respuesta["status"] == 1) {
		
		// Verificamos la contraseña utilizando password_verify()
		if ($respuesta["user"] == $_POST["ingUsuario"] && password_verify($_POST["ingPassword"], $respuesta["password"])) {

			$_SESSION["iniciarsesion"] = "ok";
			$_SESSION["user"] = $respuesta["user"];
			$_SESSION["nombre"] = $respuesta["nombre"];
			$_SESSION["cod_usuario"]=$respuesta["cod_usuario"];
			$rol=$objRol->consultarLogin($respuesta["cod_tipo_usuario"]);
			$_SESSION["rol"] = $rol["rol"];

			$_SESSION["permisos"] = []; // Inicializamos el array de permisos
			
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
			"message" => "Intenta de nuevo.. ",
			"icon" => "error"
		];
	}
	
}



