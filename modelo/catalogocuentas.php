<?php
require_once "conexion.php";

class CatalogoCuentas extends Conexion{
    private $codigo_contable;
    private $nombre;
    private $naturaleza;
    private $cuenta_padreid;
    private $nivel;
    public function __construct()
    {
        parent::__construct( _DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }
    public function getCodigoContable(){
        return $this->codigo_contable;
    }
    public function setCodigoContable($codigo_contable){
        $this->codigo_contable = $codigo_contable;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function getNaturaleza(){
        return $this->naturaleza;
    }
    public function setNaturaleza($naturaleza){
        $this->naturaleza = $naturaleza;
    }
    public function getCuentaPadreid(){
        return $this->cuenta_padreid;
    }
    public function setCuentaPadreid($cuenta_padreid){
        $this->cuenta_padreid = $cuenta_padreid;
    }
    public function getNivel(){
        return $this->nivel;
    }
    public function setNivel($nivel){
        $this->nivel = $nivel;
    }
    /*==============================
    REGISTRAR CUENTA CONTABLE CON STORED PROCEDURE
    ================================*/
    private function registrar(){
        $sql = "CALL(:codigo_contable, :nombre, :naturaleza, :cuenta_padreid, :nivel, 1)";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":codigo_contable", $this->codigo_contable, PDO::PARAM_STR);
        $strExec->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
        $strExec->bindParam(":naturaleza", $this->naturaleza);
        $strExec->bindParam(":cuenta_padreid", $this->cuenta_padreid, PDO::PARAM_INT);
        $strExec->bindParam(":nivel", $this->nivel, PDO::PARAM_INT);
        $resul = $strExec->execute();
        parent::desconectarBD();
        return $resul ? 1 : 0;  // Retorna 1 si tuvo éxito, 0 si falló
    }

    public function getregistrar(){
        return $this->registrar();
    }

    /*==============================
    CONSULTAR (TABLA) CON STORED PROCEDURE
    ================================*/

    private function consultar_cuentas(){
        $sql = "CALL consultar_cuentas_contables()";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->execute();
        $resul=$strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        return $resul;
    }

    public function getconsultar_cuentas(){
        return $this->consultar_cuentas();
    }

}
?>