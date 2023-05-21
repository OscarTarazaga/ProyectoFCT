Create database ProyectoFCT;
Use ProyectoFCT;
drop table pacientes;
drop table usuarios;
drop table doctores;
drop table horarios;
drop table receta;
/*drop table administradores;*/

-- Crear tabla de pacientes
CREATE TABLE pacientes (
  dni VARCHAR(9) PRIMARY KEY,
  id INT,
  nombre VARCHAR(50),
  apellidos VARCHAR(50),
  genero ENUM('H', 'M'),
  edad INT, 
  direccion VARCHAR(120),
  telefono VARCHAR(15),
  passwd VARCHAR(16),
  dni_doctor VARCHAR(9),
  FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);

-- Crear tabla de doctores
CREATE TABLE doctores (
  dni VARCHAR(9) PRIMARY KEY,
  id INT,
  nombre VARCHAR(50),
  apellidos VARCHAR(50),
  genero ENUM('H', 'M'),
  edad INT, 
  especialidad VARCHAR(50),
  salario INT,
  horario_inicio TIME,
  horario_fin TIME,
  passwd VARCHAR(16)
);

/*
/*Matar tabla admin y pasarla a usuarios
-- Crear tabla de administradores
CREATE TABLE administradores (
  dni VARCHAR(9) PRIMARY KEY,
  id INT,
  nombre VARCHAR(50),
  passwd VARCHAR(16)
);
*/

/*
La tabla usuarios ya contiene la antigua tabla de administradores, pues solo hay un administrador
*/

CREATE TABLE usuarios (
  dni VARCHAR(9) PRIMARY KEY,
  dni_administrador VARCHAR(9),
  nombre VARCHAR(50),
  passwd VARCHAR(16),
  tipo ENUM('paciente', 'doctor', 'administrador'),
  genero ENUM('H', 'M'),
  dni_paciente VARCHAR(9),
  dni_doctor VARCHAR(9),
  FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni),
  FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);

-- Crear tabla de receta
CREATE TABLE receta (
/*Añado el campo id_receta y fecha_receta*/
  id_receta INT AUTO_INCREMENT PRIMARY KEY,
  fecha_receta date,
  comentario VARCHAR(200),
  dni_paciente VARCHAR(9),
  dni_doctor VARCHAR(9),
  FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni),
  FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);
select * from receta;
/*Inserción de receta*/
delete from receta where id_receta = 2;
INSERT INTO receta (fecha_receta, comentario, dni_paciente, dni_doctor)
VALUES ('2023-04-28', 'Tomar una pastilla de ibuprofeno cada 12 horas', '123456789', '987654321');

INSERT INTO receta (fecha_receta, comentario, dni_paciente, dni_doctor)
VALUES ('2022-11-05', 'Tomar una pastilla de enantyum cada 6 horas', '123456789', '987654321');

CREATE TABLE cita (
id_cita INT AUTO_INCREMENT PRIMARY KEY,
dni_paciente VARCHAR(9),
dni_doctor VARCHAR(9),
dia date,
hora TIME,
FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni),
FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);

CREATE TABLE tickets (
id_ticket INT AUTO_INCREMENT PRIMARY KEY,
texto VARCHAR(300),
dni_paciente VARCHAR(9),
FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni)
);

/*Es inutil puesto que ya tenemos una talba cita*/
-- Crear tabla de horarios
CREATE TABLE horarios (
  dni VARCHAR(9) PRIMARY KEY,
  dni_paciente VARCHAR(9),
  dni_doctor VARCHAR(9),
  fecha DATE,
  FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni),
  FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);

/*Insercion de datos:*/
/*Paciente*/
INSERT INTO pacientes (dni, id, nombre, apellidos, genero, edad, direccion, telefono, passwd)
VALUES ('123456789', 1, 'Juan', 'Perez', 'H', 30, 'Calle Falsa 123', '555-1234', 'contraseña1');

INSERT INTO pacientes (dni, id, nombre, apellidos, genero, edad, direccion, telefono, passwd, dni_doctor)
VALUES ('343536373', 122, 'Prueba', 'González', 'H', 30, 'Calle Principal 123', '1234567890', 'micontraseña', '987654321');

UPDATE pacientes
SET telefono = '616397567', apellidos = 'Perez Ureña'
WHERE dni = '123456789';

/*Doctor*/
INSERT INTO doctores (dni, id, nombre, apellidos, genero, edad, especialidad, salario, horario_inicio, horario_fin, passwd)
VALUES ('987654321', 1, 'Maria', 'Garcia', 'M', 40, 'Cardiología', 60000, '08:00:00', '14:00:00', 'contraseña2');

INSERT INTO doctores (dni, id, nombre, apellidos, genero, edad, especialidad, salario, horario_inicio, horario_fin, passwd)
VALUES ('49269244R', 1, 'Jose Maria', 'Garcia', 'H', 35, 'Cardiologia', 40000, '08:00:00', '14:00:00', 'contraseña3');

/*Administrador(TABLA USUARIOS)*/
INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('11111111R', '11111111R', 'Administrador', 'admin123', 'administrador', NULL, NULL, NULL);

/*Insercion en la tabla usuarios (PACIENTES)*/
INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('123456789', NULL, 'Juan Perez Ureña', 'contraseña1', 'paciente', 'H', '123456789', NULL);

INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('19547349J', NULL, 'Leire', 'contraseña1', 'paciente', 'M', '123456789', NULL);

INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('19547349J', NULL, 'Leire', 'contraseña', 'paciente', 'M', '19547349J', NULL);

/*Insercion en la tabla usuarios (DOCTORES)*/
INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('987654321', NULL, 'Maria Garcia', 'contraseña2', 'doctor', 'M', NULL, '987654321');

INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('49269244R', NULL, 'Jose Maria Garcia', 'contraseña3', 'doctor', 'H', NULL, '49269244R');

/*Comprobación de las inserciones*/
Select * from usuarios;
Select * from pacientes;
Select * from doctores;
Select * from cita;
Select * from tickets;
Select * from receta;
describe tickets;

update pacientes set apellidos="Pérez Ureña" where dni="123456789";

delete from doctores where id = 2;
delete from cita where id_cita = 55;
delete from tickets where id_ticket = 3;

/*Asignación de paciente a un doctor*/
ALTER TABLE pacientes ADD dni_doctor VARCHAR(9);
ALTER TABLE pacientes ADD FOREIGN KEY (dni_doctor) REFERENCES doctores(dni);

UPDATE pacientes SET dni_doctor = '49269244R' WHERE dni = '123456789';