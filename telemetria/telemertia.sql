CREATE DATABASE telemetria;
USE telemetria;
CREATE TABLE usuarios (
  id INT NOT NULL UNIQUE AUTO_INCREMENT,
  nombre VARCHAR(25) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fecha_registro DATETIME NOT NULL,
  activo TINYINT NOT NULL,
  PRIMARY KEY(id)
);
CREATE TABLE datos(
  id INT NOT NULL UNIQUE AUTO_INCREMENT,
  zona varchar(40) not null,
  codigo varchar(50) not null,
  tipo varchar(20) not null,
  dato float not null,
  servicio TINYINT NOT NULL,
  idfecha int not null,
  primary key(id)
);
CREATE TABLE fechas(
  id INT NOT NULL UNIQUE AUTO_INCREMENT,
  fecha_actualizacion varchar(30) not null,
  primary key(id)
);
CREATE TABLE favoritos (
  id INT NOT NULL UNIQUE AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  codigo varchar(50) not null,
  alerta TINYINT NOT NULL,
  max FLOAT NOT NULL,
  min FLOAT NOT NULL,
  semaforo TINYINT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(usuario_id)
    REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);
CREATE TABLE recuperacion_clave (
  id INT NOT NULL UNIQUE AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  enlace VARCHAR(255) NOT NULL UNIQUE,
  fecha DATETIME NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(usuario_id)
    REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);
