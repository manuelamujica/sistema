<?php
require_once 'conexion.php';

class Caja extends Conexion {
    private $conex;

    // Propiedades
    private $cod_caja;
    private $nombre;
    private $cod_usuario;
    private $cod_divisa;
    private $fecha_apertura;
    private $fecha_cierre;
    private $monto_apertura;
    private $monto_cierre;
    private $estado;
    private $descripcion;
    private $monto;
    private $tipo;

    public function __construct()
    {
      $this->conex = new Conexion();
      $this->conex = $this->conex->conectar();
    }

    // Setters y Getters
    public function setCodCaja($cod_caja) { $this->cod_caja = $cod_caja; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCodUsuario($cod_usuario) { $this->cod_usuario = $cod_usuario; }
    public function setCodDivisa($cod_divisa) { $this->cod_divisa = $cod_divisa; }
    public function setFechaApertura($fecha_apertura) { $this->fecha_apertura = $fecha_apertura; }
    public function setFechaCierre($fecha_cierre) { $this->fecha_cierre = $fecha_cierre; }
    public function setMontoApertura($monto_apertura) { $this->monto_apertura = $monto_apertura; }
    public function setMontoCierre($monto_cierre) { $this->monto_cierre = $monto_cierre; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setMonto($monto) { $this->monto = $monto; }
    public function setTipo($tipo) { $this->tipo = $tipo; }

    // Métodos
    public function abrirCaja() {
        $sql = "INSERT INTO caja (nombre, cod_usuario, cod_divisa, fecha_apertura, monto_apertura, estado) 
                VALUES (:nombre, :cod_usuario, :cod_divisa, :fecha_apertura, :monto_apertura, :estado)";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':cod_usuario', $this->cod_usuario);
        $stmt->bindParam(':cod_divisa', $this->cod_divisa);
        $stmt->bindParam(':fecha_apertura', $this->fecha_apertura);
        $stmt->bindParam(':monto_apertura', $this->monto_apertura);
        $stmt->bindParam(':estado', $this->estado);
        
        return $stmt->execute() ? 1 : 0;
    }

    public function cerrarCaja($cod_caja) {
        $sql = "UPDATE caja SET 
                fecha_cierre = :fecha_cierre, 
                monto_cierre = :monto_cierre, 
                estado = :estado 
                WHERE cod_caja = :cod_caja";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':fecha_cierre', $this->fecha_cierre);
        $stmt->bindParam(':monto_cierre', $this->monto_cierre);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':cod_caja', $cod_caja);
        
        return $stmt->execute() ? 1 : 0;
    }

    public function editarCaja() {
        $sql = "UPDATE caja SET 
                nombre = :nombre,
                cod_divisa = :cod_divisa,
                monto_apertura = :monto_apertura,
                estado = :estado
                WHERE cod_caja = :cod_caja";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':cod_divisa', $this->cod_divisa);
        $stmt->bindParam(':monto_apertura', $this->monto_apertura);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':cod_caja', $this->cod_caja);
        
        return $stmt->execute() ? 1 : 0;
    }

    public function eliminarCaja($cod_caja) {
        // Primero eliminamos los detalles
        $sql_detalles = "DELETE FROM detalle_caja WHERE cod_caja = :cod_caja";
        $stmt_detalles = $this->conex->prepare($sql_detalles);
        $stmt_detalles->bindParam(':cod_caja', $cod_caja);
        $stmt_detalles->execute();
        
        // Luego eliminamos la caja
        $sql = "DELETE FROM caja WHERE cod_caja = :cod_caja";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_caja', $cod_caja);
        
        return $stmt->execute() ? 1 : 0;
    }

    public function agregarDetalle($cod_caja) {
        $sql = "INSERT INTO detalle_caja (cod_caja, descripcion, monto, tipo) 
                VALUES (:cod_caja, :descripcion, :monto, :tipo)";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_caja', $cod_caja);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':monto', $this->monto);
        $stmt->bindParam(':tipo', $this->tipo);
        
        return $stmt->execute() ? 1 : 0;
    }

    public function mostrarCajas() {
        $sql = "SELECT c.*, u.nombre as nombre_usuario, d.nombre as nombre_divisa, d.abreviatura
                FROM caja c
                LEFT JOIN usuarios u ON c.cod_usuario = u.cod_usuario
                LEFT JOIN divisas d ON c.cod_divisa = d.cod_divisa
                ORDER BY c.cod_caja DESC";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalleCaja($cod_caja) {
        $sql = "SELECT * FROM detalle_caja 
                WHERE cod_caja = :cod_caja 
                ORDER BY fecha DESC";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_caja', $cod_caja);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cajaAbierta() {
        $sql = "SELECT c.*, u.nombre as nombre_usuario, d.nombre as nombre_divisa, d.abreviatura
                FROM caja c
                LEFT JOIN usuarios u ON c.cod_usuario = u.cod_usuario
                LEFT JOIN divisas d ON c.cod_divisa = d.cod_divisa
                WHERE c.estado = 'abierta' 
                ORDER BY c.cod_caja DESC 
                LIMIT 1";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCaja($cod_caja) {
        $sql = "SELECT c.*, u.nombre as nombre_usuario, d.nombre as nombre_divisa, d.abreviatura
                FROM caja c
                LEFT JOIN usuarios u ON c.cod_usuario = u.cod_usuario
                LEFT JOIN divisas d ON c.cod_divisa = d.cod_divisa
                WHERE c.cod_caja = :cod_caja";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cod_caja', $cod_caja);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>