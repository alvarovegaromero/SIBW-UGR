<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start(); //Si le aparece esta opcion, es que ya habia iniciado sesion

    if(isset($_SESSION['usuario']))
    {
        $variablesParaTwig = [];

        $variablesParaTwig['user'] = $_SESSION['usuario'];

        echo $twig->render('edicion_perfil.html', $variablesParaTwig); //renderizar html de edicion
    }

    else{
        echo $twig->render('error404.html');
    }
?>