<?php
include "./clases/Usuario.php";

$correo = isset($_POST["correo"]) ? $_POST["correo"] : "";
$clave = isset($_POST["clave"]) ? $_POST["clave"] : "";
$nombre = isset($_POST["nombre"]) ?  $_POST["nombre"] : "";

$usuario = new Usuario($nombre,$correo,$clave);



echo $usuario->GuardarEnArchivo();



?>