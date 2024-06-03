<?php
include_once '../version1.php';

if ($_version == 'Backend') {
    if ($_mantenedor == 'historia') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $lista = $control->getAll();
                    http_response_code(200);
                    echo json_encode(["data" => $lista]);  // Se mostrará todo
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
                        "tipo": "Ejemplo de tipo",           EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN  
                        "texto": "Ejemplo de texto",
                        "imagenes": [1, 2, 3]  // IDs de imágenes relacionadas (opcional)
                    }*/                  
                    $respuesta  = $control->postNuevo($body);
                    if ($respuesta) {
                        http_response_code(201);
                        echo json_encode(["data" => $respuesta]);
                    } else {
                        http_response_code(409);
                        echo json_encode(["Error" => "Error al crear la historia"]);
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
                        "accion": "encender" o "apagar"     EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN  
                    }*/
        
                    // Verificar si los datos requeridos están presentes en el cuerpo de la solicitud
                    if (isset($body->id) && isset($body->accion)) {
                        // Obtener los valores del cuerpo de la solicitud
                        $valorId = $body->id;
                        $valorAccion = $body->accion;

                        // Validar la acción
                        if ($valorAccion == 'encender' || $valorAccion == 'apagar') {
                            $respuesta = $control->patchEncenderApagar($valorId, $valorAccion);
                            if ($respuesta !== null) {
                                http_response_code(200);
                                echo json_encode(["data" => $respuesta]);  // Si el resultado es data: true se realizó correctamente
                            } else {
                                // En caso de error en la actualización
                                http_response_code(500);
                                echo json_encode(["Error" => "Error al actualizar el estado"]);
                            }
                        } else {
                            // Acción no válida
                            http_response_code(400);
                            echo json_encode(["Error" => "Acción no válida"]);
                        }
                    } else {
                        // Faltan parámetros
                        http_response_code(400);
                        echo json_encode(["Error" => "Faltan parámetros"]);
                    }
                } else {
                    // Autorización fallida
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
                        "tipo": "Nuevo tipo",
                        "texto": "Nuevo texto",         EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN  
                        "imagenes": [4, 5] // IDs de imágenes relacionadas (opcional)
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
                    $body = json_decode(file_get_contents("php://input"));
                    $respuesta = $control->deleteById($body->id);
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