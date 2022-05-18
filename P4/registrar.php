<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    include ("db.php");

    $acceso_bd = new AccesoBD();

    //Registrar es para la accion y registro para cargar el html
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $_POST;

        if (!$acceso_bd->existeUsuario($usuario['username'])) {
            if($acceso_bd->registrarUsuario($usuario)) 
            {
                session_start();
                $_SESSION['usuario'] = $acceso_bd->getUsuario($usuario['username']); //usuario es un vector aqui
                header("Location: ./index.php");
                exit();
            }
        }
    }

    if (!isset($_SESSION['error_registro'])) { 
        $_SESSION['error_registro'] = true;
    }

    $variablesParaTwig['error_registro'] = $_SESSION['error_registro'];

    echo $twig->render('registro.html', $variablesParaTwig); //renderizar html de registro
?>