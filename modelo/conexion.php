<?php

require_once "config/config.php";

class Conexion extends PDO{
	protected $conex;
	private $conexionActiva = false;

	public function __construct(){
		/*$link = "mysql:host=" ._DB_HOST_. ";dbname=" ._DB_NAME_. ";charset=utf8";

		try{

			$this->conex = new PDO($link, _DB_USER_, _DB_PASS_);
			$this->conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			die("Conexión Fallida" . $e->getMessage());
		}*/
	}
		
	/*public function conectar(){
	return $this->conex;

	}*/

	public function conectarBD() {

		if(!$this->conexionActiva) {
			$link = "mysql:host=" ._DB_HOST_. ";dbname=" ._DB_NAME_. ";charset=utf8";

			try {
				$this->conex = new PDO($link, _DB_USER_, _DB_PASS_);
				$this->conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conexionActiva = true;
			} catch (PDOException $e) {
				die("conexión fallida" . $e->getMessage());
			}
			
		}
		return $this->conex;
	}

	public function desconectarBD() {
		if($this->conexionActiva) {
			$this->conex = null;
			$this->conexionActiva = false;
		}
	}

}