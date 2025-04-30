<?php
require_once 'conexion.php';

class Bitacora extends Conexion
{


    public function __construct()
  {
  }

    public function registrar($cod_usuario, $accion, $detalles, $modulo = '')
    {
        $query = "INSERT INTO bitacora (cod_usuario, accion, detalles, modulo) VALUES (:cod_usuario, :accion, :detalles, :modulo)";
        $this->conectarBD();
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':cod_usuario', $cod_usuario);
        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':detalles', $detalles);
        $stmt->bindParam(':modulo', $modulo);
        $resultado = $stmt->execute();
        $this->desconectarBD();
        return $resultado;
    }

    public function obtenerRegistros()
    {
        $query = "SELECT bitacora.fecha, bitacora.accion, bitacora.modulo, bitacora.detalles, usuarios.nombre FROM bitacora inner join usuarios on bitacora.cod_usuario = usuarios.cod_usuario ORDER BY fecha DESC";
        $this->conectarBD();
        $stmt = $this->conex->query($query);
        $this->desconectarBD();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

function registrarEnBitacora($cod_usuario, $accion, $detalles, $modulo = '')
{
   



    $this->registrar($cod_usuario, $accion, $detalles, $modulo);
}


}