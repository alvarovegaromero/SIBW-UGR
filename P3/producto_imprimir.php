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

  $acceso_bd = new AccesoBD();
  $producto = $acceso_bd->getProducto($id_producto);
  $imagenes = $acceso_bd->getImagenes($id_producto);
  $comentarios = $acceso_bd->getComentarios($id_producto);

  echo $twig->render('producto.html', ['producto' => $producto, 'imagenes' => $imagenes, 'comentarios' => $comentarios, 'enlaces' => $enlaces]);

  $acceso_bd->cerrarConexion();

?>
