<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();

    if(isset($_SESSION['usuario']))
    {
        $variablesParaTwig = [];

        $variablesParaTwig['user'] = $_SESSION['usuario'];

        echo $twig->render('perfil.html', $variablesParaTwig); //renderizar html de edicion
    }
    
    else{
        echo $twig->render('error404.html');
    }
?>