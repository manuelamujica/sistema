<?php 
require_once "conexion.php";
class General extends Conexion{

    private $rif;
    private $nombre;
    private $direccion;
    private $telefono;
    private $email;
    private $descripcion;
    private $logo;

    public function __construct(){
        parent::__construct( _DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

#GETTER Y SETTER
    public function getRif(){
        return $this->rif;
    }
    public function setRif($rif){
        $this->rif = $rif;
    }
    public function getNom(){
        return $this->nombre;
    }
    public function setNom($nombre){
        $this->nombre = $nombre;
    }
    public function getDir(){
        return $this->direccion;
    }
    public function setDir($direccion){
        $this->direccion = $direccion;
    }
    public function gettlf(){
        return $this->telefono;
    }
    public function settlf($telefono){
        $this->telefono = $telefono;
    }
    public function getemail(){
        return $this->email;
    }
    public function setemail($email){
        $this->email = $email;
    }
    public function getDescri(){
        return $this->descripcion;
    }
    public function setDescri($descripcion){
        $this->descripcion = $descripcion;
    }
    public function getlogo(){
        return $this->logo;
    }
    public function setlogo($logo){
        $this->logo = $logo;
    }
/*==============================
REGISTRAR INFO DE EMPRESA
================================*/
    private function registrar(){
        $this->conectarBD();
        $sql = "INSERT INTO empresa(rif,nombre,direccion,telefono,email,descripcion,logo) VALUES(:rif,:nombre,:direccion,:telefono,:email,:descripcion,:logo)";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":rif", $this->rif);
        $strExec->bindParam(":nombre", $this->nombre);
        $strExec->bindParam(":direccion", $this->direccion);
        $strExec->bindParam(":telefono", $this->telefono);
        $strExec->bindParam(":email", $this->email);
        $strExec->bindParam(":descripcion", $this->descripcion);
        $strExec->bindParam(":logo", $this->logo);
        $resul = $strExec->execute();
        parent::desconectarBD();
        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;

    }
    public function getregistrar(){
        return $this->registrar();
    }

/*==============================
MOSTRAR INFO DE EMPRESA
================================*/
    public function mostrar(){
        $this->conectarBD();
        $registro="select * from empresa";
        parent::conectarBD();
        $consulta=$this->conex->prepare($registro);
        $resul=$consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        if($resul){
            return $datos;
        }else{
            return [];
        }
    }

    //VALIDAR REGISTRO
    public function buscar(){
        $this->conectarBD();
        $registro="select count(*) as total from empresa";
        $resultado= "";
        parent::conectarBD();
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
            if($resul){
                if($resultado['total']>0){
                return $resultado;
            }else{
                return false;
            }
        }
    }

    public function subirlogo($valor){
        $nombre_logo = $valor['name'];
        $tmp_logo = $valor['tmp_name'];
        $ruta_logo = "vista/dist/img/".$nombre_logo;
        move_uploaded_file($tmp_logo, $ruta_logo);
        $this->logo = $ruta_logo; // Guardar la ruta del archivo
        //el archivo de imagen se guardará en la carpeta logos con move_uploaded_file
    }

/*==============================
EDITAR INFO DE EMPRESA
================================*/
private function editar(){
    $this->conectarBD();
    $registro = "UPDATE empresa SET nombre = :nombre, telefono=:telefono, email=:email, direccion = :direccion, descripcion = :descripcion, logo=:logo WHERE rif = :rif";
    parent::conectarBD();
    $strExec = $this->conex->prepare($registro);
    $strExec->bindParam(':rif',$this->rif);
    $strExec->bindParam(':nombre',$this->nombre);
    $strExec->bindParam(':telefono',$this->telefono);
    $strExec->bindParam(':email',$this->email);
    $strExec->bindParam(':direccion',$this->direccion);
    $strExec->bindParam(':descripcion',$this->descripcion);
    $strExec->bindParam(':logo', $this->logo);
    $resul = $strExec->execute();
    parent::desconectarBD();
    if($resul == 1){
        $r = 1;
    }else{
        $r = 0;
    }
    return $r;
}

public function geteditar(){
    return $this->editar();
}
}

