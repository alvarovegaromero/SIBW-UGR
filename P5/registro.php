<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $variablesParaTwig = [];

    echo $twig->render('registro.html', $variablesParaTwig); //renderizar html de registro
?>