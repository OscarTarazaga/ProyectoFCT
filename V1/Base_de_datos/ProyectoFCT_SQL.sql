Create database ProyectoFCT;
Use ProyectoFCT;

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
  comentario VARCHAR(700),
  dni_paciente VARCHAR(9),
  dni_doctor VARCHAR(9),
  FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni),
  FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);

-- Crear tabla de citas
CREATE TABLE cita (
id_cita INT AUTO_INCREMENT PRIMARY KEY,
dni_paciente VARCHAR(9),
dni_doctor VARCHAR(9),
dia date,
hora TIME,
FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni),
FOREIGN KEY (dni_doctor) REFERENCES doctores(dni)
);

-- Crear tabla de tickets
CREATE TABLE tickets (
id_ticket INT AUTO_INCREMENT PRIMARY KEY,
texto VARCHAR(300),
dni_paciente VARCHAR(9),
FOREIGN KEY (dni_paciente) REFERENCES pacientes(dni)
);

/*Administrador(TABLA USUARIOS)*/
INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
VALUES ('11111111R', '11111111R', 'Administrador', 'admin123', 'administrador', NULL, NULL, NULL);