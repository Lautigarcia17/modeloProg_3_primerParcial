<?php
include "./alumno.php";
use Garcia\Alumno;

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : null;
$legajo = isset($_POST["legajo"]) ? (int) $_POST["legajo"] : 0;
$pathFoto = $_FILES["foto"]["name"];

if (isset($_GET["accion"])) {
    $accion = $_GET["accion"];
} elseif (isset($_POST["accion"])) {
    $accion = $_POST["accion"];
} else {
    $accion = null; // Acción no definida
}

switch($accion)
{
    case "agregar":
        $alumno = new Alumno($legajo,$apellido,$nombre,$pathFoto);
        Alumno::agregar($alumno);
    break;
    case "listar" :
        Alumno::listar();
    break;
    case "verificar" :
        if (Alumno::verificar($legajo)) {
            echo "El alumno con legajo '$legajo' se encuentra en el listado";
        }else {
            echo "El alumno con legajo '$legajo' no se encuentra en el listado";
        }
    break;
    case "modificar" :
        $alumno = new Alumno($legajo,$apellido,$nombre,$pathFoto);
        if(Alumno::modificar($alumno))
        {
            echo "El alumno con legajo '$legajo' se ha modificado";
        }
        else {
            echo "El alumno con legajo '$legajo' no se encuentra en el listado";
        }
    break;
    case "borrar" :
        if (Alumno::borrar($legajo)) {
            echo "El alumno con legajo '$legajo' se ha borrado";
 
        }else {
            echo "El alumno con legajo '$legajo' no se encuentra en el listado";
        }
    break;
    default :
          echo "No existe el archivo";
    break;
}

?>