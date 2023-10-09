<?php


// include_once "C:/xampp/htdocs/prog_3/modelo_pp/backend/clases/Usuario.php";

class Usuario implements IBM
{
    public int $id;
    public string $nombre;
    public string $correo;
    public string $clave;
    public int $id_perfil;
    public string $perfil;

    public function __construct($nombreIngresado,$correoIngresado,$claveIngresada,$idIngresado=0,$id_perfilIngresado=0,$perfilIngresado="") {
        $this->id = $idIngresado;
        $this->nombre = $nombreIngresado;
        $this->correo = $correoIngresado;
        $this->clave = $claveIngresada;
        $this->id_perfil = $id_perfilIngresado;
        $this->perfil = $perfilIngresado;
    }


    public function ToJSON() : string
    {
        return '{ "nombre": "' . $this->nombre . '", "correo": "' . $this->correo . '", "clave": "' . $this->clave . '"}'; 
    }

    public function GuardarEnArchivo() : string
    {
        $respuesta = "";

        $archivo = fopen( "./archivos/usuarios.json","a"); 
        $retorno = fwrite($archivo,$this->ToJSON()."\n");
        if ($retorno) {
            $respuesta = '{ "exito": "' . true . '", "mensaje": "Se cargo el usuario "}' ; 
        }else {
            $respuesta = '{ "exito": "' . false . '", "mensaje": "No Se cargo el usuario "}' ; 
        }

        fclose($archivo);

        return $respuesta;
    }

    public static function TraerTodosJSON() : array
    {     
        if (file_exists("./backend/archivos/usuarios.json")) 
        {
            $arrayUsuarios = array();
            $archivo = fopen("./backend/archivos/usuarios.json","r");
            while(!feof($archivo))
            {
                $linea = fgets($archivo);
                $linea = trim($linea);

                if ($linea !="") 
                {
                    $objeto = json_decode($linea); //json a objeto
                    array_push($arrayUsuarios,new Usuario($objeto->nombre,$objeto->correo,$objeto->clave));
                }
            }
            fclose($archivo);

        }else 
        {
            echo "No existe el archivo";
        }
        return $arrayUsuarios;  
    }

    public function Agregar() : bool
    {
        $rtn = false;
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=usuarios_test","root","");
            $sql = $pdo->prepare("INSERT INTO `usuarios`(`id`, `nombre`,`correo`, `clave`,`id_perfil`) VALUES (:id,:nombre,:correo,:clave,:id_perfil)"); // retonar false o retorna un PDOStatement
            $sql->bindParam(':id',$this->id,PDO::PARAM_INT);
            $sql->bindParam(':nombre',$this->nombre,PDO::PARAM_STR,30);
            $sql->bindParam(':correo',$this->correo,PDO::PARAM_STR,50);
            $sql->bindParam(':clave',$this->clave,PDO::PARAM_STR,8);
            $sql->bindParam(':id_perfil',$this->id_perfil,PDO::PARAM_INT);
            $sql->execute();
            $rtn=true;
            
        } catch (PDOException $e) {
            echo " Error = ". $e->getMessage();
        }
        return $rtn;
    }

    public static function TraerTodos() : array
    {
        try 
        {
            $arrayUsuarios = array();
            $pdo = new PDO("mysql:host=localhost;dbname=usuarios_test","root","");
            $sql = $pdo->query("SELECT * FROM usuarios INNER JOIN perfiles ON
                                usuarios.id_perfil = perfiles.id"); // union de 2 tablas

            if($sql !=false)
            {
                $resultado = $sql->fetchAll();
                foreach ($resultado as $value) {
                    array_push($arrayUsuarios,new Usuario($value["nombre"],$value["correo"], 
                                                          $value["clave"],$value["id"],
                                                          $value["id_perfil"],$value["descripcion"]));
                }
            } 

        } catch (PDOException $e) {
            echo " Error = ". $e->getMessage();
        }
        return $arrayUsuarios;
    }

    public static function TraerUno($params) : Usuario  | bool
    {
        try 
        {
            $objeto = json_decode($params);//json a objeto
            $usuario = false;
            $pdo = new PDO("mysql:host=localhost;dbname=usuarios_test","root","");
            $sql = $pdo->prepare("SELECT * FROM usuarios INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id WHERE correo = :correo AND clave = :clave"); // union de 2 tablas

            $sql->bindParam(':correo',$objeto->correo,PDO::PARAM_STR,50);
            $sql->bindParam(':clave',$objeto->clave,PDO::PARAM_STR,8);
            $sql->execute();

            if($sql !=false)
            {
                $filaUsuario = $sql->fetch();
                if ($filaUsuario !=false) {
                    $usuario = new Usuario($filaUsuario["nombre"],$filaUsuario["correo"], 
                    $filaUsuario["clave"],$filaUsuario["id"],
                    $filaUsuario["id_perfil"],$filaUsuario["descripcion"]);
                }      
            } 

        } catch (PDOException $e) {
            echo " Error = ". $e->getMessage();
        }

        return $usuario;
    }


    public function Modificar() : bool
    {
        try {
            $retorno = false;
            $pdo = new PDO("mysql:host=localhost;dbname=usuarios_test","root","");
            $sql = $pdo->prepare("UPDATE `usuarios` SET `nombre`= :nombre,`correo`=:correo,`clave`= :clave,`id_perfil`=:id_perfil WHERE id = :id"); // retonar false o retorna un PDOStatement
            
            $sql->bindParam(':id',$this->id,PDO::PARAM_INT);
            $sql->bindParam(':nombre',$this->nombre,PDO::PARAM_STR,30);
            $sql->bindParam(':correo',$this->correo,PDO::PARAM_STR,50);
            $sql->bindParam(':clave',$this->clave,PDO::PARAM_STR,8);
            $sql->bindParam(':id_perfil',$this->id_perfil,PDO::PARAM_INT);
            
            $sql->execute();
            $retorno = true;

            
        } catch (PDOException $e) {
            echo " Error = ". $e->getMessage();
        }

        return $retorno;
    }

    public static function Eliminar($id) : bool
    {
        $rtn = false;
        try {

            $pdo = new PDO("mysql:host=localhost;dbname=usuarios_test","root","");
            $sql = $pdo->prepare("DELETE FROM `usuarios` WHERE id = :id");    
            $sql->bindParam(':id',$id,PDO::PARAM_INT);
            $sql->execute();
            $rtn = true;
            
        } catch (PDOException $e) {
            echo " Error = ". $e->getMessage();
        }

        return $rtn;  
    }

}


?>