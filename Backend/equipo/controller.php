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
        $id = count($this->getAll()) + 1;
        $tipo = mysqli_real_escape_string($con->getConnection(), $_nuevoObjeto->tipo);
        $texto = mysqli_real_escape_string($con->getConnection(), $_nuevoObjeto->texto);
        $sql = "INSERT INTO equipo (id, tipo, texto, activo) VALUES ($id, '$tipo', '$texto', true)";
        $success = false;

        try {
            $success = mysqli_query($con->getConnection(), $sql);
            $equipo_id = mysqli_insert_id($con->getConnection());

            // Insert images if provided
            if (isset($_nuevoObjeto->imagenes) && is_array($_nuevoObjeto->imagenes)) {
                foreach ($_nuevoObjeto->imagenes as $imagen_id) {
                    $sqlImagen = "INSERT INTO equipo_imagen (equipo_id, imagen_id) VALUES ($equipo_id, $imagen_id)";
                    mysqli_query($con->getConnection(), $sqlImagen);
                }
            }
        } catch (Exception $e) {
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

    public function putNombreById($_nuevoObjeto, $_id) {
        $con = new Conexion();
        $tipo = mysqli_real_escape_string($con->getConnection(), $_nuevoObjeto->tipo);
        $texto = mysqli_real_escape_string($con->getConnection(), $_nuevoObjeto->texto);
        $sql = "UPDATE equipo SET tipo = '$tipo', texto = '$texto' WHERE id = $_id";
        $success = false;

        try {
            $success = mysqli_query($con->getConnection(), $sql);

            if (isset($_nuevoObjeto->imagenes) && is_array($_nuevoObjeto->imagenes)) {
                // First, delete old associations
                $sqlDelete = "DELETE FROM equipo_imagen WHERE equipo_id = $_id";
                mysqli_query($con->getConnection(), $sqlDelete);

                foreach ($_nuevoObjeto->imagenes as $imagen_id) {
                    $sqlImagen = "INSERT INTO equipo_imagen (equipo_id, imagen_id) VALUES ($_id, $imagen_id)";
                    mysqli_query($con->getConnection(), $sqlImagen);
                }
            }
        } catch (Exception $e) {
            $success = false;
        }

        $con->closeConnection();
        return $success;
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