<?php
    include("db.php");

    $acceso_bd = new AccesoBD();

    header('Content-Type: application/json');
    
    $busqueda = $_POST['busqueda_post'];
    $rol = $_POST['rol_post'];
    

    if ($rol === 'superusuario' OR $rol === 'gestor') {
        $productos_busqueda = $acceso_bd->obtenerProductos($busqueda);
    } 
    else {
        $productos_busqueda = $acceso_bd->obtenerProductosPublicados($busqueda);
    } 

    echo(json_encode($productos_busqueda));
?>