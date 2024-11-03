<?php

require_once 'conexion.php';
class Descarga extends Conexion{
    private $conex;

    #Descarga
    private $fecha;
    private $descripcion;
    private $status;

    #Detalle
    private $cantidad;
    private $statusdetalle;

    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    public function get
}
