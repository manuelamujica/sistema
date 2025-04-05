<?php
require_once "conexion.php";
require_once "global.php";
class Formula extends Conexion
{
    private $cod_formula;
    private $nombre;
    private $expresion;
    private $cod_var;
    private $nombre_var;
    private $cod_operador;
    private $operador;
    private $status;
    private $conex;

    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    private function buscarformula()
    {
        $expresion = GlobalVariables::get('expresion');
        if (!empty($expresion)) {
            $sql = "SELECT * FROM formula WHERE expresion LIKE :expresion";
            $strExec = $this->conex->prepare($sql);
            $expresion = '%' . $expresion . '%';
            $strExec->bindParam(":expresion", $expresion, PDO::PARAM_STR);
            $res = $strExec->execute();
            return $strExec->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function getbuscarformula()
    {
        return $this->buscarformula();
    }

    private function registrarformula()
    {
        $nombre = GlobalVariables::get('nombre');
        $expresion = GlobalVariables::get('expresion');
        $cod_var = GlobalVariables::get('cod_var');
        $cod_operador = GlobalVariables::get('cod_operador');

        if (empty($nombre) || empty($expresion) || empty($cod_operador) || empty($cod_var)) {
            return 0; 
        }

        if (strlen($nombre) > 15 || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nombre)) {
            return 0; 
        }

        if (empty($expresion)) {
            return 0;
        }

        $sql = "INSERT INTO formula(cod_var, cod_operador, nombre, expresion, status) VALUES( :cod_var, :cod_operador, :nombre, :expresion, 1)";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre", $nombre);
        $strExec->bindParam(":expresion", $expresion);
        $strExec->bindParam(":cod_var", $cod_var);
        $strExec->bindParam(":cod_operador", $cod_operador);
        $r = $strExec->execute();
        if ($r) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getregisformula()
    {
        return $this->registrarformula();
    }

    public function consultaroperador()
    { 
        $sql = "CALL consultaroperador()";
        $strExec = $this->conex->prepare($sql);
        $res = $strExec->execute();
        $r = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($res) {
            return $r;
        } else {
            return [];
        }
    }

    public function mostrarformula()
    {
        $sql = "SELECT * FROM formula";
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $result = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $result;
        } else {
            return [];
        }
    }

    private function editarformula()
    {
        $cod_formula = GlobalVariables::get('cod_formula');
        $nombre = GlobalVariables::get('nombre');
        $nombre_origin = GlobalVariables::get('nombre_origin');
        $status = GlobalVariables::get('status');

        if (empty($nombre)) {
            return 0; 
        }

        if ($nombre !== $nombre_origin && $this->buscarformulaeditar($nombre)) {
            return 2; 
        }
        $sql = "UPDATE formula SET nombre = :nombre,  status = :status WHERE cod_formula = :cod_formula";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre", $nombre);
        $strExec->bindParam(":status", $status);
        $strExec->bindParam(":cod_formula", $cod_formula);
        $r = $strExec->execute();
        if ($r) {
            return 1;
        } else {
            return 0;
        }
    }
    public function geteditarformula()
    {
        return $this->editarformula();
    }

    public function buscarformulaeditar($nombre)
    {
        $sql = "SELECT * FROM formula WHERE nombre = :nombre";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $strExec->execute();
        return $strExec->fetch(PDO::FETCH_ASSOC);
    }

    private function eliminar()
    {
        $cod_formula = GlobalVariables::get('cod_formula');
        $registro = "SELECT COUNT(*) AS n_tiponomina FROM tipo_nomina WHERE cod_formula = :valor";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(":valor", $cod_formula, PDO::PARAM_INT);
        $strExec->execute();
        $resul = $strExec->fetch(PDO::FETCH_ASSOC);



        if ($resul) {

            $registro = "SELECT status FROM formula WHERE cod_formula = :valor";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(":valor", $cod_formula, PDO::PARAM_INT);
            $strExec->execute();
            $resu = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($resu['status'] != 0) { 
                $r = 'error_status';
                return $r;
            }

            if ($resul['n_tiponomina'] != 0) {
                $r = 'error_associated';
                return $r;
            }

            $f = "DELETE FROM formula WHERE cod_formula = :valor";
            $strExec = $this->conex->prepare($f);
            $strExec->bindParam(":valor", $cod_formula, PDO::PARAM_INT);
            $ress = $strExec->execute();
            if ($ress) {
                $r = 'success';
                return $r;
            } else {
                $r = 'error_delete';
                return $r;
            }
        } else {
            $r = 'error_query';
            return $r;
        }
    }


    public function geteliminar()
    {
        return $this->eliminar();
    }


    #########  SECCIÓN DE VARIABLES ###########
    private function registrarvariable($nombre_var)
    {
        $sql = "CALL registrarvar(:nombre_var)";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre_var", $nombre_var);
        $r = $strExec->execute();
        if ($r) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function getregistrarvariable($nombre_var)
    {
        return $this->registrarvariable($nombre_var);
    }

    private function buscarvariable($nombre_var)
    {
        if (!empty($nombre_var)) {
            $sql = "CALL buscarvariable(:nombre_var)";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(":nombre_var", $nombre_var, PDO::PARAM_STR);
            $res = $strExec->execute();
            return $strExec->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    public function getbuscarvariable($nombre_var)
    {
        return $this->buscarvariable($nombre_var);
    }

    public function mostrarvariable()
    {
        $valor = GlobalVariables::get('nombre_var');
        $sql = "SELECT cod_var, nombre_var FROM variables WHERE nombre_var LIKE :valor LIMIT 6";
        $strExec = $this->conex->prepare($sql);
        $buscarv = '%' . $valor . '%';
        $strExec->bindParam(':valor', $buscarv, PDO::PARAM_STR);
        $resul = $strExec->execute();
        $result = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $result;
        } else {
            return [];
        }
    }

    public function consultarvariable()
    {  
        $sql = "CALL consultarvar()";
        $strExec = $this->conex->prepare($sql);
        $res = $strExec->execute();
        $r = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($res) {
            return $r;
        } else {
            return 0;
        }
    }

    private function editarvariable()
    {
        $cod_var = GlobalVariables::get('cod_var');
        $nombre_var = GlobalVariables::get('nombre_var');
        $nombre_originv = GlobalVariables::get('nombre_originv');
        $statusv = GlobalVariables::get('statusv');
        if ($nombre_var !== $nombre_originv && $this->buscareditarv($nombre_var, $cod_var)) {
            return 2;
        }
        $sql = "UPDATE variables SET nombre_var = :nombre_var,  status = :status WHERE cod_var = :cod_var";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":nombre_var", $nombre_var);
        $strExec->bindParam(":status", $statusv);
        $strExec->bindParam(":cod_var", $cod_var);
        $r = $strExec->execute();
        if ($r) {
            return 1;
        } else {
            return 0;
        }
    }
    public function geteditarvaiable()
    {
        return $this->editarvariable();
    }

    public function buscareditarv($nombre_var, $cod_var)
{
    $sql = "SELECT * FROM variables WHERE nombre_var = :nombre_var AND cod_var != :cod_var";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(":nombre_var", $nombre_var, PDO::PARAM_STR);
    $strExec->bindParam(":cod_var", $cod_var, PDO::PARAM_INT);
    $strExec->execute();
    return $strExec->fetch(PDO::FETCH_ASSOC);
}

    private function eliminarv()
    {
        $cod_var = GlobalVariables::get('cod_var');
        $registro = "SELECT COUNT(*) AS n_formula FROM formula WHERE cod_var = :valor";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(":valor", $cod_var, PDO::PARAM_INT);
        $strExec->execute();
        $resul = $strExec->fetch(PDO::FETCH_ASSOC);
        if ($resul) {

            $registro = "SELECT status FROM variables WHERE cod_var = :valor";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(":valor", $cod_var, PDO::PARAM_INT);
            $strExec->execute();
            $resu = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($resu['status'] != 0) {
                $r = 'error_status';
                return $r;
            }

            if ($resul['n_formula'] != 0) {
                $r = 'error_associated';
                return $r;
            }

            $f = "DELETE FROM variables WHERE cod_var = :valor";
            $strExec = $this->conex->prepare($f);
            $strExec->bindParam(":valor", $cod_var, PDO::PARAM_INT);
            $ress = $strExec->execute();
            if ($ress) {
                $r = 'success';
                return $r;
            } else {
                $r = 'error_delete';
                return $r;
            }
        } else {
            $r = 'error_query';
            return $r;
        }
    }


    public function geteliminarv()
    {
        return $this->eliminarv();
    }
}
