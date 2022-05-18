# Accedemos 
# Conectar a mysql
mysql -h 127.0.0.1 -P 3306 -u root -p
tiger #contraseña

# Crear usuario admin, con contraseña admin
create user 'admin'@'%' identified by 'admin';

# Otorgar permisos al usuario para no usar el root
GRANT CREATE, REFERENCES, ALTER, LOCK TABLES, DELETE, DROP, INDEX, INSERT, SELECT, UPDATE ON SIBW.* TO 'admin'@'%';

# Salir de sql
exit;

# Conectar a mysql con el usuario creado con contraseña admin
mysql -h 127.0.0.1 -P 3306 -u admin -p 
admin #Contraseña 

# Creamos una base de datos llamada "SIBW"
CREATE DATABASE SIBW;

# Cambiar a la base de datos que hemos creado (SIBW)
use SIBW;


### Creacion de tablas en la base de datos

#   Comentarios  
#                           ---CE-----
#            ---------CP---------------
#comentarios(id_comentario, id_producto, nombre, opinion, correo, fecha)
# CP Ambos para que sea más rápido filtrar los comentarios de un producto

#   Productos    
#                          
#         ----CP----
#productos(id_producto, nombre_producto, marca, precio, descripcion, url_imagen_principal, url, texto_enlace)


#   Imágenes
#                    ----CE---
#        ----------CP---------
#imagenes(id_imagen, id_producto, ruta)


#   Palabrotas
#               ----CP---
#   palabrotas (palabrota)

#                   ----CE---
#         ----------CP---------
#enlaces(id_enlace, id_producto, texto, url)

# En sql, creamos la tabla de productos
CREATE TABLE productos(
	id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    precio FLOAT NOT NULL,
    descripcion VARCHAR(512) NOT NULL,
    url_imagen_principal VARCHAR(512) NOT NULL
);


# En sql, creamos la tabla de comentarios
CREATE TABLE comentarios(
    id_comentario INT AUTO_INCREMENT NOT NULL,
    id_producto INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    opinion VARCHAR(512) NOT NULL,
    correo VARCHAR(60) NOT NULL,
    fecha DATETIME NOT NULL,
    CONSTRAINT fk_producto_comentario FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    CONSTRAINT pk_comentario PRIMARY KEY(id_comentario, id_producto)
);

# En sql, creamos la tabla de imagenes secundarias
CREATE TABLE imagenes(
	id_imagen INT UNIQUE NOT NULL,
	id_producto INT NOT NULL,
	ruta VARCHAR(512) NOT NULL,
	CONSTRAINT fk_producto_imagen FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
	CONSTRAINT pk_imagen PRIMARY KEY(id_imagen, id_producto)
);

# En sql, creamos la tabla de palabrotas
CREATE TABLE palabrotas(
    palabrota VARCHAR(30) PRIMARY KEY
);

# En sql, creamos la tabla de enlaces (los de abajo del producto)
CREATE TABLE enlaces(
    id_enlace INT AUTO_INCREMENT NOT NULL,
    id_producto INT NOT NULL,
    texto VARCHAR(100) NOT NULL,
    url VARCHAR(512) NOT NULL,
    CONSTRAINT fk_producto_enlace FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    CONSTRAINT pk_enlace PRIMARY KEY(id_enlace, id_producto)
);

# Realizamos algunas insercciones 
# Insercion de productos
INSERT INTO productos(id_producto, nombre_producto, marca, precio, url_imagen_principal, descripcion) VALUES
(1, 'Cajar CF', 'Federacion Andaluza de Futbol', 1500, '/images/escudo1.png',
'El Cajar CF es un club de personas con gran potencial que no disponen de los medios para acabar en un equipo de mayor categoría.
En este equipo han llegado a jugar jugadores del mayor calibre, como el estanquero del pueblo de 40 anios, o el panadero, que se tiraba el partido hablando con las señoras en la grada.'),
(2, 'CD Baza', 'Federacion de la Dama de Baza de Futbol', 3000, '/images/escudo2.png',
'Club de la zona norte de Granada, referente de la comarca, gracias a su cantera de donde han salido jugadores que han dejado el futbol'),
(3, 'Guadix CF', 'Federacion Desertica de Futbol', 300, '/images/escudo3.png',
'Equipo que juega en un desierto porque no tienen mucha mas infraestructura por alli. Jugadores veteranos'),
(4, 'Almunecar City CF', 'Federacion de Chirimoyos', 10000, 'images/escudo5.png',
'Mejor equipo de la costa tropical. Ha ganado recientemente la copa chirimoyo');

#Insercion de imagenes
INSERT INTO imagenes(id_imagen, id_producto, ruta) VALUES
(1,1,'/images/extra1.png'),
(2,2,'/images/extra2.jpg'); 

#Insercion de palabrotas
INSERT INTO palabrotas(palabrota) VALUES
('gilipollas'),
('retrasado'),
('puta'),
('inutil'),
('tonto'),
('polla'),
('capullo'),
('pito'),
('zorra'),
('tonta'),
('desgraciado');

#Insercion de comentarios
INSERT INTO comentarios(id_comentario, id_producto, nombre, opinion, correo, fecha) VALUES
(1,1, 'Paco', 'Buen equipo, lo recomiendo para todos aquellos que quieren vivir una experiencia inolvidable.',
'pacopaquito@pa.com', '2021-09-29 16:47:00'),
(2,1, 'Ronaldo', ' Tiene sus problemillas en la liga, pero se ve que se curran lo de levantar copas (de fiesta obviamente).',
'elbicho@correo.ugr.es', '2022-01-31 11:54:00'),
(3,2, 'Antonio', ' No compreis el Baza, es una estafa y los jugadores realmente no van a los partidos',
'antonio@avispao.net', '2022-03-12 14:10:00');

INSERT INTO enlaces(id_producto, texto, url) VALUES #Al ser AutoIncrement el id_enlace, se incrementa
(1, 'Enlace FaceBook del Club', 'https://www.facebook.com/RecreativoCajar2016/'),
(1, 'Clasificacion del Equipo', 'https://www.resultados-futbol.com/tercera_andaluza_granada/grupo1' ),
(1, 'Calendario del Equipo', 'https://www.resultados-futbol.com/tercera_andaluza_granada2022/grupo1/calendario' ),
(2, 'Enlace club', 'https://www.lapreferente.com/E23034/cd-ciudad-de-baza'),
(3, 'Enlace club','https://www.lapreferente.com/E337/guadix-cf');
(4, 'Enlace club','https://www.lapreferente.com/E23030/cd-almu%C3%B1ecar-city');

exit;