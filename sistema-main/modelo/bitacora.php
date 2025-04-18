
<?php
require_once 'conexion.php';

class Bitacora extends Conexion
{
    private $conex;

    public function __construct()
  {
    $this->conex = new Conexion();
    $this->conex = $this->conex->conectar();
  }

    public function registrar($cod_usuario, $accion, $detalles, $modulo = '')
    {
        $query = "INSERT INTO bitacora (cod_usuario, accion, detalles, modulo) VALUES (:cod_usuario, :accion, :detalles, :modulo)";
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':cod_usuario', $cod_usuario);
        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':detalles', $detalles);
        $stmt->bindParam(':modulo', $modulo);
        return $stmt->execute();
    }

    public function obtenerRegistros()
    {
        $query = "SELECT bitacora.fecha, bitacora.accion, bitacora.modulo, bitacora.detalles, usuarios.nombre FROM bitacora inner join usuarios on bitacora.cod_usuario = usuarios.cod_usuario ORDER BY fecha DESC";

        $stmt = $this->conex->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

function registrarEnBitacora($cod_usuario, $accion, $detalles, $modulo = '')
{
   



    $this->registrar($cod_usuario, $accion, $detalles, $modulo);
}


}


