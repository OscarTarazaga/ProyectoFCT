DELIMITER //
CREATE TRIGGER tr_insertar_doctor
AFTER INSERT ON doctores
FOR EACH ROW
BEGIN
  INSERT INTO usuarios (dni, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
  VALUES (NEW.dni, CONCAT(NEW.nombre, ' ', NEW.apellidos), NEW.passwd, 'doctor', NEW.genero, NULL, NEW.dni);
END; 
//

DELIMITER //
CREATE TRIGGER tr_actualizar_paciente
AFTER UPDATE ON pacientes
FOR EACH ROW
BEGIN
  UPDATE usuarios
  SET nombre = CONCAT(NEW.nombre, ' ', NEW.apellidos),
      passwd = NEW.passwd,
      tipo = 'paciente',
      genero = NEW.genero,
      dni_paciente = NEW.dni,
      dni_doctor = NEW.dni_doctor
  WHERE dni_paciente = OLD.dni;
END;

DELIMITER //
CREATE TRIGGER insertar_usuario AFTER INSERT ON pacientes
FOR EACH ROW
BEGIN
    INSERT INTO usuarios (dni, dni_administrador, nombre, passwd, tipo, genero, dni_paciente, dni_doctor)
    VALUES (NEW.dni, NULL, NEW.nombre, NEW.passwd, 'paciente', NEW.genero, NEW.dni, NEW.dni_doctor);
END //

DELIMITER ;
