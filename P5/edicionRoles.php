<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    include ("db.php");

    $variablesParaTwig = [];
    $acceso_bd = new AccesoBD();

    session_start(); //Para que en la cabecera aparezca quien es

    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' ))
    {

    $usuarios = $acceso_bd->getUsuarios();
    $roles = $acceso_bd->getRoles();

    $variablesParaTwig['usuarios'] = $usuarios;
    $variablesParaTwig['roles'] = $roles;

    $variablesParaTwig['user'] = $_SESSION['usuario'];

    echo $twig->render('edicion_roles.html', $variablesParaTwig);
    }
    else{
        echo $twig->render('error404.html');
    }
?>