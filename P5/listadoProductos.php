<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    include ("db.php"); //Funciones que interactúan con la BD

    session_start(); //Si le aparece esta opcion, es que ya habia iniciado sesion

    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor'))
    {
        $acceso_bd = new AccesoBD();
        $productos = $acceso_bd->getProductos();

        $variablesParaTwig['user'] = $_SESSION['usuario'];
        $variablesParaTwig['productos'] = $productos;

        echo $twig->render('listado_productos.html', $variablesParaTwig); //renderizar html de edicion
    }

    else{
        echo $twig->render('error404.html');
    }
?>