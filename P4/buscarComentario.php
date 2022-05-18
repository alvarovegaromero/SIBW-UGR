<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    include ("db.php"); //Funciones que interactúan con la BD

    session_start(); //Si le aparece esta opcion, es que ya habia iniciado sesion

    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'moderador'))
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $filtro = $_POST['comentario_buscar'];

            $acceso_bd = new AccesoBD();
            $productos = $acceso_bd->getProductos();
            $comentarios = $acceso_bd->buscarComentarios($filtro);

            $variablesParaTwig['user'] = $_SESSION['usuario'];
            $variablesParaTwig['productos'] = $productos;
            $variablesParaTwig['comentarios'] = $comentarios;

            echo $twig->render('listado_comentarios.html', $variablesParaTwig); //renderizar html de edicion
        }
    }

    else{
        echo $twig->render('error404.html');
    }
?>