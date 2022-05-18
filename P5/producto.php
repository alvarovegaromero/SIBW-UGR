<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include ("db.php"); //Funciones que interactúan con la BD

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  if (isset($_GET['prod'])) { //Si está definida la variable get
    $id_producto = $_GET['prod'];
  } else {
    $id_producto = -1;
  }

  $variablesParaTwig = [];

  $acceso_bd = new AccesoBD();
  $producto = $acceso_bd->getProducto($id_producto);
  $imagenes = $acceso_bd->getImagenes($id_producto);
  $comentarios = $acceso_bd->getComentarios($id_producto);
  $enlaces = $acceso_bd->getEnlaces($id_producto);
  $palabrotas = $acceso_bd->getPalabrotas($id_producto);

  $etiquetas_aux = $producto['etiquetas'];    // obtener el campo etiquetas del producto
  $etiquetas = explode(', ', $etiquetas_aux); // convertir string separado por comas a array

  $variablesParaTwig['producto'] = $producto;
  $variablesParaTwig['imagenes'] = $imagenes;
  $variablesParaTwig['comentarios'] = $comentarios;
  $variablesParaTwig['enlaces'] = $enlaces;
  $variablesParaTwig['palabrotas'] = $palabrotas;
  $variablesParaTwig['etiquetas'] = $etiquetas;

  session_start(); //reanuda sesion

  if(isset($_SESSION['usuario']))
  {
    $variablesParaTwig['user'] = $_SESSION['usuario'];
  }

  echo $twig->render('producto.html', $variablesParaTwig);
?>
