<?php
//Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Content-Type: application/json; charset=utf-8");

$_metodo = $_SERVER['REQUEST_METHOD'];
$_ubicacion = $_SERVER['HTTP_HOST'];
$_path = $_SERVER['REQUEST_URI'];
$_partes = explode('/', $_path);
$_version = $_ubicacion == 'localhost' ? $_partes[2] : null;
$_mantenedor = $_ubicacion == 'localhost' ? $_partes[3] : null;
$_parametros = [];
$_parametros = $_ubicacion == 'localhost' ? $_partes[4] : null;

if (strlen($_parametros)> 0){
    $_parametros = explode('?', $_parametros)[1];
    $_parametros = explode('&', $_parametros);
}else{
    $_parametros = [];
}

//Authorization
$_header = null;
try {
    $_header = isset(getallheaders()['Authorization']) ? getallheaders()['Authorization'] : null;
    if ($_header === null){
        throw new Exception("No tiene autorizacion");
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['Error' => $e->getMessage()]);
}

//Tokens
$_token_get = 'Bearer get_ciisa';
$_token_post = 'Bearer post_ciisa';
$_token_put = 'Bearer put_ciisa';
$_token_patch = 'Bearer patch_ciisa';
$_token_delete = 'Bearer delete_ciisa';