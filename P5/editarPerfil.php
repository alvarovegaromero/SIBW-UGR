<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include ("db.php");

$acceso_bd = new AccesoBD();

session_start();
if(isset($_SESSION['usuario']))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $usuario = $_POST; //Usuario es los datos enviados por el formulario

        //Si el nuevo nombre de usuario no existe, o era el mismo que teniamos antes
        if (!$acceso_bd->existeUsuario($usuario['username']) or $_SESSION['usuario']['username'] === $usuario['username']) 
        {
            if($acceso_bd->actualizarUsuario($usuario, $_SESSION['usuario']['username'])) 
            {
                $_SESSION['usuario'] = $acceso_bd->getUsuario($usuario['username']);//Cambiar sesion
                header("Location: ./perfil.php");
                exit();
            }
        }
        header("Location: /registro.php");
    }
}
else{
    echo $twig->render('error404.html');
}
?>