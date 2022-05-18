<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include ("db.php"); //Funciones que interactúan con la BD

session_start();

if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor'))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $acceso_bd = new AccesoBD();

        $nombre_producto = $_POST['nombre_producto'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $marca = $_POST['marca'];
        $url_foto_portada = $_FILES['foto_portada']['name'];

        //Mover la foto de la portada a la carpeta
        $portada_tmp = $_FILES['foto_portada']['tmp_name'];
        $portada_name = $_FILES['foto_portada']['name'];
        $url_foto_portada = "/images/$portada_name";
        move_uploaded_file($portada_tmp, "." . $url_foto_portada);

        if($acceso_bd->crearProducto($nombre_producto, $marca, $precio, $descripcion, $url_foto_portada)) //Si fue bien
        {
            // Si no se cambian las imágenes
            if($_FILES['fotos_insertar']['error'][0] === UPLOAD_ERR_NO_FILE) {
                $url_imagenes = null;
            }
            else { // Si hay nuevas imágenes
                $imagenes_tmp = $_FILES['fotos_insertar']['tmp_name'];
                $imagenes_name = $_FILES['fotos_insertar']['name'];
                
                $id_producto = $acceso_bd->getProductoPorDatos($nombre_producto, $marca, $precio);
                
                for ($i = 0; $i < sizeof($imagenes_name); $i++) {
                    $max_id_imagen = $acceso_bd->getMaxIdImagen();

                    $url_imagenes[$i] = "/images/$max_id_imagen"."$imagenes_name[$i]";
                    $acceso_bd->aniadirImagen($id_producto, $url_imagenes[$i]);
                }
                for ($i = 0; $i < sizeof($imagenes_name); $i++) {
                    move_uploaded_file($imagenes_tmp[$i], "." . $url_imagenes[$i]);
                }
            }

            $texto_enlace = $_POST['texto_enlace'];
            $url_enlace = $_POST['url_enlace'];

            $nuevas_etiquetas = $_POST['etiquetas'];

            if ($acceso_bd->crearEnlace($id_producto, $texto_enlace, $url_enlace) and ($acceso_bd->editarEtiquetas($id_producto, $nuevas_etiquetas))) {
                header("Location: ./index.php");
                exit();
            }
        }
    }
}

else{
    echo $twig->render('error404.html');
}

?>