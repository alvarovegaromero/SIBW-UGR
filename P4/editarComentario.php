<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
include ("db.php"); //Funciones que interactúan con la BD

$acceso_bd = new AccesoBD();

session_start(); //Para que en la cabecera aparezca quien es
if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'moderador'))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_producto = $_POST['id_producto'];
        $id_comentario = $_POST['id_comentario'];
        $opinion = $_POST['opinion'];
        $id_mod = $_SESSION['usuario']['id_usuario']; //User es el admin

        if($acceso_bd->editarComentario($id_producto, $id_comentario, $opinion, $id_mod)) 
        {
            header("Location: ./producto.php?prod=$id_producto");
            exit();
        }
    }
}
else{
    echo $twig->render('error404.html');
}
?>