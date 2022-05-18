<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);  
    
    include("db.php");

    session_start();

    if (isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor') )
    {
        $acceso_bd = new AccesoBD();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $nuevas_etiquetas = $_POST['etiquetas']; //
            $id_producto = $_POST['id_producto'];

            if($acceso_bd->editarEtiquetas($id_producto, $nuevas_etiquetas))
            {
                header("Location: producto.php?prod=$id_producto");
                exit();
            }
        }
    }
    else{
        echo $twig->render('error404.html');
    }
?>