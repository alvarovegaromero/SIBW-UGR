<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
include ("db.php"); //Funciones que interactúan con la BD

$acceso_bd = new AccesoBD();

session_start();
if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor'))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_producto = $_POST['id_producto'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $marca = $_POST['marca'];
        $nombre_producto = $_POST['nombre_producto'];
        $url_foto_portada = $_FILES['foto_portada']['name'];
        $publicado = false;

        // Se ha marcado el checkbox publicado
        if ($_POST['publicado'] === 'publicado')
            $publicado = true;

        if($_FILES['foto_portada']['error'] === UPLOAD_ERR_NO_FILE) {
            $url_foto_portada = null;
        }
        else { // Si hay nueva portada. Se controla que se suba una foto desde una carpeta distinta
            $portada_tmp = $_FILES['foto_portada']['tmp_name'];
            $portada_name = $_FILES['foto_portada']['name'];
            $url_foto_portada = "/images/$portada_name";
            move_uploaded_file($portada_tmp, "." . $url_foto_portada);
        }

        if($acceso_bd->editarProducto($id_producto, $nombre_producto, $marca, $precio, $descripcion, $url_foto_portada, $publicado)) 
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