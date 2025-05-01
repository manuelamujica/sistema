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
        $this->conectarBD();
        $sql = "CALL(:codigo_contable, :nombre, :naturaleza, :cuenta_padreid, :nivel, 1)";  
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":codigo_contable", $this->codigo_contable, PDO::PARAM_STR);
        $strExec->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
        $strExec->bindParam(":naturaleza", $this->naturaleza);
        $strExec->bindParam(":cuenta_padreid", $this->cuenta_padreid, PDO::PARAM_INT);
        $strExec->bindParam(":nivel", $this->nivel, PDO::PARAM_INT);

        $resul = $strExec->execute();
        $this->desconectarBD();
        return $resul ? 1 : 0;  // Retorna 1 si tuvo éxito, 0 si falló
    }

    public function getregistrar(){
        return $this->registrar();
    }

    /*==============================
    CONSULTAR (TABLA) CON STORED PROCEDURE
    ================================*/

    private function consultar_cuentas(){
        $this->conectarBD();
        $sql = "CALL consultar_cuentas_contables()";
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    public function getconsultar_cuentas(){
        return $this->consultar_cuentas();
    }

}
?>