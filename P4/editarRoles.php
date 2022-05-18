<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    include ("db.php");

    $variablesParaTwig = [];
    $acceso_bd = new AccesoBD();

    session_start(); //Para que en la cabecera aparezca quien es
    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario'))
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $descomponer = json_decode($_POST['usuario_rol'], true); // Descomponer la cadena JSON asociativa (true indica que es asociativo)
            $username = $descomponer['username'];
            $rol = $descomponer['rol'];

            if ($acceso_bd->actualizarRol($username, $rol))
            {
                header("Location: ./edicionRoles.php");
                exit();
            }
        }
        header("Location: ./edicionRoles.php");
    }
    else{
        echo $twig->render('error404.html');
    }

?>