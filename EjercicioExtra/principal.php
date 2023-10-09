<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once "./alumno.php";
    use Garcia\Alumno;

        session_start();

        if (isset($_SESSION["legajo"]) && isset($_SESSION["nombre"]) && isset($_SESSION["apellido"]) && isset($_SESSION["foto"])) {
            echo "<h1>" . $_SESSION["legajo"] . "</h1>" .
                 "<h2>" . $_SESSION["nombre"] . " " . $_SESSION["apellido"] . "</h2>" .
                 "<img src=" ."./fotos/" .$_SESSION["foto"] . " ' width='400px' height='300px' <br><br>"; 
            echo "<table border='1'>". Alumno::listar() ."</table>";
        }
        else
        {
            header("Location: ./nexo_poo_foto.php");
        }


    
    ?>
</body>
</html>