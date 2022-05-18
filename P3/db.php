<?php

    //Clase con todas las posibles cosas que pudieramos hacer sobre la BD
    class AccesoBD
    {
        private $host = "mysql";
        private $usuario = "admin";
        private $clave = "admin";
        private $db = "SIBW"; //BD se llama SIBW y tiene las distinas tablas
        public $conexion = null; //ahora mismo no tenemos conexion

        
        public function __construct() // Constructor
        {
            $this->conexion = new mysqli($this->host, $this->usuario, $this->clave, $this->db) or die(mysqli_error());
            // Si va mal, hacer exit(die) con el ultimo codigo de error de mysqli
        }

        public function cerrarConexion() 
        {
            $this->conexion = null;
        }

        public function getProductos() 
        {
            //query es para consulta a la BD. 
            $productos = $this->conexion->query("SELECT id_producto, nombre_producto, marca, precio, descripcion, url_imagen_principal FROM productos");
            return $productos->fetch_all(MYSQLI_ASSOC);
            //fetch_all para obtener el resultado como vector. mysqli_assoc para vector asociativo
            //asociativo es que no accedes con número, accedes con un string
        }

        public function getProducto($id_producto)
        {
            $inf_producto = $this->conexion->prepare("SELECT id_producto, nombre_producto, marca, precio, descripcion, url_imagen_principal FROM productos WHERE id_producto = ?");
            // Preparamos sentencia para prevenir inyección de código
            $inf_producto->bind_param("i", $id_producto); #Buscamos que ? sea un entero, con el valor de idEvento
            $inf_producto->execute(); #Ejecutamos la sentencia
            $inf_producto = $inf_producto->get_result(); // Obtiene resultado de una sentencia preparada
            $producto = $inf_producto->fetch_assoc();  //Obtener UNA fila de inf, como array asociativo

            if ($inf_producto->num_rows <= 0) //Si no tenemos nada asoaciado a ese id, no tendremos filas
            {
                $producto['id_producto'] = 0;
                $producto['nombre_producto'] = "Equipo Base";
                $producto['marca'] = "Marca Base";
                $producto['precio'] = 0;
                $producto['descripcion'] = "Descripción base";
                $producto['url_imagen_principal'] = "";
            }

            //$inf_producto->close();

            return $producto;
        }

        public function getComentarios($id_producto) 
        {
            $inf_comentarios = $this->conexion->prepare("SELECT id_comentario, id_producto, nombre, opinion, correo, fecha FROM comentarios WHERE id_producto = ? ORDER BY fecha");
            //Recuperar comentarios que sean asociados al producto seleccionado. Ordenados por la fecha
            $inf_comentarios->bind_param("i", $id_producto);
            $inf_comentarios->execute();
            $inf_comentarios = $inf_comentarios->get_result(); //Sobreescribimos la variable con el resultado de la sentencia
            return $inf_comentarios->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        public function getEnlaces($id_producto) //Enlaces de abajo del body
        {
            $inf_enlaces = $this->conexion->prepare("SELECT id_enlace, id_producto, texto, url FROM enlaces WHERE id_producto = ?");
            //Recuperar comentarios que sean asociados al producto seleccionado. Ordenados por la fecha
            $inf_enlaces->bind_param("i", $id_producto);
            $inf_enlaces->execute();
            $inf_enlaces = $inf_enlaces->get_result(); //Sobreescribimos la variable con el resultado de la sentencia
            return $inf_enlaces->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        public function getImagenes($id_producto) 
        {
            $inf_imagenes = $this->conexion->prepare("SELECT id_imagen, id_producto, ruta FROM imagenes WHERE id_producto = ?");
            $inf_imagenes->bind_param("i", $id_producto);
            $inf_imagenes->execute();
            $inf_imagenes = $inf_imagenes->get_result();
            return $inf_imagenes->fetch_all(MYSQLI_ASSOC); //Devuelve todas las filas
        }

        //Método para obtener un comentario dado su id??
        /*

        public function getPalabrotas() 
        {
            $inf_palabrotas = $this->conexion->query("SELECT palabrota FROM palabrotas");
            return $inf_palabrotas->fetch_all(MYSQLI_ASSOC); //Devolver las palabrotas como array asociativo
        }

        public function crearComentario($id_producto, $nombre, $opinion, $correo, $fecha) 
        {

        }
        */
    }

?>