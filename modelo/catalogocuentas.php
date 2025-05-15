<?php
require_once "conexion.php";

class CatalogoCuentas extends Conexion{
    private $codigo_contable;
    private $nombre;
    private $naturaleza;
    private $cuenta_padreid;
    private $nivel;
    private $saldo;
    private $errores = [];

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
    public function getSaldo(){
        return $this->saldo;
    }
    public function setSaldo($saldo){
        $this->saldo = $saldo;
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


    public function check(){
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }
    
    #Acceder a los errores individualmente
    public function getErrores() {
        return $this->errores;
    }
    
    /*==============================
    REGISTRAR CUENTA CONTABLE
    ================================*/
    private function registrar() {
    try {
        parent::conectarBD();

        // Verificar si hay cuenta padre
        if (!empty($this->cuenta_padreid)) {
            $sql = "SELECT nivel, naturaleza FROM cuentas_contables WHERE cod_cuenta = :cuenta_padreid";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':cuenta_padreid', $this->cuenta_padreid, PDO::PARAM_INT);
            $stmt->execute();
            $padre = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$padre) {
                throw new Exception("Cuenta padre no encontrada.");
            }

            // Validar nivel
            if ($this->nivel <= $padre['nivel']) {
                throw new Exception("El nivel de la cuenta debe ser mayor que el de su cuenta padre.");
            }

            // Validar naturaleza
            if ($this->naturaleza !== $padre['naturaleza']) {
                throw new Exception("La naturaleza de la cuenta hija debe ser igual a la de su cuenta padre.");
            }
        }

        // Insertar cuenta contable
        $sqlInsert = "INSERT INTO cuentas_contables (codigo_contable, nombre_cuenta, naturaleza, cuenta_padreid, nivel, status, saldo) VALUES (:codigo_contable, :nombre, :naturaleza, :cuenta_padreid, :nivel, 1, :saldo)";

        $stmtInsert = $this->conex->prepare($sqlInsert);
        $stmtInsert->bindParam(':codigo_contable', $this->codigo_contable);
        $stmtInsert->bindParam(':nombre', $this->nombre);
        $stmtInsert->bindParam(':naturaleza', $this->naturaleza);
        $stmtInsert->bindParam(':cuenta_padreid', $this->cuenta_padreid, PDO::PARAM_INT);
        $stmtInsert->bindParam(':nivel', $this->nivel, PDO::PARAM_INT);
        $stmtInsert->bindParam(':saldo', $this->saldo);

        $stmtInsert->execute();

        return 1;

    } catch (Exception $e) {
        $this->errores[] = $e->getMessage();
        return 0;
    } finally {
        parent::desconectarBD();
    }
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


  /* =============================
  LISTAR CUENTAS PADRES POR NIVEL
  =================================*/
  public function listarcuentaspadrespornivel($nivel) {
    try {
        parent::conectarBD();
        $nivelPadre = $nivel - 1; // queremos los padres de ese nivel
        $sql = "SELECT cod_cuenta, codigo_contable, nombre_cuenta, naturaleza 
                FROM cuentas_contables 
                WHERE nivel = :nivel 
                ORDER BY codigo_contable ASC";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nivel', $nivelPadre, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        return $resultado;
    } catch (Exception $e) {
        parent::desconectarBD();
        return [];
    }
}


  /* ====================================
  GENERAR CÓDIGO CONTABLE POR NIVEL
  =======================================*/
  public function generarCodigo($nivel, $codPadre = null) {
    try {
        parent::conectarBD();

        // NIVEL 1: Código raíz
        if ($nivel == 1) {
            $sql = "SELECT MAX(CAST(codigo_contable AS UNSIGNED)) AS ultimo 
                    FROM cuentas_contables 
                    WHERE nivel = 1 AND codigo_contable NOT LIKE '%.%'";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $nuevo = $row && $row['ultimo'] ? ((int)$row['ultimo'] + 1) : 1;
            return $nuevo;
        }

        // NIVEL > 1: Buscar código del padre y generar en base a él
        if ($codPadre === null) {
            return 'VACIO/CUENTA SIN PADRE'; // No se puede sin padre
        }

        // Obtener código del padre
        $sql = "SELECT codigo_contable FROM cuentas_contables WHERE cod_cuenta = :codPadre";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':codPadre', $codPadre);
        $stmt->execute();
        $padre = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$padre) {
            return 'NO HAY CUENTA PADRE';
        }

        $codigoPadre = $padre['codigo_contable'];

        // Buscar último hijo directo
        $sqlHijos = "SELECT MAX(codigo_contable) AS ultimo 
                    FROM cuentas_contables 
                    WHERE cuenta_padreid = :codPadre";
        $stmtHijos = $this->conex->prepare($sqlHijos);
        $stmtHijos->bindParam(':codPadre', $codPadre);
        $stmtHijos->execute();
        $hijo = $stmtHijos->fetch(PDO::FETCH_ASSOC);

        if ($hijo && $hijo['ultimo']) {
            $partes = explode('.', $hijo['ultimo']);
            $ultimoSegmento = (int) end($partes) + 1;

            if ($nivel == 2) {
                $nuevoCodigo = $codigoPadre . '.' . $ultimoSegmento; // sin ceros
            } else {
                $nuevoCodigo = $codigoPadre . '.' . str_pad($ultimoSegmento, 2, '0', STR_PAD_LEFT);
            }

            return $nuevoCodigo;
        } else {
            // Primer hijo
            if ($nivel == 2) {
                return $codigoPadre . '.1'; // sin ceros
            } else {
                return $codigoPadre . '.01'; // con ceros
            }
        }

    } catch (Exception $e) {
        return 'CATCH ' . $e->getMessage();
    } finally {
        parent::desconectarBD();
    }
}


}
