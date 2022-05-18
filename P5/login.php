<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include ("db.php"); //Funciones que interactúan con la BD

$acceso_bd = new AccesoBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    if ($acceso_bd->checkLogin($usuario, $contraseña)) { //si ha iniciado sesion
        session_start();
        $_SESSION['usuario'] = $acceso_bd->getUsuario($usuario);
        header("Location: index.php");
        exit();
    }
}
    $productos = $acceso_bd->getProductos(); #cargar la portada de nuevo
    
    if (!isset($_SESSION['error_login'])) { 
        $_SESSION['error_login'] = true;
    }
    
    $variablesParaTwig['productos'] = $productos;
    $variablesParaTwig['error'] = $_SESSION['error_login'];

    echo $twig->render('portada.html', $variablesParaTwig);

?>