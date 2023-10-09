<?php
include "./alumno.php";
require_once __DIR__ . '/vendor/autoload.php';
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
    case "listar":
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
    case "obtener" :
        $alumno = Alumno::obtenerAlumno($legajo);
        if ($alumno == false) {
            echo "No se encontro el alumno con el legajo '$legajo'";
        }else{
            var_dump($alumno);
        }
    break;
    case "redirigir" :
        $alumno = Alumno::obtenerAlumno($legajo);
        if ($alumno == false) {
            echo "El alumno con legajo '$legajo' no se encuentra en el listado";
        }else{

            session_start();
            session_unset();
            $_SESSION["legajo"] = $alumno->getLegajo();
            $_SESSION["nombre"] = $alumno->getNombre();
            $_SESSION["apellido"] = $alumno->getApellido();
            $_SESSION["foto"] = $alumno->getFoto();

            header("Location: ./principal.php");

        }
    break;
    case "listar_pdf":
        $alumno = Alumno::obtenerAlumno($legajo);
        if ($alumno !=false) {
            header('content-type:application/pdf'); // indico que el tipo de archivo que voy a usar es de tipo pdf   -----> siempre tiene que dar salida esto, sino genera error, por eso va primero la validacion

            $mpdf = new \Mpdf\mpdf(['orientation' => 'P', 
                                    'pagenumPrefix' => 'Página nro. ',
                                    'pagenumSuffix' => ' - ',
                                    'nbpgPrefix' => ' de ',
                                    'nbpgSuffix' => ' páginas']);
            
            $nombreCompleto = $alumno->getApellido() .' '. $alumno->getNombre();

            $mpdf->SetHeader($nombreCompleto.'||{PAGENO}{nbpg}');
            $mpdf->SetFooter('{DATE Y}|Programacón III|{PAGENO}');
            
            $alumnos = Alumno::obtenerTodosLosAlumnos();
           

            $tabla = '<table border="1">' . "<br>";
            $tabla.= '<caption>Palabras</caption>';
            $tabla.= "<tr>
                    <th>Legajo</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Foto</th>
                    </tr>" . "<br>";
            foreach ($alumnos as $alumnoRecorrer) {
                if ($alumnoRecorrer instanceof Alumno) 
                {
                    $tabla.="<tr>
                                <td> ". $alumnoRecorrer->getLegajo() . "</td>".
                                "<td>".$alumnoRecorrer->getNombre() . "</td>".
                                "<td>".$alumnoRecorrer->getApellido() . "</td> ".
                                "<td><img src='./fotos/".$alumnoRecorrer->getFoto() . "' width='100px' height='100px'></td>" .
                            "</tr>";
                }
            }
            $tabla.= '</table>';
            $mpdf->SetProtection(array(),$alumno->getLegajo());
            $mpdf->WriteHTML($tabla);
            $mpdf->Output();  
        }else {
            echo "Legajo no encontrado";
        }
    break;
    default :
          echo "No existe el archivo";
    break;
}

?>

