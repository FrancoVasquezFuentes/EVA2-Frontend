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
        $sql = "SELECT id, nombre, icono, texto, texto_adicional, activo FROM info_contacto;";
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
        $sql = "INSERT INTO mantenimiento_info (nombre, icono, texto, texto_adicional, activo) VALUES ('$_nuevoObjeto->nombre', '$_nuevoObjeto->icono', '$_nuevoObjeto->texto', '$_nuevoObjeto->texto_adicional', true)";
        
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
        $sql = "UPDATE info_contacto SET activo = $_accion WHERE id = $_id;";
        
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
        $sql = "UPDATE info_contacto SET nombre = '$_nombre->nombre', icono = '$_nombre->icono', texto = '$_nombre->texto', texto_adicional = '$_nombre->texto_adicional', WHERE id = $_id;";
        
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
        $sql = "DELETE FROM info_contacto WHERE id = $_id;";
        
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