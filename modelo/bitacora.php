<?php
require_once 'conexion.php';

class Bitacora extends Conexion
{

    public function __construct()
  {
    parent::__construct(_SEC_DB_HOST_, _SEC_DB_NAME_, _SEC_DB_USER_, _SEC_DB_PASS_);  
  }

    private function registrar($cod_usuario, $accion, $detalles, $modulo = '')
    {
      parent::conectarBD();
        $query = "INSERT INTO bitacora (cod_usuario, accion, detalles, modulo) VALUES (:cod_usuario, :accion, :detalles, :modulo)";
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':cod_usuario', $cod_usuario);
        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':detalles', $detalles);
        $stmt->bindParam(':modulo', $modulo);
        $resul=$stmt->execute();
      parent::desconectarBD();
      return $resul;
    }

    public function obtenerRegistros()
    {
      parent::conectarBD();
        $query = "SELECT bitacora.fecha, bitacora.accion, bitacora.modulo, bitacora.detalles, usuarios.nombre FROM bitacora inner join usuarios on bitacora.cod_usuario = usuarios.cod_usuario ORDER BY fecha DESC";
        $stmt = $this->conex->query($query);
        $resul=$stmt->fetchAll(PDO::FETCH_ASSOC);
      parent::desconectarBD();
      return $resul;
    }

    function registrarEnBitacora($cod_usuario, $accion, $detalles, $modulo = '')
    {
        $this->registrar($cod_usuario, $accion, $detalles, $modulo);
    }
    public function eliminarPorFechas($fechaInicio, $fechaFin)
    {
        parent::conectarBD();
    
        // Incluir el inicio del primer día y el final del último día
        $fechaInicio .= " 00:00:00";
        $fechaFin .= " 23:59:59"; // Esto incluye todo el día hasta el último segundo
    
        $query = "DELETE FROM bitacora WHERE fecha BETWEEN :fechaInicio AND :fechaFin";
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
    
        $resultado = $stmt->execute();
    
        parent::desconectarBD();
        return $resultado;
    }


}