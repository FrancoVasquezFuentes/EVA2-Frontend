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
        $sql = "SELECT id, nombre, imagen, activo FROM imagen;";
        $rs = mysqli_query($con->getConnection(), $sql);
        if ($rs) {
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
        $sql = "INSERT INTO imagen (id, nombre, imagen, activo) VALUES ($id, '$_nuevoObjeto->nombre', '$_nuevoObjeto->imagen', true)";
        
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
        $sql = "UPDATE imagen SET activo = $_accion WHERE id = $_id;";
        
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

    public function putNombreById($_nuevo, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE imagen SET nombre = '$_nuevo' WHERE id = $_id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection()
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putImagenById($_nuevo, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE imagen SET imagen = '$_nuevo' WHERE id = $_id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection()
        if ($rs) {
            return true;
        }
        return null;
    }

    public function deleteById($_id)
    {
        $con = new Conexion();
        $sql = "DELETE FROM imagen WHERE id = $_id;";
        
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
