<?php

class Controlador{
    private $lista;

    public function __construct()
    {
        $this->lista = [];
    }

    public function getAll()
    {
        $con = new Conexion();
        $sql = "SELECT id, nombre, texto, activo FROM mantenimiento_info;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
            $this->lista = [];
            while ($tupla = mysqli_fetch_assoc($rs)) {
                $tupla['activo'] = $tupla['activo'] == 1 ? true : false;
                array_push($this->lista, $tupla);
            }
            mysqli_free_result($rs);
        }
        $con->closeConnection();
        return $this->lista;
    }

    public function postNuevo($_nuevoObjeto)
    {
        $con = new Conexion();
        
        $id = count($this->getAll()) + 1;
        $sql = "INSERT INTO mantenimiento_info (nombre, texto, activo) VALUES ('$_nuevoObjeto->nombre', '$_nuevoObjeto->texto', true)";
        
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return null;
    }

    public function patchEncenderApagar($_id, $_accion)
    {
        $con = new Conexion();
        $activo = $_accion === 'encender' ? 1 : 0;
        $sql = "UPDATE mantenimiento_info SET activo = $_accion WHERE id = $_id";
        
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putNombreById($_nombre, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE mantenimiento_info SET nombre = '$_nombre', texto = '$_nombre' WHERE id = $_id";
        
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        
        
        $con->closeConnection();
        
        if ($rs) {
            return true;
        }
        return null;
    }

    public function deleteById($_id)
    {
        $con = new Conexion();
        $sql = "DELETE FROM mantenimiento_info WHERE id = $_id";
        
        $rs = [];
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = null;
        }
        
        $con->closeConnection();
       
        if ($rs) {
            return true;
        }
        return null;
    }
}
?>