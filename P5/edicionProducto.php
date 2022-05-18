<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    include ("db.php"); //Funciones que interactúan con la BD

    $acceso_bd = new AccesoBD();

    session_start();

    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor'))
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $id_producto = $_POST['id_producto_editar'];

            $producto = $acceso_bd->getProducto($id_producto);
            $enlaces = $acceso_bd->getEnlaces($id_producto);
            $imagenes = $acceso_bd->getImagenes($id_producto);

            $etiquetas_aux = $producto['etiquetas'];    // obtener el campo etiquetas del producto
            $etiquetas = explode(', ', $etiquetas_aux); // convertir string separado por comas a array

            $variablesParaTwig['producto'] = $producto;
            $variablesParaTwig['imagenes'] = $imagenes;
            $variablesParaTwig['enlaces'] = $enlaces;
            $variablesParaTwig['etiquetas'] = $etiquetas;

            $variablesParaTwig['user'] = $_SESSION['usuario']; //User es el admin

            echo $twig->render('edicion_producto.html', $variablesParaTwig); //renderizar html de edicion
        }
    }
    
    else{
        echo $twig->render('error404.html');
    }
?>