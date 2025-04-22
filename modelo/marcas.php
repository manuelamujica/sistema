<?php 
require_once "conexion.php";

class Marca extends Conexion{

    private $conex;
    private $nombre;
    private $status;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

#GETTER Y SETTER
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }


/*==============================
REGISTRAR MARCA
================================*/
    private function registrar(){

        $sql = "INSERT INTO marcas(nombre,status) VALUES(:nombre,1)";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre", $this->nombre);

        $resul = $strExec->execute();

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
VALIDAR MARCAS
================================*/
private function buscar($valor){
    $this->nombre=$valor;
    $registro = "SELECT * FROM marcas WHERE nombre=:nombre";
    $resultado= "";
        $dato=$this->conex->prepare($registro);
        $dato->bindParam(":nombre", $this->nombre);
        $resul=$dato->execute();
        $resultado=$dato->fetch(PDO::FETCH_ASSOC);  
        if ($resul) {
            return $resultado;
        }else{
            return false;
        }
}

public function getbuscar($valor){
    return $this->buscar($valor);
}

/*==============================
MOSTRAR MARCAS
================================*/
    private function mostrar(){
        $registro = "select * from marcas";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $r=0;
        }

    }
    public function getmostrar(){
        return $this->mostrar();
    }

/*==============================
EDITAR MARCAS
================================*/
    private function editar($valor){
        $sql="UPDATE marcas SET nombre=:nombre, status=:status WHERE cod_marca=$valor";
        $strExec = $this->conex->prepare($sql);

        #Instanciar metodo BINDPARAM
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        if($resul){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;
    }

    public function geteditar($valor){
        return $this->editar($valor);
    }    

/*==============================
ELIMINAR MARCAS
================================*/
    private function eliminar($valor){
        $sql="SELECT COUNT(*) AS count FROM marcas m JOIN productos p ON m.cod_marca = p.cod_marca WHERE m.cod_marca=$valor";
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();

        $resultado=$strExec->fetch(PDO::FETCH_ASSOC);

        if($resultado){
            if($resultado["count"]==0){
                $fisico = "DELETE FROM marcas WHERE cod_marca=$valor";
                $strExec=$this->conex->prepare($fisico);
                $delete = $strExec->execute();

                if($delete){
                    $r='success';
                    return $r;
                }else{
                    $r='error_delete';
                    return $r;
                }
            }else{
                $r='error_associated';
                return $r;
            }
            
        } else{
            $r='error_query';
            return $r;
        }
    }

    public function geteliminar($valor){
        return $this->eliminar($valor);
    }  
}
