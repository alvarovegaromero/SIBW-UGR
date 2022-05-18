<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include ("db.php"); //Funciones que interactúan con la BD

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $variablesParaTwig = [];

  if (isset($_GET['e'])) { //Si está definida la variable get para error
    $error_login = true;
  } else {
    $error_login = false;
  }

  $acceso_bd = new AccesoBD();
  $productos = $acceso_bd->getProductos();

  $variablesParaTwig['productos'] = $productos;
  $variablesParaTwig['error'] = $error_login;

  session_start(); //reanuda sesion

  if(isset($_SESSION['usuario']))
  {
    $variablesParaTwig['user'] = $_SESSION['usuario'];
  }

  echo $twig->render('portada.html', $variablesParaTwig);
?>
