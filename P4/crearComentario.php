<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include ("db.php"); //Funciones que interactúan con la BD

session_start();

if(isset($_SESSION['usuario']))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $acceso_bd = new AccesoBD();

        $id_prod = $_POST['id_prod'];
        $username = $_POST['username'];
        $opinion = $_POST['opinion'];
        $fecha = date('Y-m-d H:i:s', strtotime("+2 hours")); //coger hora actual en formato de sql
    
        if($acceso_bd->crearComentario($id_prod, $username, $opinion, $fecha)) {
            header("Location: ./producto.php?prod=$id_prod");
            exit();
        }
    }
}

else{
    echo $twig->render('error404.html');
}

?>