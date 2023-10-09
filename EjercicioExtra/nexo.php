<?php

$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : null;
$legajo = isset($_POST["legajo"]) ? (int) $_POST["legajo"] : 0;

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
        
        $ar = fopen("./archivos/alumnos.txt","a");

        $retorno = fwrite($ar,"$legajo - $apellido - $nombre\r\n");

        if ($retorno > 0) {
            echo "Se a guardado el alumno !";
        }else {
            echo "No se a podido guardar el alumno !";
        }

        fclose($ar);

    break;
    case "listar" :
        if (file_exists("./archivos/alumnos.txt")) {
            $ar = fopen("./archivos/alumnos.txt","r");
            while(!feof($ar))
            {
                echo fgets($ar);
            }
            fclose($ar);

        }else {
            echo "No existe el archivo";
        }
    break;
    case "verificar" :
        if (file_exists("./archivos/alumnos.txt") && $legajo != 0) {
            $archivo = fopen("./archivos/alumnos.txt","r") ;
            $esta = false;

            while(!feof($archivo))
            {
                $linea = fgets($archivo);
                $arrayString = explode("-",$linea);
                $arrayString[0] = trim($arrayString[0]);
                
                if ($arrayString[0] !="" && $arrayString[0] == $legajo) {
                    $esta = true;
                    break;
                }
            }
             echo $esta == true ? "El alumno con legajo '$legajo' se encuentra en el listado" : "El alumno con legajo '$legajo' no se encuentra en el listado"; 
           
            fclose($archivo);
        }
        else {
            echo "Surgio un error";
        }
    break;
    case "modificar" :
        if (file_exists("./archivos/alumnos.txt") && $legajo != 0 && $nombre != null && $apellido !=null) {
            $archivo = fopen("./archivos/alumnos.txt","r") ;
            $elementos = array();
            $esta = false;

            while(!feof($archivo))
            {
                $linea = fgets($archivo);
                $arrayString = explode("-",$linea);
                $arrayString[0] = trim($arrayString[0]);
                
                if ($arrayString[0] !="") 
                {
                    $legajoArchivo = $arrayString[0];
                    $apellidoArchivo = trim($arrayString[1]);
                    $nombreArchivo = trim($arrayString[2]);

                    if ($legajoArchivo == $legajo) {
                        array_push($elementos,"$legajoArchivo - $apellido - $nombre\r\n");
                        $esta = true;
                    }
                    else{
                        array_push($elementos,"$legajoArchivo - $apellidoArchivo - $nombreArchivo\r\n");
                    }
                }
            }
            echo $esta == true ? "El alumno con legajo '$legajo' se ha modificado" : "El alumno con legajo '$legajo' no se encuentra en el listado"; 

            fclose($archivo);
            
            $archivo = fopen("./archivos/alumnos.txt", "w");
            
            //ESCRIBO EN EL ARCHIVO
            foreach($elementos AS $item){
                
                fwrite($archivo, $item);
            }
            fclose($archivo);
        }
        else {
            echo "Surgio un error";
        }
    break;
    case "borrar" :
        if (file_exists("./archivos/alumnos.txt") && $legajo != 0) {
            $archivo = fopen("./archivos/alumnos.txt","r") ;
            $elementos = array();
            $esta = false;

            while(!feof($archivo))
            {
                $linea = fgets($archivo);
                $arrayString = explode("-",$linea);
                $arrayString[0] = trim($arrayString[0]);
                
                if ($arrayString[0] !="") 
                {
                    $legajoArchivo = $arrayString[0];

                    if ($legajoArchivo == $legajo) {
                        $esta = true;
                        continue;
                    }   
                    array_push($elementos,"$linea");     
                }
            }
            echo $esta == true ? "El alumno con legajo '$legajo' se ha borrado" : "El alumno con legajo '$legajo' no se encuentra en el listado"; 

            fclose($archivo);
            
            $archivo = fopen("./archivos/alumnos.txt", "w");
            
            //ESCRIBO EN EL ARCHIVO
            foreach($elementos AS $item){
                
                fwrite($archivo, $item);
            }
            fclose($archivo);
        }
        else {
            echo "Surgio un error";
        }
    break;
    default :
          echo "No existe el archivo";
    break;
}





?>
