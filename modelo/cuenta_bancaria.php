<?php
require_once "conexion.php";

class Cuenta_bancaria extends Conexion {
    private $conex;
    private $numero_cuenta;
    private $saldo;
    private $cod_banco;
    private $cod_tipo_cuenta;
    private $cod_divisa;
    private $status;


    public function __construct()
    {
       $this->conex = new Conexion();
       $this->conex = $this->conex->conectar();
    }

    // SETTERS
    public function setNumero_cuenta($numero_cuenta) {
        $this->numero_cuenta = $numero_cuenta;
    }
    public function setSaldo($saldo) {
        $this->saldo = $saldo;
    }
    public function setCod_banco($cod_banco) {
        $this->cod_banco = $cod_banco;
    }
    public function setCod_tipo_cuenta($cod_tipo_cuenta) {
        $this->cod_tipo_cuenta = $cod_tipo_cuenta;
    }
    public function setCod_divisa($cod_divisa) {
        $this->cod_divisa = $cod_divisa;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    // GETTERS
    public function getNumero_cuenta() {
        return $this->numero_cuenta;
    }
    public function getSaldo() {
        return $this->saldo;
    }

    /* ==============================
       REGISTRAR CUENTA
    ============================== */
    public function registrar() {
        $sql = "INSERT INTO cuenta_bancaria (numero_cuenta, saldo, cod_banco, cod_tipo_cuenta, cod_divisa, status) 
                VALUES (:numero_cuenta, :saldo, :cod_banco, :cod_tipo_cuenta, :cod_divisa, 1)";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(":numero_cuenta", $this->numero_cuenta);
        $stmt->bindParam(":saldo", $this->saldo);
        $stmt->bindParam(":cod_banco", $this->cod_banco);
        $stmt->bindParam(":cod_tipo_cuenta", $this->cod_tipo_cuenta);
        $stmt->bindParam(":cod_divisa", $this->cod_divisa);

        return $stmt->execute() ? 1 : 0;
    }

    /* ==============================
       EDITAR CUENTA
    ============================== */
    public function editar($codigo) {
        $sql = "UPDATE cuenta_bancaria 
                SET numero_cuenta = :numero_cuenta, saldo = :saldo, cod_banco = :cod_banco, cod_tipo_cuenta = :cod_tipo_cuenta, cod_divisa = :cod_divisa, status = :status 
                WHERE cod_cuenta_bancaria = :codigo";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(":numero_cuenta", $this->numero_cuenta);
        $stmt->bindParam(":saldo", $this->saldo);
        $stmt->bindParam(":cod_banco", $this->cod_banco);
        $stmt->bindParam(":cod_tipo_cuenta", $this->cod_tipo_cuenta);
        $stmt->bindParam(":cod_divisa", $this->cod_divisa);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":codigo", $codigo);

        return $stmt->execute() ? 1 : 0;
    }

    /* ==============================
       ELIMINAR CUENTA
    ============================== */
    public function eliminar($codigo) {
        $sql = "DELETE FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :codigo";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(":codigo", $codigo, PDO::PARAM_INT);
        return $stmt->execute() ? 1 : 0;
    }

    /* ==============================
       LISTAR CUENTAS
    ============================== */
    public function listar() {
        $sql = "SELECT cb.*, b.nombre_banco, t.descripcion AS tipo_cuenta
                FROM cuenta_bancaria cb
                INNER JOIN bancos b ON cb.cod_banco = b.cod_banco
                INNER JOIN tipo_cuenta t ON cb.cod_tipo_cuenta = t.cod_tipo_cuenta
                WHERE cb.cod_cuenta_bancaria != 1";
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==============================
       BUSCAR CUENTA POR NÚMERO
    ============================== */
    public function buscar($numero_cuenta) {
        $sql = "SELECT * FROM cuenta_bancaria WHERE numero_cuenta = :numero_cuenta";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(":numero_cuenta", $numero_cuenta);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : [];
    }

    /* ==============================
       BANCOS
    ============================== */
    public function obtenerBancos() {
        $sql = "SELECT * FROM bancos";
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==============================
       TIPOS DE CUENTA
    ============================== */
    public function obtenerTiposCuenta() {
        $sql = "SELECT * FROM tipo_cuenta";
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
