DELIMITER //
CREATE TRIGGER tr_insertar_doctor
AFTER INSERT ON doctores
FOR EACH ROW
BEGIN
  INSERT INTO usuarios (dni, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
  VALUES (NEW.dni, CONCAT(NEW.nombre, ' ', NEW.apellidos), NEW.passwd, 'doctor', NEW.genero, NULL, NEW.dni);
END;
