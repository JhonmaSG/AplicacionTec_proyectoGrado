create database IF NOT EXISTS proyecto_grado_ud;
use proyecto_grado_ud;
-- drop database proyecto_grado_ud;

-- -----------------------------------------------------------------------------------

-- Tabla de roles
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    nid INT PRIMARY KEY NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    pregunta VARCHAR(50) NOT NULL,
    respuesta VARCHAR(50) NOT NULL,
    ult_conexion DATETIME NULL,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id_rol) ON DELETE CASCADE
);


INSERT INTO roles (nombre_rol) VALUES ('Administrador'), ('Usuario Corriente');

INSERT INTO `usuarios` (`nid`, `nombres`, `apellidos`, `email`, `password`, `pregunta`, `respuesta`, `ult_conexion`, `rol_id`) VALUES
(1, 'Admin', 'Prueba', 'admin@correo.com', '$2y$10$BKqnYd5Xf5DP2uZirQ8ftOu5mP1uafVBiAo2KPqic4z5uCmPqWA0y', 'Color favorito', 'Azul', '2025-02-18 17:02:31', 1),
(1013670411, 'jhon', 'serrano', 'jmserranog@udistrital.edu.co', '$2y$10$dOvIGtWg0OzsbKyLac7/QuQMJEr40CybPMM4Zr9JDcOSio7yhqZEC', 'Color favorito', 'azul', '2025-03-09 22:57:43', 2);

-- -----------------------------------------------------------------------------------

CREATE TABLE Verbo (
    Id_verbo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE Area (
    Id_area INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE Carrera (
    id_carrera INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE Materia (
    Id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre_materia VARCHAR(100) NOT NULL,
    id_area_materia INT,
    id_carrera INT NULL,
    creditos INT NOT NULL,
    semestre VARCHAR(2),
    FOREIGN KEY (id_area_materia) REFERENCES Area(Id_area) ON DELETE SET NULL,
    FOREIGN KEY (id_carrera) REFERENCES Carrera(id_carrera) ON DELETE SET NULL
);


CREATE TABLE Datos (
    Id_dato_materia INT AUTO_INCREMENT PRIMARY KEY,
    periodo VARCHAR(6),
    id_materia_d INT,
    inscritos INT,
    reprobados INT, 
    tasa_reprobacion double,
    FOREIGN KEY (id_materia_d) REFERENCES Materia(Id_materia) ON DELETE SET NULL
);

CREATE TABLE MateriaVerbo (
    id_materia INT,
    id_verbo INT,
    PRIMARY KEY (id_materia, id_verbo),
    FOREIGN KEY (id_materia) REFERENCES Materia(Id_materia) ON DELETE CASCADE,
    FOREIGN KEY (id_verbo) REFERENCES Verbo(Id_verbo) ON DELETE CASCADE
);

-- TRIGGGERS--

DELIMITER //

CREATE TRIGGER calcular_tasa_reprobacion
BEFORE INSERT ON Datos
FOR EACH ROW
BEGIN
    IF NEW.inscritos = 0 THEN
        SET NEW.tasa_reprobacion = 0.00;
    ELSE
        SET NEW.tasa_reprobacion = ROUND((NEW.reprobados / NEW.inscritos) * 100, 2);
    END IF;
END;
//

DELIMITER ;

-- DROP PROCEDURE  InsertarDatosMateria
-- -------------------------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE InsertarDatosMateria(
    IN p_periodo VARCHAR(6),
    IN p_nombre_materia_d VARCHAR(100), -- Ajustado a la longitud de la columna en Materia
    IN p_inscritos INT,
    IN p_reprobados INT
)
BEGIN
    DECLARE materia_id INT;
    DECLARE existe INT;

    -- Verificar que inscritos y reprobados sean valores válidos
    IF p_inscritos < 0 OR p_reprobados < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Los valores de inscritos y reprobados no pueden ser negativos.';
    END IF;

    -- Obtener el ID de la materia
    SELECT Id_materia INTO materia_id 
    FROM Materia
    WHERE nombre_materia = p_nombre_materia_d
    LIMIT 1;

    -- Validar que la materia exista
    IF materia_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La materia especificada no existe.';
    END IF;

    -- Verificar si ya existe un registro para el mismo periodo y materia
    SELECT COUNT(*) INTO existe 
    FROM Datos 
    WHERE periodo = p_periodo AND id_materia_d = materia_id;

    IF existe > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Ya existe un registro con la misma materia y periodo.';
    ELSE
        -- Insertar los datos en la tabla Datos
        INSERT INTO Datos (periodo, id_materia_d, inscritos, reprobados)
        VALUES (p_periodo, materia_id, p_inscritos, p_reprobados);
    END IF;
END $$

DELIMITER ;


-- ---------------------------------------------------------------------------------------------
-- Vistas ---

CREATE OR REPLACE VIEW vista_materias_ordenadas AS
	SELECT 
		m.Id_materia, 
		m.id_area_materia,
		m.nombre_materia, 
		a.Nombre AS nombre_area, 
		m.semestre,
        c.id_carrera,
        c.nombre AS nombre_carrera,
        d.periodo,
		d.inscritos, 
		d.reprobados,
		d.tasa_reprobacion
	FROM Materia m
	LEFT JOIN Area a ON m.id_area_materia = a.Id_area
    LEFT JOIN Carrera c ON m.id_carrera = c.id_carrera
	LEFT JOIN Datos d ON d.id_materia_d = m.Id_materia
    WHERE d.inscritos > 0
	ORDER BY 
		m.Id_materia ASC, 
		SUBSTRING_INDEX(d.periodo, '-', 1) ASC, 
		SUBSTRING_INDEX(d.periodo, '-', -1) ASC;
-- ---------------------------------------------------------------------------------------
-- INSERT INTO

INSERT INTO Area(nombre, descripcion) VALUES
('Ciencias Básicas',''),
('Humanidades',''),
('Humanidades - Electiva',''),
('Económico Administrativa',''),
('Profesional Básica',''),
('Profesional Complementario',''),
('Componente propedéutico',''),
('Segunda Lengua','');

INSERT INTO Carrera(nombre, descripcion) VALUES 
('TSD','Tegnologo en Sistematización de Datos'),
('IT','Ingenieria en Telematica');

INSERT INTO Materia (nombre_materia, id_area_materia, id_carrera, creditos, semestre) VALUES
('Algebra Lineal', 1, 1, 3, 1),
('Cálculo Diferencial', 1, 1, 4, 1),
('Cátedra Democracia y Ciudadania', 2, 1, 1, 1),
('Cátedra Francisco José de Caldas', 2, 1, 1, 1),
('Introducción a Algoritmos', 5, 1, 3, 1),
('Lógica Matemática', 1, 1, 3, 1),
('Producción y Comprensión de Textos I', 2, 1, 3, 1),
('Administración', 4, 1, 3, 2),
('Cálculo Integral', 1, 1, 3, 2),
('Cátedra de Contexto', 2, 1, 1, 2),
('Estructura de Datos', 5, 1, 3, 2),
('Física I: Mecánica Newtoniana', 1, 1, 3, 2),
('Producción y Compresión de Textos II', 2, 1, 2, 2),
('Programación Orientada a Objetos', 5, 1, 3, 2),
('Bases de Datos', 5, 1, 3, 3),
('Ciencia, Tecnologia y Sociedad', 2, 1, 2, 3),
('Contabilidad General', 4, 1, 2, 3),
('Física II: Electromagnetismo', 1, 1, 2, 3),
('Fundamentos de Organizaciones', 4, 1, 3, 3),
('Programación Multinivel', 5, 1, 3, 3),
('Segunda Lengua I - Inglés', 8, 1, 2, 3),
('Diseño Lógico', 5, 1, 3, 4),
('Matemáticas Especiales', 1, 1, 3, 4),
('Análisis y métodos numéricos', NULL, 1, 3, 4),
('Fundamentos de Economía', 4, 1, 2, 4),
('Tics en las Organizaciones', 2, 1, 2, 4),
('Ética y Sociedad', 2, 1, 2, 4),
('Programación Avanzada', 5, 1, 3, 4),
('Segunda Lengua II - Inglés', 8, 1, 2, 4),
('Análisis de Sistemas', 5, 1, 3, 5),
('Transmisión de Datos', NULL, 1, 3, 5),
('Programación Web', NULL, 1, 3, 5),
('Aplicaciones para Internet', NULL, 1, 3, 5),
('Bases de Datos Distribuidas', NULL, 1, 3, 5),
('Analisis Social Colombiano', NULL, 1, 2, 5),
('Sistemas Operacionales', 5, 1, 3, 5),
('Taller de Investigación', NULL, 1, 2, 5),
('Arquitectura de Computadores', 5, 1, 2, 6),
('Fundamentos de telemática', NULL, 1, 3, 6),
('Protocolos de Comunicación', NULL, 1, 3, 6),
('Programación por Componentes', NULL, 1, 3, 6),
('Regulación para telecomunicaciones', NULL, 1, 3, 6),
('Inteligencia Artificial', NULL, 1, 3, 6),
('Segunda Lengua III - Inglés', 8, 1, 2, 6),
('Trabajo de Grado Tecnológico', NULL, 1, 2, 6),
('Ecuaciones Diferenciales', 7, 1, 3, NULL),
('Bases de Datos Avanzadas', 7, 1, 3, NULL),
('Ingeniería de Software', 7, 1, 3, NULL),
('Socio Humanística', NULL, 2, 2, 7),
('Cálculo Multivariado', NULL, 2, 3, 7),
('Ingenieria Económica', NULL, 2, 3, 7),
('Probabilidad y Estadística', NULL, 2, 3, 7),
('Sistemas Distribuidos', NULL, 2, 3, 7),
('Teoría General de Sistemas', NULL, 2, 2, 7),
('Física III: Ondas y Física Moderna', NULL, 2, 3, 8),
('Formulación y Evaluación de Proyectos', NULL, 2, 3, 8),
('Análisis de Fourier', NULL, 2, 3, 8),
('Redes Corporativas', NULL, 2, 3, 8),
('Sistemas Abiertos', NULL, 2, 3, 8),
('Teoría de la Informatica', NULL, 2, 3, 8),
('Computación Cuántica', NULL, 2, 3, 9),
('Simulación de Sistemas Dinámicos', NULL, 2, 3, 9),
('Criptología', NULL, 2, 3, 9),
('Investigación de Operaciones', NULL, 2, 3, 9),
('Trabajo de Grado I', NULL, 2, 2, 9),
('Planificación y Diseño de Redes', NULL, 2, 3, 9),
('Redes de Alta Velocidad', NULL, 2, 3, 9),
('Seguridad en Redes', NULL, 2, 3, 9),
('Económico-Administrativa', NULL, 2, 2, 10),
('Análisis de Datos', NULL, 2, 3, 10),
('Bioinformática', NULL, 2, 3, 10),
('Seminario de Telemática', NULL, 2, 3, 10),
('Redes inalámbricas', NULL, 2, 3, 10),
('Trabajo de Grado II', NULL, 2, 2, 10),
('Gerencia y Auditoría en Redes', NULL, 2, 3, 10),
('Segunda Lengua I - Francés', 8, 1, 2, 3),
('Segunda Lengua I - Alemán', 8, 1, 2, 3),
('Segunda Lengua I - Italiano', 8, 1, 2, 3),
('Segunda Lengua I - Portugués', 8, 1, 2, 3),
('Segunda Lengua I - Mandarín', 8, 1, 2, 3),
('Segunda Lengua II - Francés', 8, 1, 2, 4),
('Segunda Lengua II - Alemán', 8, 1, 2, 4),
('Segunda Lengua II - Italiano', 8, 1, 2, 4),
('Segunda Lengua II - Portugués', 8, 1, 2, 4),
('Segunda Lengua II - Mandarín', 8, 1, 2, 4),
('Segunda Lengua III - Francés', 8, 1, 2, 6),
('Segunda Lengua III - Alemán', 8, 1, 2, 6),
('Segunda Lengua III - Italiano', 8, 1, 2, 6),
('Segunda Lengua III - Portugués', 8, 1, 2, 6),
('Segunda Lengua III - Mandarín', 8, 1, 2, 6);


INSERT INTO datos (id_materia_d, periodo, inscritos, reprobados) VALUES
(1, "2020-1", 171, 53),(1, "2020-3", 156, 49),(1, "2021-1", 145, 50),
(1, "2021-3", 158, 78),(1, "2022-1", 166, 79),(1, "2022-3", 154, 96),
(1, "2023-1", 211, 123),(1, "2023-3", 203, 90),(1, "2024-1", 224, 116),
(1, "2024-3", 230, 100),

(2, "2020-1", 180, 36),(2, "2020-3", 149, 86),(2, "2021-1", 138, 53),
(2, "2021-3", 166, 98),(2, "2022-1", 176, 101),(2, "2022-3", 175, 118),
(2, "2023-1", 225, 136),(2, "2023-3", 192, 178),(2, "2024-1", 216, 81),

(3, "2020-1", 122, 12),(3, "2020-3", 126, 27),(3, "2021-1", 108, 13),
(3, "2021-3", 134, 35),(3, "2022-1", 135, 27),(3, "2022-3", 117, 36),
(3, "2023-1", 161, 35),(3, "2023-3", 130, 28),(3, "2024-1", 180, 38),

(4, "2020-1", 126, 18),(4, "2020-3", 135, 25),(4, "2021-1", 116, 24),
(4, "2021-3", 138, 37),(4, "2022-1", 129, 33),(4, "2022-3", 121, 37),
(4, "2023-1", 158, 30),(4, "2023-3", 132, 37),(4, "2024-1", 186, 36),

(5, '2020-1', 133, 38),(5, '2020-3', 141, 53),(5, '2021-1', 145, 62),
(5, '2021-3', 179, 94),(5, '2022-1', 179, 71),(5, '2022-3', 147, 73),
(5, '2023-1', 184, 84),(5, '2023-3', 175, 82),(5, '2024-1', 219, 81),

(6, '2020-1', 127, 19),(6, '2020-3', 134, 45),(6, '2021-1', 128, 34),
(6, '2021-3', 151, 55),(6, '2022-1', 146, 46),(6, '2022-3', 127, 51),
(6, '2023-1', 169, 52),(6, '2023-3', 148, 52),(6, '2024-1', 202, 41),

(7, "2020-1", 139, 21),(7, "2020-3", 133, 21),(7, "2021-1", 117, 30),
(7, "2021-3", 147, 46),(7, "2022-1", 137, 43),(7, "2022-3", 128, 31),
(7, "2023-1", 155, 53),(7, "2023-3", 151, 32),(7, "2024-1", 186, 33),

(8, '2020-1', 113, 15),(8, '2020-3', 123, 19),(8, '2021-1', 101, 23),
(8, '2021-3', 97, 33),(8, '2022-1', 100, 33),(8, '2022-3', 111, 18),
(8, '2023-1', 91, 15),(8, '2023-3', 126, 35),(8, '2024-1', 120, 35),

(9, "2020-1", 91, 7),(9, "2020-3", 142, 21),(9, "2021-1", 114, 30),
(9, "2021-3", 118, 61),(9, "2022-1", 110, 65),(9, "2022-3", 120, 58),
(9, "2023-1", 104, 58),(9, "2023-3", 166, 91),(9, "2024-1", 180, 95),

(10, '2020-1', 86, 1),(10, '2020-3', 117, 4),(10, '2021-1', 104, 4),
(10, '2021-3', 82, 6),(10, '2022-1', 76, 11),(10, '2022-3', 86, 5),
(10, '2023-1', 73, 8),(10, '2023-3', 90, 9),(10, '2024-1', 82, 5),

(11, '2020-1', 102, 10),(11, '2020-3', 110, 24),(11, '2021-1', 111, 17),
(11, '2021-3', 179, 94),(11, '2022-1', 179, 71),(11, '2022-3', 147, 73),
(11, '2023-1', 184, 84),(11, '2023-3', 175, 82),(11, '2024-1', 219, 81),

(12, "2020-1", 180, 36),(12, "2020-3", 124, 40),(12, "2021-1", 133, 39),
(12, "2021-3", 218, 48),(12, "2022-1", 113, 52),(12, "2022-3", 86, 40),
(12, "2023-1", 77, 16),(12, "2023-3", 106, 20),(12, "2024-1", 120, 38),

(13, "2020-1", 105, 14),(13, "2020-3", 123, 17),(13, "2021-1", 109, 26),
(13, "2021-3", 98, 23),(13, "2022-1", 101, 27),(13, "2022-3", 100, 19),
(13, "2023-1", 99, 16),(13, "2023-3", 110, 19),(13, "2024-1", 104, 21),

(16, '2020-1', 79, 6),(16, '2020-3', 89, 7),(16, '2021-1', 106, 14),
(16, '2021-3', 101, 25),(16, '2022-1', 88, 17),(16, '2022-3', 81, 18),
(16, '2023-1', 101, 15),(16, '2023-3', 80, 14),(16, '2024-1', 86, 12),

(17, '2020-1', 59, 5),(17, '2020-3', 57, 3),(17, '2021-1', 32, 7),
(17, '2021-3', 42, 6),(17, '2022-1', 37, 5),(17, '2022-3', 23, 4),
(17, '2023-1', 50, 14),(17, '2023-3', 38, 7),(17, '2024-1', 42, 13),

(18, "2020-1", 61, 1),(18, "2020-3", 55, 4),(18, "2021-1", 82, 9),
(18, "2021-3", 67, 16),(18, "2022-1", 53, 19),(18, "2022-3", 62, 19),
(18, "2023-1", 78, 17),(18, "2023-3", 73, 18),(18, "2024-1", 72, 37),

(19, '2020-1', 55, 2),(19, '2020-3', 49, 1),(19, '2021-1', 55, 7),
(19, '2021-3', 61, 9),(19, '2022-1', 47, 8),(19, '2022-3', 51, 5),
(19, '2023-1', 49, 7),(19, '2023-3', 46, 8),(19, '2024-1', 50, 8),

(23, '2020-1', 17, 1),(23, '2020-3', 14, 0),(23, '2021-1', 8, 0),
(23, '2021-3', 18, 3),(23, '2022-1', 11, 5),(23, '2022-3', 16, 8),
(23, '2023-1', 16, 4),(23, '2023-3', 11, 3),(23, '2024-1', 12, 3),

(25, '2020-1', 22, 1),(25, '2020-3', 25, 0),(25, '2021-1', 25, 2),
(25, '2021-3', 17, 4),(25, '2022-1', 15, 9),(25, '2022-3', 16, 3),
(25, '2023-1', 18, 3),(25, '2023-3', 16, 4),(25, '2024-1', 14, 2),

(26, '2020-1', 55, 4),(26, '2020-3', 43, 3),(26, '2021-1', 56, 5),
(26, '2021-3', 48, 12),(26, '2022-1', 45, 4),(26, '2022-3', 61, 5),
(26, '2023-1', 51, 7),(26, '2023-3', 45, 4),(26, '2024-1', 37, 8),

(27, '2020-1', 83, 1),(27, '2020-3', 84, 6),(27, '2021-1', 83, 10),
(27, '2021-3', 100, 14),(27, '2022-1', 87, 8),(27, '2022-3', 61, 8),
(27, '2023-1', 79, 9),(27, '2023-3', 87, 13),(27, '2024-1', 77, 14);