<?php
include_once '../version1.php';

//parametros
$existeId = false;
$valorId = 0;
$existeAccion = false;
$valorAccion = 0;


if (count($_parametros) > 0) {
    foreach ($_parametros as $p) {
        if (strpos($p, 'id') !== false) {
            $existeId = true;
            $valorId = explode('=', $p)[1];
        }
        if (strpos($p, 'accion') !== false) {
            $existeAccion = true;
            $valorAccion = explode('=', $p)[1];
        }
    }
}

if ($_version == 'Backend') {
    if ($_mantenedor == 'imagen') {
        switch ($_metodo) {
            case 'GET':
                if ($_header == $_token_get){
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $lista = $control->getAll();
                    http_response_code(200);
                    echo json_encode(["data" => $lista]);  // Se mostrara todo
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
                        "nombre": "Ejemplo de nombre",
                        "imagen": "url_de_la_imagen",       EJEMPLO DE LO QUE SE TIENE QUE PONER EN EL BODY RAW DE POSTMAN
                        "activo": true
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
                    if ($existeId && $existeAccion) {
                        if ($valorAccion == 'apagar') {
                            $respuesta = $control->patchEncenderApagar($valorId, 'false');
                            // echo "patch... $valorId - $valorAccion";
                            http_response_code(200);
                            echo json_encode(['data' => $respuesta]);
                        } else if ($valorAccion == 'encender') {
                            $respuesta = $control->patchEncenderApagar($valorId, 'true');
                            http_response_code(200);
                            echo json_encode(['data' => $respuesta]);
                        } else {
                            echo 'error con acciones';
                        }
                    } else {
                        echo 'faltan parametros';
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion patch']);
                }
                break;
            case 'PUT':
                if ($_header == $_token_put) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $body = json_decode(file_get_contents("php://input", true));
                    // var_dump($body);
                    $control = new Controlador();
                    if (isset($body->nombre) && strlen($body->nombre) > 0) {
                        $respuesta = $control->putNombreById($body->nombre, $body->id);
                    }
                    if (isset($body->imagen) && strlen($body->imagen) > 0) {
                        $respuesta = $control->putImagenById($body->imagen, $body->id);
                    }
                    http_response_code(200);
                    echo json_encode(['data' => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion put']);
                }
                break;
            case 'DELETE':
                if ($_header == $_token_delete) {
                    include_once 'controller.php';
                    include_once '../conexion.php';
                    $control = new Controlador();
                    $respuesta = $control->deleteById($valorId);
                    http_response_code(200);
                    echo json_encode(['data' => $respuesta]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'no tiene autorizacion delete']);
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