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

        public function __destruct()  // Destructor 
        {
            $this->conexion = null;
        }

        public function cerrarConexion() //Por si quisieramos hacer de "destructor"
        {
            $this->conexion = null;
        }

        public function getProductos() 
        {
            //query es para consulta a la BD. 
            $productos = $this->conexion->query("SELECT * FROM productos");
            return $productos->fetch_all(MYSQLI_ASSOC);
            //fetch_all para obtener el resultado como vector. mysqli_assoc para vector asociativo
            //asociativo es que no accedes con número, accedes con un string
        }

        public function getProducto($id_producto)
        {
            $inf_producto = $this->conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
            // Preparamos sentencia para prevenir inyección de código
            $inf_producto->bind_param("i", $id_producto); 
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

            return $producto;
        }

        public function getEnlaces($id_producto) //Enlaces de abajo del body
        {
            $inf_enlaces = $this->conexion->prepare("SELECT * FROM enlaces WHERE id_producto = ?");
            //Recuperar comentarios que sean asociados al producto seleccionado. Ordenados por la fecha
            $inf_enlaces->bind_param("i", $id_producto);
            $inf_enlaces->execute();
            $inf_enlaces = $inf_enlaces->get_result(); //Sobreescribimos la variable con el resultado de la sentencia
            return $inf_enlaces->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        public function getImagenes($id_producto) 
        {
            $inf_imagenes = $this->conexion->prepare("SELECT * FROM imagenes WHERE id_producto = ?");
            $inf_imagenes->bind_param("i", $id_producto);
            $inf_imagenes->execute();
            $inf_imagenes = $inf_imagenes->get_result();
            return $inf_imagenes->fetch_all(MYSQLI_ASSOC); //Devuelve todas las filas
        }

        //Método para obtener un comentario dado su id??
        
        public function getPalabrotas() 
        {
            $inf_palabrotas = $this->conexion->query("SELECT palabrota FROM palabrotas");
            return $inf_palabrotas->fetch_all(MYSQLI_ASSOC); //Devolver las palabrotas como array asociativo
        }

        public function getUsuario($usuario) 
        {
            $infoUsuario = $this->conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
            $infoUsuario->bind_param("s", $usuario);
            $infoUsuario->execute();
            $infoUsuario = $infoUsuario->get_result();
            $usuario = $infoUsuario->fetch_assoc();
            return $usuario;
        }

        public function getUsuarios() //Obtener todos los usuarios
        {
            $infoUsuarios = $this->conexion->query("SELECT * FROM usuarios");
            return $infoUsuarios->fetch_all(MYSQLI_ASSOC);
        }

        function checkLogin($nick, $pass) 
        {
            $usuario = $this->getUsuario($nick);

            if ($usuario){
                return password_verify($pass, $usuario['password'] );
            }
            return false;
        }

        public function existeUsuario($username) //Comprobar si ya hay alguien con ese nombre de usuario
        {
            $infoUsuario = $this->conexion->prepare("SELECT username FROM usuarios WHERE username = ?");
            $infoUsuario->bind_param("s", $username);
            $infoUsuario->execute();
            return $infoUsuario->get_result()->num_rows == 1; //Comparamos para devolver bool de si existe o no
        }

        public function registrarUsuario($usuario) //Enviarle un vector con los datos de usuario y registrarlo 
        {
            $hash = password_hash($usuario['password'], PASSWORD_DEFAULT);
            $fecha_registro = date('Y-m-d', strtotime("+2 hours"));

            $registroUsuario = $this->conexion->prepare("INSERT INTO usuarios(username, password, rol, nombre, apellidos, DNI, email, fecha_nacimiento, telefono, fecha_registro) VALUES
                     (?, ?, 'registrado', ?, ?, ?, ?, ?, ?, ?)");
            $registroUsuario->bind_param("sssssssss", $usuario['username'], $hash, $usuario['nombre'], $usuario['apellidos'], $usuario['DNI'], $usuario['email'], $usuario['fecha_nacimiento'], $usuario['telefono'], $fecha_registro);
            $registroUsuario->execute();
            return $registroUsuario;
        }

        public function actualizarUsuario($usuario, $antiguoUsername) //Enviarle un vector con los datos de usuario y registrarlo 
        {
            $hash = password_hash($usuario['password'], PASSWORD_DEFAULT);

            $actualizacionUsuario = $this->conexion->prepare("UPDATE usuarios SET username = ?, email = ?, nombre = ?, apellidos = ?, telefono = ?, DNI = ?, password = ?, fecha_nacimiento = ? WHERE username = ?");
            $actualizacionUsuario->bind_param("sssssssss", $usuario['username'], $usuario['email'], $usuario['nombre'], $usuario['apellidos'], $usuario['telefono'], $usuario['DNI'], $hash, $usuario['fecha_nacimiento'], $antiguoUsername);
            return $actualizacionUsuario->execute();
        }

        public function getComentarios($id_producto) //Comentarios P4
        {
            $inf_comentarios = $this->conexion->prepare("SELECT * FROM comentarios WHERE id_producto = ? ORDER BY fecha");
            //Recuperar comentarios que sean asociados al producto seleccionado. Ordenados por la fecha
            $inf_comentarios->bind_param("i", $id_producto);
            $inf_comentarios->execute();
            $inf_comentarios = $inf_comentarios->get_result(); //Sobreescribimos la variable con el resultado de la sentencia
            return $inf_comentarios->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        #comentarios(id_comentario, id_producto, opinion, fecha, username) Username o id_usuario
        public function crearComentario($id_prod, $username, $opinion, $fecha) //P4
        {
            $creacionComentario = $this->conexion->prepare("INSERT INTO comentarios(id_producto, username, opinion, fecha) VALUES (?, ?, ?, ?)");
            $creacionComentario->bind_param("isss", $id_prod, $username, $opinion, $fecha);
            return $creacionComentario->execute();

            //cuidado CP ambos id y solo autoincrement del primero
        }

        public function eliminarComentario($id_comentario, $id_producto)
        {
            $eliminarComentario = $this->conexion->prepare("DELETE FROM comentarios WHERE id_producto = ? AND id_comentario = ?");
            $eliminarComentario->bind_param("ii", $id_producto, $id_comentario);
            return $eliminarComentario->execute();
        }

        public function getComentario($id_producto, $id_comentario) //Comentarios P4
        {
            $infoComentario = $this->conexion->prepare("SELECT * FROM comentarios WHERE id_producto = ? AND id_comentario = ?");
            $infoComentario->bind_param("ii", $id_producto, $id_comentario);
            $infoComentario->execute();
            $infoComentarios = $infoComentario->get_result();
            return $infoComentarios->fetch_assoc(); //Obtener una tupla solo
        }

        public function editarComentario($id_producto, $id_comentario, $opinion, $id_moderador)
        {
            $actualizacionComentario = $this->conexion->prepare("UPDATE comentarios SET id_moderador = ?, opinion = ? WHERE id_producto = ? AND id_comentario = ?");
            $actualizacionComentario->bind_param("ssii", $id_moderador, $opinion, $id_producto, $id_comentario);
            return $actualizacionComentario->execute();
        }

        public function actualizarRol($username, $rol) //Modificar el parametro rol de usuario 
        {
            $actualizarRol = $this->conexion->prepare("UPDATE usuarios SET rol = ? WHERE username = ?");
            $actualizarRol->bind_param("ss", $rol,$username);
            return $actualizarRol->execute();
        }

        public function getRoles() //Ver todos los posibles roles
        {
            $infoUsuarios = $this->conexion->query("SELECT * FROM roles");
            return $infoUsuarios->fetch_all(MYSQLI_ASSOC);
        }

        public function getComentariosGlobal() //Todos los comentarios
        {
            $inf_comentarios = $this->conexion->query("SELECT * FROM comentarios ORDER BY fecha");
            return $inf_comentarios->fetch_all(MYSQLI_ASSOC); //Devolver las palabrotas como array asociativo
        }

        public function buscarComentarios($texto_comentario)
        {
            $buscarComentario = $this->conexion->query("SELECT * FROM comentarios WHERE opinion LIKE '%".$texto_comentario."%'");
            return $buscarComentario->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        public function editarEtiquetas($id_producto, $nuevas_etiquetas) //actualizar etiquetas de un producto. 
        {
            $actualizacionEtiquetas = $this->conexion->prepare("UPDATE productos SET etiquetas = ? WHERE id_producto = ?");
            $actualizacionEtiquetas->bind_param("ss", $nuevas_etiquetas, $id_producto);
            return $actualizacionEtiquetas->execute();
        }

        public function buscarProductos($descripcion)
        {
            $buscarProducto = $this->conexion->query("SELECT * FROM productos WHERE descripcion LIKE '%".$descripcion."%'");
            return $buscarProducto->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        public function borrarComentarios($id_producto) {
            $eliminarComentarios = $this->conexion->prepare("DELETE FROM comentarios WHERE id_producto = ?");
            $eliminarComentarios->bind_param("i", $id_producto);
            return $eliminarComentarios->execute();
        }

        public function borrarEnlaces($id_producto) {

            $eliminarEnlaces = $this->conexion->prepare("DELETE FROM enlaces WHERE id_producto = ?");
            $eliminarEnlaces->bind_param("i", $id_producto);
            return $eliminarEnlaces->execute();
        }

        public function borrarProducto($id_producto)
        {
            $this->borrarEnlaces($id_producto);
            $this->borrarImagenes($id_producto);
            $this->borrarComentarios($id_producto);
            $this->borrarPortada($id_producto);

            $eliminarProducto = $this->conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
            $eliminarProducto->bind_param("i", $id_producto);
            return $eliminarProducto->execute();
        }

        public function getFotoPortada($id_producto) {
            $infoUrlPortada = $this->conexion->prepare("SELECT url_imagen_principal FROM productos WHERE id_producto = ?");
            $infoUrlPortada->bind_param("i", $id_producto);
            $infoUrlPortada->execute();
            $infoUrlPortada = $infoUrlPortada->get_result();
            $infoUrlPortada = $infoUrlPortada->fetch_assoc(); //solo una fila
            return $infoUrlPortada['url_imagen_principal'];
        }

        public function editarProducto($id_producto, $nombre_producto, $marca, $precio, $descripcion, $portada, $publicado) //actualizar etiquetas de un producto. 
        {
            // Si no se ha añadido portada obtenemos la anterior
            if($portada === null) {
                $portada = $this->getFotoPortada($id_producto);
            }
            else {
                $portada_vieja = $this->getFotoPortada($id_producto);
                if ($portada !== $portada_vieja) // Si la portada va a ser distinta -> borrar la antigua portada
                    unlink("." . $portada_vieja);
            }

            $actualizacionProducto = $this->conexion->prepare("UPDATE productos SET nombre_producto = ?, marca = ?, precio = ?, descripcion = ?, url_imagen_principal = ?, publicado = ? WHERE id_producto = ?");
            $actualizacionProducto->bind_param("ssdssii", $nombre_producto, $marca, $precio, $descripcion, $portada, $publicado, $id_producto);
            return $actualizacionProducto->execute();
        }

        public function crearProducto($nombre_producto, $marca, $precio, $descripcion, $portada)
        {
            $creacionProducto = $this->conexion->prepare("INSERT INTO productos(nombre_producto, marca, precio, descripcion, url_imagen_principal) VALUES (?, ?, ?, ?, ?)");
            $creacionProducto->bind_param("ssdss", $nombre_producto, $marca, $precio, $descripcion, $portada);
            return $creacionProducto->execute();
        }

        public function crearEnlace($id_prod, $texto_enlace, $url_enlace)
        {
            $creacionEnlace = $this->conexion->prepare("INSERT INTO enlaces(id_producto, texto, url) VALUES (?, ?, ?)");
            $creacionEnlace->bind_param("iss", $id_prod, $texto_enlace, $url_enlace);
            return $creacionEnlace->execute();
        }

        public function borrarEnlace($id_enlace, $id_producto)
        {
            $eliminarEnlace = $this->conexion->prepare("DELETE FROM enlaces WHERE id_producto = ? AND id_enlace = ?");
            $eliminarEnlace->bind_param("ii", $id_producto, $id_enlace);
            return $eliminarEnlace->execute();
        }

        public function getMaxIdImagen()
        {
            $idImagen = $this->conexion->query("SELECT max(id_imagen) FROM imagenes");
            $idImagen = $idImagen->fetch_all(MYSQLI_ASSOC)[0]['max(id_imagen)'];
            if ($idImagen === null) { $idImagen = 1; }
            else { $idImagen = intval($idImagen) + 1; }

            return $idImagen;
        }

        public function aniadirImagen($id_producto, $url_imagen) {
            $idImagen = $this->getMaxIdImagen();
            $creacionImagen = $this->conexion->prepare("INSERT INTO imagenes(id_imagen, id_producto, ruta) 
                     VALUES
                     (?, ?, ?)");

            $creacionImagen->bind_param("iis", $idImagen, $id_producto, $url_imagen);
            return $creacionImagen->execute();
        }

        public function getImagen($id_producto, $id_imagen)
        {
            $imagenes = $this->conexion->prepare("SELECT id_imagen, id_producto, ruta FROM imagenes WHERE id_producto = ? and id_imagen = ?");
            $imagenes->bind_param("ii", $id_producto, $id_imagen);
            $imagenes->execute();
            $imagenes = $imagenes->get_result();

            return $imagenes->fetch_assoc();
        }

        public function borrarImagenes($id_producto){

            $imagenes = $this->getImagenes($id_producto);

            for ($i = 0; $i < sizeof($imagenes); $i++) {
                unlink("." . $imagenes[$i]['ruta']);
            }

            $eliminarImagenes = $this->conexion->prepare("DELETE FROM imagenes WHERE id_producto = ?");
            $eliminarImagenes->bind_param("i", $id_producto);
            if(!$eliminarImagenes->execute()) {
                return false;
            }

            return true;
        }

        public function borrarImagen($id_producto, $id_imagen)
        {
            $imagen = $this->getImagen($id_producto, $id_imagen);
            $eliminarImagen = $this->conexion->prepare("DELETE FROM imagenes WHERE id_producto = ? AND id_imagen = ?");
            $eliminarImagen->bind_param("ii", $id_producto, $id_imagen);

            unlink("." . $imagen['ruta']);
            return $eliminarImagen->execute();
        }

        public function borrarPortada($id_producto)
        {
            $portada = $this->getProducto($id_producto)['url_imagen_principal'];

            return unlink("." . $portada);
        }

        public function getProductoPorDatos($nombre_producto, $marca, $precio) //Obtener id_producto dado su precio, nombre y marca
        {
            $inf_producto = $this->conexion->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ? and marca = ? and precio = ?");

            // Preparamos sentencia para prevenir inyección de código
            $inf_producto->bind_param("ssd", $nombre_producto, $marca, $precio);
            $inf_producto->execute(); 
            $inf_producto = $inf_producto->get_result(); 
            $producto = $inf_producto->fetch_assoc();

            return $producto['id_producto'];
        }

        public function obtenerProductos($busqueda) { //Obtiene productos segun semejanzas con nombre y descripcion. buscarProducto solo por desc
            $obtenerProductos = $this->conexion->query("SELECT * FROM (
                                SELECT * FROM productos WHERE nombre_producto LIKE '%".$busqueda."%' 
                                UNION 
                                SELECT * FROM productos WHERE descripcion LIKE '%".$busqueda."%') AS RESULTADO");
            return $obtenerProductos->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }

        public function obtenerProductosPublicados($busqueda) {
            $obtenerProductos = $this->conexion->query("SELECT * FROM (
                                SELECT * FROM productos WHERE publicado = 1 AND nombre_producto LIKE '%".$busqueda."%' 
                                UNION 
                                SELECT * FROM productos WHERE publicado = 1 AND descripcion LIKE '%".$busqueda."%') AS RESULTADO");
            return $obtenerProductos->fetch_all(MYSQLI_ASSOC); //Devolver los comentarios como array asociativo
        }
    }
?>