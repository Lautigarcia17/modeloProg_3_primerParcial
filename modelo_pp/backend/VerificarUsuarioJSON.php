<?php

include "./clases/Usuario.php";

$usuario_json = isset($_POST["usuario_json"]) ? $_POST["usuario_json"] : "";


if (Usuario::TraerUno($usuario_json) == false) {
    echo  $respuesta = '{ "exito": "' . false . '", "mensaje": "No se encontro el usuario "}' ; 
}
else {
    echo'{ "exito": "' . true . '", "mensaje": "Se encontro el usuario "}' ; 
}

?>