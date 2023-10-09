<?php
include "./alumno.php";
use Garcia\Alumno;

$id = isset($_POST["id"]) ? $_POST["id"] : null;
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

try {
    $pdo = new PDO("mysql:host=localhost;dbname=alumno_pdo","root","");
    
    switch ($accion) {
        case "agregar_bd":

            $path= Alumno::cambiarPath($legajo,$pathFoto);
            $rutaGuardar = "./fotos/". $path;
            move_uploaded_file($_FILES["foto"]["tmp_name"],$rutaGuardar); 


            $sql = $pdo->prepare("INSERT INTO `alumnos`(`legajo`, `nombre`, `apellido` , `foto`) VALUES (:legajo,:nombre,:apellido,:foto)");
            $sql->bindParam(':legajo',$legajo,PDO::PARAM_INT);
            $sql->bindParam(':nombre',$nombre,PDO::PARAM_STR,20);
            $sql->bindParam(':apellido',$apellido,PDO::PARAM_STR,20);
            $sql->bindParam(':foto',$path,PDO::PARAM_STR,50);

            $sql->execute();
            break;
        case "listar_bd":
            $sql = $pdo->query("SELECT `legajo`, `apellido`, `nombre`, `foto` FROM `alumnos`");
      
            if ($sql != false) 
            {
                // var_dump($sql->fetchAll(PDO::FETCH_OBJ));
                $resultado = $sql->fetchAll();
                foreach ($resultado as $value) {
                    echo $value[0] . " - " . $value[1] . " - " . $value[2] . " - " . $value[3] ."<br>";
                }
            }
        break;
        case "borrar_bd":
            $sql = $pdo->prepare("DELETE FROM `alumnos` WHERE id = :id");
            $sql->bindParam(':id',$id,PDO::PARAM_INT);
            $sql->execute();
            break;
        case "modificar_bd":
            $path= Alumno::cambiarPath($legajo,$pathFoto);

            $sql = $pdo->prepare("UPDATE `alumnos` SET `id`= :id,`legajo`= :legajo,`nombre`=:nombre,`apellido`=:apellido,`foto`=:pathFoto WHERE id = :id"); // retonar false o retorna un PDOStatement
            
            $sql->bindParam(':id',$id,PDO::PARAM_INT);
            $sql->bindParam(':legajo',$legajo,PDO::PARAM_INT);
            $sql->bindParam(':nombre',$nombre,PDO::PARAM_STR,20);
            $sql->bindParam(':apellido',$apellido,PDO::PARAM_STR,20);
            $sql->bindParam(':pathFoto',$path,PDO::PARAM_STR);
            
            $sql->execute();

            break;
        default:
            echo "Accion no valida";
            break;
    }

} catch (PDOException $e) {
    echo "Error = ". $e->getMessage();
} catch(Exception)
{
    echo "Surgio un error";
}






























?>
