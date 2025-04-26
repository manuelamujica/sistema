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

    public function __construct() {
        /*$this->conex = (new Conexion())->conectar();*/
    }

    public function setNombre($valor) {
        $resultado = $this->validarTexto($valor, 'nombre', 2, 50);
        if ($resultado === true) {
            $this->nombre = $valor;
        } else {
            $this->errores['nombre'] = $resultado;
        }
    }

    public function setApellido($valor) {
        $resultado = $this->validarTexto($valor, 'apellido', 2, 50);
        if ($resultado === true) {
            $this->apellido = $valor;
        } else {
            $this->errores['apellido'] = $resultado;
        }
    }

    public function setCedula($valor) {
        $resultado = $this->validarNumerico($valor, 'cedula', 6, 12);
        if ($resultado === true) {
            $this->cedula = $valor;
        } else {
            $this->errores['cedula'] = $resultado;
        }
    }

    public function setTelefono($valor) {
        $resultado = $this->validarTelefono($valor);
        if ($resultado === true) {
            $this->telefono = $valor;
        } else {
            $this->errores['telefono'] = $resultado;
        }
    }

    public function setEmail($valor) {
        $resultado = $this->validarEmail($valor);
        if ($resultado === true) {
            $this->email = $valor;
        } else {
            $this->errores['email'] = $resultado;
        }
    }

    public function setDireccion($valor) {
        $resultado = $this->validarAlfanumerico($valor, 'direccion', 5, 100);
        if ($resultado === true) {
            $this->direccion = $valor;
        } else {
            $this->errores['direccion'] = $resultado;
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
            return $r=0;
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
            $r = 0;
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