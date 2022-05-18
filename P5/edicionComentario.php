<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    include ("db.php"); //Funciones que interactúan con la BD

    $acceso_bd = new AccesoBD();

    session_start();

    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'moderador'))
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $id_comentario = $_POST['id_comentario_editar'];
            $id_producto = $_POST['id_producto_editar'];

            $comentario = $acceso_bd->getComentario($id_producto, $id_comentario);
            $palabrotas = $acceso_bd->getPalabrotas();

            $variablesParaTwig['comentario'] = $comentario;
            $variablesParaTwig['palabrotas'] = $palabrotas;
            $variablesParaTwig['user'] = $_SESSION['usuario']; //User es el admin

            echo $twig->render('edicion_comentario.html', $variablesParaTwig); //renderizar html de edicion
        }
    }
    
    else{
        echo $twig->render('error404.html');
    }
?>