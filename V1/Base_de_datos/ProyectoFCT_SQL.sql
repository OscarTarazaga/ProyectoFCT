Create database ProyectoFCT;

-- Crear tabla de pacientes
CREATE TABLE pacientes (
  id INT PRIMARY KEY,
  dni VARCHAR(9),
  nombre VARCHAR(50),
  apellidos VARCHAR(50),
  genero ENUM('H', 'M'),
  edad INT, 
  direccion VARCHAR(120),
  telefono VARCHAR(15),
  passwd VARCHAR(16)
);

-- Crear tabla de doctores
CREATE TABLE doctores (
  id INT PRIMARY KEY,
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

-- Crear tabla de administradores
CREATE TABLE administradores (
  id INT PRIMARY KEY,
  nombre VARCHAR(50),
  passwd VARCHAR(16)
);

-- Crear tabla de receta
CREATE TABLE receta (
  medicina VARCHAR(40),
  cantidad INT,
  comentario VARCHAR(200),
  id_paciente INT,
  id_doctor INT,
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
  FOREIGN KEY (id_doctor) REFERENCES doctores(id)
);

-- Crear tabla de horarios
CREATE TABLE horarios (
  id INT PRIMARY KEY,
  id_paciente INT,
  id_doctor INT,
  fecha DATE,
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
  FOREIGN KEY (id_doctor) REFERENCES doctores(id)
);

/*Inserción en la tabla de pacientes*/
INSERT INTO pacientes (id, dni, nombre, apellidos, genero, edad, direccion, telefono, passwd) 
VALUES 
  (1, '11111111A', 'María', 'García Pérez', 'M', 35, 'Calle Mayor, 1', '123456789', 'pass1234'),
  (2, '22222222B', 'Juan', 'González Sánchez', 'H', 27, 'Calle Sol, 5', '987654321', 'pass5678'),
  (3, '33333333C', 'Sofía', 'Martínez Ruiz', 'M', 42, 'Avenida de la Libertad, 10', '555666777', 'pass4321'),
  (4, '44444444D', 'Pablo', 'Gutiérrez Fernández', 'H', 19, 'Calle Santa Clara, 8', '777888999', 'pass8765'),
  (5, '55555555E', 'Lucía', 'López Sánchez', 'M', 56, 'Calle del Prado, 3', '111222333', 'pass1357');

/*Inserción en la tabla de doctores*/
INSERT INTO doctores (id, nombre, apellidos, genero, edad, especialidad, salario, horario_inicio, horario_fin, passwd) 
VALUES 
  (1, 'Pedro', 'García Martínez', 'H', 45, 'Cardiología', 50000, '09:00:00', '14:00:00', 'pass1234'),
  (2, 'Sara', 'González Ruiz', 'M', 32, 'Ginecología', 60000, '08:00:00', '13:00:00', 'pass5678'),
  (3, 'Javier', 'Martínez Sánchez', 'H', 38, 'Oncología', 55000, '10:00:00', '15:00:00', 'pass4321'),
  (4, 'Lucía', 'Gutiérrez Martínez', 'M', 50, 'Pediatría', 45000, '08:30:00', '13:30:00', 'pass8765'),
  (5, 'Alejandro', 'López García', 'H', 41, 'Dermatología', 52000, '11:00:00', '16:00:00', 'pass1357');


