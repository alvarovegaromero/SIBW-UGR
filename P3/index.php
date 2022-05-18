<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include ("db.php"); //Funciones que interactúan con la BD

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $acceso_bd = new AccesoBD();
  $productos = $acceso_bd->getProductos();

  echo $twig->render('portada.html', ['productos' => $productos]);

  $acceso_bd->cerrarConexion();
?>
