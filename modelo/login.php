<?php
require_once "conexion.php";

class Usuario extends Conexion{

    private $conex;
    private $nombre;
    private $user;
    private $password;
    private $status;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

/*GETTER Y SETTER
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getUser(){
        return $this->user;
    }
    public function setUser($user){
        $this->user = $user;
    }

    public function getPassword(){
        return $this->password;
    }
    public function setPassword($password){
        $this->user = $password;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }    */


    public function mostrar($item, $valor){
            // Preparamos la consulta SQL usando un parámetro de tipo dinámico
            $sql = "SELECT * FROM usuarios WHERE $item = :$item";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
            #creo que fetch assoc no 
        }
    
}