<?php
include_once '../version1.php';

if ($_version == 'Backend') {
    if ($_mantenedor == 'equipo') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get){
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $lista = $control->getAll();
                    http_response_code(200);
                    echo json_encode(["data" => $lista]);  // Retorna todos los equipos
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorización GET"]);
                }
                break;
            case 'POST':
                if ($_header == $_token_post) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input"));
                    /*{
                        "tipo": "Ejemplo de tipo",               EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN  
                        "texto": "Ejemplo de texto",
                        "imagenes": [1, 2, 3] // IDs de imágenes relacionadas (opcional)
                    }*/                  
                    $respuesta  = $control->postNuevo($body);
                    if ($respuesta) {
                        http_response_code(201);
                        echo json_encode(["data" => $respuesta]);
                    } else {
                        http_response_code(409);
                        echo json_encode(["data" => "error: conflicto con el nombre ingresado, ya existe"]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorización POST"]);
                }
                break;
            case 'PATCH':
                if ($_header == $_token_patch) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input"));
                    /*{
                        "id": 1,
                        "accion": "encender" o "apagar"             EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN  
                    }*/       
                    if (isset($body->id) && isset($body->accion)) {
                        $valorId = $body->id;
                        $valorAccion = $body->accion;

                        if ($valorAccion == 'encender' || $valorAccion == 'apagar') {
                            $respuesta = $control->patchEncenderApagar($valorId, $valorAccion);
                            if ($respuesta !== null) {
                                http_response_code(200);
                                echo json_encode(["data" => $respuesta]);  // Si el resultado es data: true se realizo correctamente
                            } else {
                                http_response_code(500);
                                echo json_encode(["Error" => "Error al actualizar el estado."]);
                            }
                        } else {
                            http_response_code(400);
                            echo json_encode(["Error" => "Acción no válida"]);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(["Error" => "Faltan parámetros"]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorización PATCH"]);
                }
                break;
            case 'PUT':
                if ($_header == $_token_put) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $body = json_decode(file_get_contents("php://input"));
                    /*{
                        "id": 1,
                        "tipo": "Nuevo tipo",            EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN  
                        "texto": "Nuevo texto",
                        "imagenes": [4, 5, 6] // IDs de imágenes relacionadas (opcional)
                    }*/                  
                    $respuesta = $control->putNombreById($body, $body->id);
                    http_response_code(200);
                    echo json_encode(["data" => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorización PUT"]);
                }
                break;
            case 'DELETE':
                if ($_header == $_token_delete) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    // Cambiar $valorId por la ID que desea eliminar
                    $respuesta = $control->deleteById($valorId);
                    if ($respuesta) {
                        http_response_code(200);
                        echo json_encode(["data" => "Elemento eliminado correctamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["Error" => "El elemento no se encontró"]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["Error" => "No tiene autorización DELETE"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["Error" => "Método no permitido"]);
                break;
        }
    }
}
?>