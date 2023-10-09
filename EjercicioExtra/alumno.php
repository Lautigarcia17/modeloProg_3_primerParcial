<?php

namespace Garcia
{
    class Alumno
    {
        private int $legajo;
        private string $nombre;
        private string $apellido;
        private string $foto;

        public function getLegajo() : int
        {
            return $this->legajo;
        }
        public function getNombre() : string
        {
            return $this->nombre;
        }
        public function getApellido() : string
        {
            return $this->apellido;
        }
        public function getFoto() : string
        {
            return $this->foto;
        }

        public function __construct(int $legajoIngresado, string $apellidoIngresado, string $nombreIngresado, string $fotoIngresada) {
            $this->legajo = $legajoIngresado;
            $this->apellido = $apellidoIngresado;
            $this->nombre = $nombreIngresado;

            $this->foto = Alumno::cambiarPath($this->legajo,$fotoIngresada);
        }

        public function ToString(): string
        {
            return  "$this->legajo - $this->apellido - $this->nombre - $this->foto\n";
        }

        public static function agregar(Alumno $alumno) : void
        {
            $archivo = fopen("./archivos/alumnos_foto.txt","a");

            $rutaGuardar = "./fotos/". $alumno->foto;

            move_uploaded_file($_FILES["foto"]["tmp_name"],$rutaGuardar); 

            $retorno = fwrite($archivo,$alumno->ToString());

            if ($retorno > 0) {
                echo "Se a guardado el alumno !";
            }else {
                echo "No se a podido guardar el alumno !";
            }
        }

        public static function listar() : void
        {   
            if (file_exists("./archivos/alumnos_foto.txt")) 
            {
                $ar = fopen("./archivos/alumnos_foto.txt","r");
                while(!feof($ar))
                {
                    echo fgets($ar);
                    echo "<br>";
                }
                fclose($ar);

            }else 
            {
                echo "No existe el archivo";
            }
        }

        public static function verificar(int $legajo) : bool
        {   
            if (file_exists("./archivos/alumnos.txt") && $legajo != 0) 
            {
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
                fclose($archivo);
            }
            else 
            {
                echo "Surgio un error";
            }
            return $esta;
        }

        public static function modificar(Alumno $alumno) : bool
        {   
            if (file_exists("./archivos/alumnos.txt") && $alumno->legajo != 0 && $alumno->nombre != null && $alumno->apellido !=null ) 
            {
                $archivo = fopen("./archivos/alumnos_foto.txt","r") ;
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
                        $fotoArchivo = trim($arrayString[3]);

                        if ($legajoArchivo == $alumno->legajo) 
                        {
                            $rutaGuardar = "./fotos/". $alumno->foto;
                            move_uploaded_file($_FILES["foto"]["tmp_name"],$rutaGuardar); 

                            array_push($elementos,"$legajoArchivo - $alumno->apellido - $alumno->nombre - $alumno->foto\r\n");
                            $esta = true;
                        }
                        else{
                            array_push($elementos,"$legajoArchivo - $apellidoArchivo - $nombreArchivo - $fotoArchivo\r\n");
                        }
                    }
                }

                fclose($archivo);
                $archivo = fopen("./archivos/alumnos.txt", "w");
                
                foreach($elementos AS $item){
                    
                    fwrite($archivo, $item);
                }
                fclose($archivo);
            }
            else {
                echo "Surgio un error";
            }
            return $esta;
        }

        public static function borrar(int $legajo) : bool
        {
            if (file_exists("./archivos/alumnos_foto.txt") && $legajo != 0) 
            {
                $archivo = fopen("./archivos/alumnos_foto.txt","r") ;
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

                fclose($archivo); 
                $archivo = fopen("./archivos/alumnos_foto.txt", "w");
                

                foreach($elementos AS $item){
                    
                    fwrite($archivo, $item);
                }
                fclose($archivo);
            }
            else {
                echo "Surgio un error";
            }
            
            return $esta;
        }

        public static function  cambiarPath(int $legajo,string $path) : string
        {
            return "$legajo." . pathinfo($path, PATHINFO_EXTENSION);
        }
        
        public static function obtenerAlumno($legajo) : Alumno | bool
        {
            if (file_exists("./archivos/alumnos_foto.txt") && $legajo != 0) 
            {
                $archivo = fopen("./archivos/alumnos_foto.txt","r");
                while (!feof($archivo)) {
                    $linea = fgets($archivo);
                    $arrayString = explode("-",$linea);
                    $arrayString[0] = trim($arrayString[0]);

                    if ($arrayString[0] != "") {
                        $legajoArchivo = $arrayString[0];
                        $apellidoArchivo = $arrayString[1];
                        $nombreArchivo = $arrayString[2];
                        $fotoArchivo = $arrayString[3];

                        if ($legajoArchivo == $legajo) {
                            return new Alumno((int)$legajoArchivo,$apellidoArchivo,$nombreArchivo,$fotoArchivo);
                        }
                    }

                }
            }
            return false;
        }

        public static function obtenerTodosLosAlumnos() : array
        {
            $arrayAlumnos = array();
            if (file_exists("./archivos/alumnos_foto.txt")) 
            {
                $archivo = fopen("./archivos/alumnos_foto.txt","r");
                while (!feof($archivo)) {
                    $linea = fgets($archivo);
                    $arrayString = explode("-",$linea);
                    $arrayString[0] = trim($arrayString[0]);

                    if ($arrayString[0] != "") {
                        $legajoArchivo = $arrayString[0];
                        $apellidoArchivo = $arrayString[1];
                        $nombreArchivo = $arrayString[2];
                        $fotoArchivo = $arrayString[3];

                        
                        $arrayAlumnos[] = new Alumno((int)$legajoArchivo,$apellidoArchivo,$nombreArchivo,$fotoArchivo);
                    }

                }
            }
            return $arrayAlumnos;
        }

    }

}



?>
