<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    include ("db.php"); //Funciones que interactúan con la BD

    $acceso_bd = new AccesoBD();

    session_start();

    if(isset($_SESSION['usuario']) and ($_SESSION['usuario']['rol'] === 'superusuario' or $_SESSION['usuario']['rol'] === 'gestor'))
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
                        // Obtener id de la imagen e id del producto asociado
            $id_producto = $_POST['id_producto'];

            // Si no se cambian las imágenes
            if($_FILES['fotos_insertar']['error'][0] === UPLOAD_ERR_NO_FILE) {
                $url_imagenes = null;
            }
            else { // Si hay nuevas imágenes
                $imagenes_tmp = $_FILES['fotos_insertar']['tmp_name'];
                $imagenes_name = $_FILES['fotos_insertar']['name'];
                for ($i = 0; $i < sizeof($imagenes_name); $i++) {
                    $max_id_imagen = $acceso_bd->getMaxIdImagen();
                    $url_imagenes[$i] = "/images/$max_id_imagen"."$imagenes_name[$i]";
                    $acceso_bd->aniadirImagen($id_producto, $url_imagenes[$i]);
                }
                for ($i = 0; $i < sizeof($imagenes_name); $i++) {
                    move_uploaded_file($imagenes_tmp[$i], "." . $url_imagenes[$i]);
                }
            }

            header("Location: ./producto.php?prod=$id_producto");
            exit();
        }
    }
    
    else{
        echo $twig->render('error404.html');
    }
?>