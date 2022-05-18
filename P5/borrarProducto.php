<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include ("db.php"); //Funciones que interactúan con la BD

$acceso_bd = new AccesoBD();

session_start();

if (isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor') )
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $id_producto = $_POST['id_producto_borrar'];
        if ($acceso_bd->borrarProducto($id_producto)) 
        {
            header("Location: ./listadoProductos.php");
            exit();
        }
    }
}
else{
    echo $twig->render('error404.html');
}

?>