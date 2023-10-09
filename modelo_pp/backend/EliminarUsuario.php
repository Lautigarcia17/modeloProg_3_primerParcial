<?php

include_once "./clases/Usuario.php";

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if ($id !="") {

    if(Usuario::Eliminar($id) == true)
    {
        echo'{ "exito": "' . true . '", "mensaje": "Se elimino el usuario "}' ; 
    }
    else 
    {
        echo  $respuesta = '{ "exito": "' . false . '", "mensaje": "No se elimino el usuario "}' ; 
    }
}


?>