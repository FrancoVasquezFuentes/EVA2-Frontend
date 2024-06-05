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
        $sql = "SELECT equipo.id, equipo.tipo, equipo.texto, equipo.activo, imagen.nombre AS imagen_nombre, imagen.imagen AS imagen_url 
                FROM equipo 
                LEFT JOIN equipo_imagen ON equipo.id = equipo_imagen.equipo_id 
                LEFT JOIN imagen ON equipo_imagen.imagen_id = imagen.id;";
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

    public function postNuevo($_nuevoObjeto) {
        $con = new Conexion();
        $conn = $con->getConnection();
        $id = count($this->getAll()) + 1;
        $tipo = mysqli_real_escape_string($conn, $_nuevoObjeto->tipo);
        $texto = mysqli_real_escape_string($conn, $_nuevoObjeto->texto);
        $sql = "INSERT INTO equipo (id, tipo, texto, activo) VALUES ($id, '$tipo', '$texto', true)";
        $success = false;
    
        try {
            mysqli_begin_transaction($conn); // Start a transaction
            $success = mysqli_query($conn, $sql);
            $equipo_id = mysqli_insert_id($conn);
    
            // Insertar imagenes si se dan
            if (isset($_nuevoObjeto->imagenes) && is_array($_nuevoObjeto->imagenes)) {
                foreach ($_nuevoObjeto->imagenes as $imagen_id) {
                    $sqlCount = "SELECT COUNT(id) AS count FROM equipo_imagen";
                    $result = mysqli_query($conn, $sqlCount);
                    $row = mysqli_fetch_assoc($result);
                    $new_id = $row['count'] + 1;
    
                    $sqlImagen = "INSERT INTO equipo_imagen (id, equipo_id, imagen_id) VALUES ($new_id, $equipo_id, $imagen_id)";
                    if (!mysqli_query($conn, $sqlImagen)) {
                        throw new Exception("Failed to insert image ID: $imagen_id");
                    }
                }
            }
    
            mysqli_commit($conn); 
        } catch (Exception $e) {
            mysqli_rollback($conn); 
            $success = false;
        }
    
        $con->closeConnection();
        return $success;
    }

    public function patchEncenderApagar($_id, $_accion) {
        $con = new Conexion();
        $activo = $_accion === 'encender' ? 1 : 0;
        $sql = "UPDATE equipo SET activo = $activo WHERE id = $_id";
        $success = false;

        try {
            $success = mysqli_query($con->getConnection(), $sql);
        } catch (Exception $e) {
            $success = false;
        }

        $con->closeConnection();
        return $success;
    }

    public function putTextoById($_nuevo, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE equipo SET texto = '$_nuevo' WHERE id = $_id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function putTipoById($_nuevo, $_id)
    {
        $con = new Conexion();
        $sql = "UPDATE equipo SET tipo = '$_nuevo' WHERE id = $_id;";
        $rs = false;
        try {
            $rs = mysqli_query($con->getConnection(), $sql);
        } catch (\Throwable $th) {
            $rs = false;
        }
        $con->closeConnection();
        if ($rs) {
            return true;
        }
        return null;
    }

    public function deleteById($_id) {
        $con = new Conexion();
        $sql = "DELETE FROM equipo WHERE id = $_id";
        $success = false;

        try {
            $success = mysqli_query($con->getConnection(), $sql);

            $sqlDelete = "DELETE FROM equipo_imagen WHERE equipo_id = $_id";
            mysqli_query($con->getConnection(), $sqlDelete);
        } catch (Exception $e) {
            $success = false;
        }

        $con->closeConnection();
        return $success;
    }
}