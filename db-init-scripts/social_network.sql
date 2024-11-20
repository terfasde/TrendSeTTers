-- Configuración inicial
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS social_network;
USE social_network;

-- Crear tablas con PK y FK directamente
CREATE TABLE usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  nombre_usuario VARCHAR(50) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) DEFAULT NULL,
  fecha_registro DATE NOT NULL,
  rol ENUM('user', 'admin') DEFAULT 'user',
  PRIMARY KEY (id_usuario)
);

CREATE TABLE amigo (
  usuario_1 INT NOT NULL,
  usuario_2 INT NOT NULL,
  PRIMARY KEY (usuario_1, usuario_2),
  CONSTRAINT fk_amigo_usuario1 FOREIGN KEY (usuario_1) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CONSTRAINT fk_amigo_usuario2 FOREIGN KEY (usuario_2) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE post (
  id_post INT NOT NULL AUTO_INCREMENT,
  contenido VARCHAR(255) DEFAULT NULL,
  id_usuario INT NOT NULL,
  fecha_post DATE NOT NULL,
  likes_post INT DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id_post),
  CONSTRAINT fk_post_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE comentario (
  id_comentario INT NOT NULL AUTO_INCREMENT,
  id_post INT DEFAULT NULL,
  id_usuario INT DEFAULT NULL,
  comentario TEXT,
  PRIMARY KEY (id_comentario),
  CONSTRAINT fk_comentario_post FOREIGN KEY (id_post) REFERENCES post (id_post) ON DELETE CASCADE,
  CONSTRAINT fk_comentario_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE evento (
  id_evento INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  fecha_evento DATE NOT NULL,
  detalle_evento VARCHAR(50) DEFAULT NULL,
  titulo_evento VARCHAR(100) DEFAULT NULL,
  descripcion_evento VARCHAR(255) DEFAULT NULL,
  contador_usuarios INT DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id_evento),
  CONSTRAINT fk_evento_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE grupo (
  id_grupo INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  titulo_grupo VARCHAR(100) NOT NULL,
  fecha_de_creacion DATE NOT NULL,
  imagen_perfil_grupo VARCHAR(255) DEFAULT NULL,
  descripcion_grupo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id_grupo),
  CONSTRAINT fk_grupo_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE imagenes_post (
  id_imagen INT NOT NULL AUTO_INCREMENT,
  id_post INT NOT NULL,
  imagenes_post VARCHAR(255) DEFAULT NULL,
  ruta_imagen VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id_imagen),
  CONSTRAINT fk_imagenes_post FOREIGN KEY (id_post) REFERENCES post (id_post) ON DELETE CASCADE
);

CREATE TABLE mensajes_usuario (
  id_mensaje INT NOT NULL AUTO_INCREMENT,
  id_remitente INT NOT NULL,
  id_destinatario INT NOT NULL,
  contenido_mensaje TEXT NOT NULL,
  fecha_mensaje TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_mensaje),
  CONSTRAINT fk_mensajes_remitente FOREIGN KEY (id_remitente) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CONSTRAINT fk_mensajes_destinatario FOREIGN KEY (id_destinatario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE solicitudes_amistad (
  id_solicitud INT NOT NULL AUTO_INCREMENT,
  usuario_1 INT NOT NULL,
  usuario_2 INT NOT NULL,
  estado ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
  fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_solicitud),
  CONSTRAINT fk_solicitud_usuario1 FOREIGN KEY (usuario_1) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CONSTRAINT fk_solicitud_usuario2 FOREIGN KEY (usuario_2) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE upvote (
  id_upvote INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  id_post INT NOT NULL,
  PRIMARY KEY (id_upvote),
  UNIQUE KEY unique_upvote (id_usuario, id_post),
  CONSTRAINT fk_upvote_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CONSTRAINT fk_upvote_post FOREIGN KEY (id_post) REFERENCES post (id_post) ON DELETE CASCADE
);

CREATE TABLE upvote_comentario (
  id_com_upvote INT NOT NULL AUTO_INCREMENT,
  id_usuario INT DEFAULT NULL,
  id_comentario INT DEFAULT NULL,
  PRIMARY KEY (id_com_upvote),
  CONSTRAINT fk_upvote_comentario_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CONSTRAINT fk_upvote_comentario FOREIGN KEY (id_comentario) REFERENCES comentario (id_comentario) ON DELETE CASCADE
);

CREATE TABLE notificacion (
  id_notificacion INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  notificacion_leida TINYINT(1) DEFAULT 0,
  notificacion_sin_leer TINYINT(1) DEFAULT 1,
  fecha_notificacion DATE NOT NULL,
  PRIMARY KEY (id_notificacion),
  CONSTRAINT fk_notificacion_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

CREATE TABLE perfil (
  id_perfil INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  nombre_completo VARCHAR(100) DEFAULT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  foto_perfil LONGBLOB,
  PRIMARY KEY (id_perfil),
  CONSTRAINT fk_perfil_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
);

INSERT INTO usuario (nombre_usuario, correo, contraseña, fecha_registro, rol) 

VALUES ('Francisco', 'francisco@example.com', 'contraseña_francisco', CURDATE(), 'admin');


INSERT INTO usuario (nombre_usuario, correo, contraseña, fecha_registro, rol) 

VALUES ('Santiago', 'Santiago@example.com', 'contraseña_santiago', CURDATE(), 'user');