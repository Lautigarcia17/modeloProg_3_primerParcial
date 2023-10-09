<?php

include "./clases/Usuario.php";

$correo = isset($_POST["correo"]) ? $_POST["correo"] : "";
$clave = isset($_POST["clave"]) ? $_POST["clave"] : "";
$nombre = isset($_POST["nombre"]) ?  $_POST["nombre"] : "";
$id_perfil = isset($_POST["id_perfil"]) ?  (int)$_POST["id_perfil"] : 0;


$usuario = new Usuario($nombre,$correo,$clave,99,$id_perfil);
if ($usuario->Agregar()) 
{
    echo'{ "exito": "' . true . '", "mensaje": "Se agrego el usuario "}' ; 
}
else 
{
    echo  $respuesta = '{ "exito": "' . false . '", "mensaje": "No se agrego el usuario "}' ; 
}

?>
