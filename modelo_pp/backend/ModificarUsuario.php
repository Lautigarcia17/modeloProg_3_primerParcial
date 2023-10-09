<?php

include_once "./clases/Usuario.php";

$objeto = isset($_POST["usuario_json"]) ? $_POST["usuario_json"] : "";

if ($objeto !="") {
    $objeto = json_decode($objeto);//json a objeto
    $usuario = new Usuario($objeto->correo,$objeto->nombre,$objeto->clave,(int)$objeto->id,(int)$objeto->id_perfil);
    if($usuario->Modificar() == true)
    {
        echo'{ "exito": "' . true . '", "mensaje": "Se borro el usuario "}' ; 
    }
    else 
    {
        echo  $respuesta = '{ "exito": "' . false . '", "mensaje": "No se borro el usuario "}' ; 
    }
}


?>