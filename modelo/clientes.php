<?php
require_once "conexion.php";
require_once "validaciones.php";
class Clientes extends Conexion{
    use ValidadorTrait; // Usar el trait para validaciones
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $email;
    private $direccion;
    private $status;
    private $errores = [];


public function __construct(){
    $this -> conex = new Conexion();
    $this -> conex = $this->conex->conectar();
}


    public function setData($datos) {
        // Limpiar errores anteriores
        $this->errores = [];
    
        // Validar y asignar nombre
        if (isset($datos['nombre'])) {
            $resultado = $this->validarTexto($datos['nombre'], 'nombre', 2, 50);
            if ($resultado === true) {
                $this->nombre = $datos['nombre'];
            } else {
                $this->errores['nombre'] = $resultado;
            }
        }
    
        // Validar y asignar apellido
        if (isset($datos['apellido'])) {
            $resultado = $this->validarTexto($datos['apellido'], 'apellido', 2, 50);
            if ($resultado === true) {
                $this->apellido = $datos['apellido'];
            } else {
                $this->errores['apellido'] = $resultado;
            }
        }
    
        // Validar y asignar cedula
        if (isset($datos['cedula'])) {
            $resultado = $this->validarNumerico($datos['cedula'], 'cedula', 6, 12);
            if ($resultado === true) {
                $this->cedula = $datos['cedula'];
            } else {
                $this->errores['cedula'] = $resultado;
            }
        }
    
        // Validar y asignar telefono
        if (isset($datos['telefono'])) {
            $resultado = $this->validarTelefono($datos['telefono']);
            if ($resultado === true) {
                $this->telefono = $datos['telefono'];
            } else {
                $this->errores['telefono'] = $resultado;
            }
        }
    
        // Validar y asignar email
        if (isset($datos['email'])) {
            $resultado = $this->validarEmail($datos['email']);
            if ($resultado === true) {
                $this->email = $datos['email'];
            } else {
                $this->errores['email'] = $resultado;
            }
        }
    
        // Validar y asignar direccion
        if (isset($datos['direccion'])) {
            $resultado = $this->validarAlfanumerico($datos['direccion'], 'direccion', 5, 100);
            if ($resultado === true) {
                $this->direccion = $datos['direccion'];
            } else {
                $this->errores['direccion'] = $resultado;
            }
        }
    }
    // Chequear si hay errores
    public function check() {
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }


    // Si quieres acceder a los errores individualmente
    public function getErrores() {
        return $this->errores;
    }


    public function setstatus($valor){
        $this->status=$valor;
    }



    public function getNombre(){
        return $this->nombre;
    }

    public function getApellido(){
        return $this->apellido;
    }

    public function getCedula(){
        return $this->cedula;
    }
    public function getTelefono(){
        return $this->telefono;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getDireccion(){
        return $this->direccion;
    }

    public function getstatus(){
        return $this->status;
    }

    /*==============================
    REGISTRAR CLIENTE
    ================================*/

    private function registrar(){ 

        $registro = "INSERT INTO clientes(nombre,apellido,cedula_rif,telefono,email,direccion,status) VALUES(:nombre, :apellido, :cedula_rif, :telefono,:email,:direccion,1)";
        $this->conectarBD();
        #instanciar el metodo PREPARE no la ejecuta, sino que la inicializa
        $strExec = $this->conex->prepare($registro);

        #instanciar metodo bindparam
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':apellido', $this->apellido);
        $strExec->bindParam(':cedula_rif', $this->cedula);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':email', $this->email);
        $strExec->bindParam(':direccion', $this->direccion);

        $resul = $strExec->execute();

        $this->desconectarBD();
        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;
    }

    public function getRegistrar(){
        return $this->registrar();
    }

    public function consultar(){
        $this->conectarBD();
        $registro = "select * from clientes";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $this->desconectarBD();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if($resul){
            return $datos;

        }else{
            return [];
        }

    }

    public function buscar($valor){
        $this->cedula=$valor;
        $registro = "select * from clientes where cedula_rif='".$this->cedula."'";
        $resutado= "";
        $this->conectarBD();
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
        $this->desconectarBD();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            if ($resul) {
                return $resultado;
            }else{
                return [];
            }
        
    }

    public function getactualizar($valor){
        return $this->actualizar($valor);
    }

    private function actualizar($valor){
        $cod=$valor;
        $this->conectarBD();
        $registro="UPDATE clientes SET nombre=:nombre, apellido=:apellido, cedula_rif=:cedula_rif, telefono=:telefono, email=:email, direccion=:direccion, status=:status WHERE cod_cliente=$cod";

        $strExec = $this->conex->prepare($registro);

        #instanciar metodo bindparam
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':apellido', $this->apellido);
        $strExec->bindParam(':cedula_rif', $this->cedula);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':email', $this->email);
        $strExec->bindParam(':direccion', $this->direccion);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        $this->desconectarBD();
        if($resul){
            $r = 1;
        }else{
            $fisico="DELETE FROM clientes WHERE cod_cliente=$valor";
            $strExec=$this->conex->prepare($fisico);
            $strExec->execute();
            $r='success';
        }

        return $r;
    }

    public function geteliminar($valor){
        return $this->eliminar($valor);
    }

    private function eliminar($valor){
        $this->conectarBD();
        $registro="SELECT COUNT(*) AS n_ventas FROM ventas WHERE cod_cliente =$valor ";
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        $this->desconectarBD();
        if($resul){
            $resultado=$strExec->fetch(PDO::FETCH_ASSOC); 
            if ($resultado['n_ventas']>0){
                $r='venta';
            }else{
                $this->conectarBD();
                $fisico="DELETE FROM clientes WHERE cod_cliente=$valor";
                $strExec=$this->conex->prepare($fisico);
                $strExec->execute();
                $this->desconectarBD();
                $r='success';
            }
            
        }else {
            $r='error_delete';
        }
        return $r;
    }

}