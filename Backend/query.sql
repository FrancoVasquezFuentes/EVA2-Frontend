-- mysql
CREATE DATABASE ciisa_backend_v1_eva2_A;
CREATE USER 'ciisa_backend_v1_eva2_A'@'localhost' IDENTIFIED BY 'l4cl4v3-c11s4';
GRANT ALL PRIVILEGES ON ciisa_backend_v1_eva2_A . * TO 'ciisa_backend_v1_eva2_A'@'localhost';
FLUSH PRIVILEGES;

USE ciisa_backend_v1_eva2_A;

CREATE TABLE mantenimiento_info (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE, -- COSTRAINT
    texto TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO mantenimiento_info (id, nombre, texto, activo) VALUES 
(1, 'Mantenimiento', 'Conjunto de operaciones y cuidados necesarios para que tus instalaciones continúen funcionando correctamente.', true),
(2, 'Mantenimiento Preventivo', 'Ayuda a prolongar la vida útil de tus activos y aumenta la productividad, a través de una revisión.', true),
(3, 'Mantenimiento Correctivo', 'Corrige problemas o daños en las instalaciones o equipos.', true);

CREATE TABLE categoria_servicio (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE, -- COSTRAINT
    imagen TEXT NOT NULL,
    texto TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO categoria_servicio (id, nombre, imagen, texto, activo) VALUES 
(1, 'Aire Acondicionado', 'https://www.energyandwater.cl/gallery_gen/3cf1905198e9763be7cf8a3276a8375b_559x1125_79x0_771x1125_crop.jpg', 'Instalamos, reparamos y revisamos todo tipo de sistemas de aire acondicionado, tanto domésticos como industriales. Nos encargamos de la limpieza, el cambio de filtros, la recarga de gas y la detección y solución de averías.', true),
(2, 'Grupo Electrógeno', 'https://www.energyandwater.cl/gallery_gen/1dff1ecb88d5aa605aa877c96cd9df8b_640x1116_fit.jpg', 'Instalamos, reparamos y revisamos todo tipo de grupos electrógenos, tanto monofásicos como trifásicos. Nos encargamos del suministro de combustible, el cambio de aceite, el control de baterías y la verificación del funcionamiento correcto.', true),
(3, 'Sala de Calderas', 'https://www.energyandwater.cl/gallery_gen/9d0c9a5af9f0a48e29f3d3b61818457e_640x1176_fit.jpg', 'Instalamos, reparamos y revisamos todo tipo de calderas, tanto de gas como eléctricas. Nos encargamos del mantenimiento preventivo, la sustitución de piezas, la purga de radiadores y la regulación de la presión y la temperatura.', true),
(4, 'Sala de Bombas', 'https://www.energyandwater.cl/gallery_gen/6f174bfc2c2d263b250ab7bee3d6255f_640x1116_fit.jpg', 'Instalamos, reparamos y revisamos todo tipo de bombas hidráulicas, tanto centrífugas como periféricas. Nos encargamos del ajuste de válvulas, el cambio de sellos mecánicos, el equilibrado de rotores y la limpieza de impulsores.', true);

CREATE TABLE info_contacto (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE, -- COSTRAINT
    icono VARCHAR(20),
    texto TEXT NOT NULL,
    texto_adicional TEXT,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO info_contacto (id, nombre, texto, texto_adicional, activo) VALUES 
(1, 'email', 'contacto@energyandwater.cl', 'mailto:contacto@energyandwater.cl',true),
(2, 'direccion', 'Manquehue Sur 520, oficina 205, Las Condes', 'https://googlemaps',true),
(3, 'telefono', '+56 2 3256 9798', 'phone:+56232569798',true),
(4, 'whatsapp', '+56 9 3083 5203','https://api.whatsapp.com/send/?phone=%2B56930835203&text&type=phone_number&app_absent=0', true);

CREATE TABLE imagen (
    id INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE, -- COSTRAINT
    imagen TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO imagen (id, nombre, imagen, activo) VALUES 
(1, 'Reparación de Matriz  en CESFAM Padre Pierre Dubois, Pedro Aguirre Cerda.', 'https://www.energyandwater.cl/gallery_gen/c4efc61dd95fc5f46d5bcca4a4602205_fit.jpg', true),
(2, 'Universidad De Los Andes', 'https://www.energyandwater.cl/gallery_gen/5d11f12cdc2ed7cc9f331218cb5b9ca0_640x608_fit.jpg', true),
(3, 'Viña Cousiño Macul', 'https://www.energyandwater.cl/gallery_gen/bff51c94eebee5ca25ea6f9aeccc8491_640x608_fit.jpg', true);


CREATE TABLE historia (
    id INT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    texto TEXT,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO historia (id, tipo, texto, activo) VALUES 
(1, 'titulo', 'Creando ambientes gratos y confortables a través del mantenimiento.', true),
(2, 'subtitulo', 'Resumen', true),
(3, 'parrafo', 'Bienvenido a Energy and Water SpA,  empresa dedicada a la instalación y reparación de sistemas de ventilación, calefacción, electricidad y construcción. Contamos con un equipo de profesionales altamente cualificados y con amplia experiencia en el sector. Ofrecemos soluciones a medida para cada cliente, garantizando la calidad y la seguridad de nuestros servicios. Contacta con nosotros y solicita tu presupuesto sin compromiso.', true),
(4, 'imagen', '', true),
(5, 'subtitulo', 'En qué punto nos encontramos', true),
(6, 'parrafo', 'Somos una empresa fundada en 2018 con el objetivo de brindar servicios integrales de mantenimiento para hogares, oficinas, comercios e industrias. Nuestra misión es satisfacer las necesidades de nuestros clientes con eficiencia, rapidez y profesionalidad. Nuestra visión es ser la empresa referente en el mercado por nuestra excelencia y compromiso. Nuestros valores son la honestidad, la responsabilidad, el respeto y la innovación.', true),
(7, 'imagen', '', true);

CREATE TABLE historia_imagen (
    id INT PRIMARY KEY,
    historia_id INT NOT NULL,
    imagen_id INT NOT NULL,
    FOREIGN KEY (historia_id) REFERENCES historia (id),
    FOREIGN KEY (imagen_id) REFERENCES imagen (id)
);

INSERT INTO historia_imagen (id, historia_id, imagen_id) VALUES 
(1, 4, 1), 
(2, 7, 2),
(3, 7, 3);

CREATE TABLE equipo (
    id INT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    texto TEXT,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO equipo (id, tipo, texto, activo) VALUES 
(1, 'titulo', 'Compartimos una visión clara del objetivo, fomentamos un clima de confianza, colaboración y apoyo mutuo entre los integrantes del equipo.', true),
(2, 'subtitulo', 'Liderazgo', true),
(3, 'parrafo', 'Mizraim Abello es el fundador de Energy and Water SpA, es un líder colaborativo que se desempeña en el ámbito laboral, específicamente en el sector de la climatización y electricidad. Practica un liderazgo democrático; es decir, que involucra al equipo de trabajo en la toma de decisiones y valora sus opiniones y sugerencias. Sus principales habilidades como líder son la resolución de problemas, la comunicación efectiva, el pensamiento crítico, la capacidad de coordinación y gestión. Algunos de los logros más destacados son haber participado en la acreditación de los centros de salud de la municipalidad de San Joaquín, reparación del equipo de climatización para el museo de la Universidad de los Andes y haber realizado reparaciones y cambios de grupos electrógenos que son críticos para el funcionamiento de los edificios. Uno de los desafíos que enfrentó como líder fue adaptarse a las nuevas normativas sanitarias por la pandemia y garantizar la seguridad del equipo y los clientes. Lo logró implementando protocolos de prevención y capacitando al personal. Su objetivo como líder es darle continuidad operacional a los clientes y mejorar constantemente los procesos de mantenimiento y calidad.', true),
(4, 'imagen', '', true),
(5, 'subtitulo', 'Este es nuestro equipo', true),
(6, 'parrafo', 'En Energy And Water SpA nos dedicamos a crear ambientes gratos y confortables a través del mantenimiento para calderas, aire acondicionado, salas de bombas, grupos electrógenos y electricidad, entre otros.', true),
(7, 'imagen', '', true);

INSERT INTO imagen (id, nombre, imagen, activo) VALUES 
(4, 'Mantenimiento para edificios', 'https://www.energyandwater.cl/gallery_gen/0b901c86ff523e2ff3dcc5c6fff6e437_fit.jpg', true),
(5, 'El servicio nos mueve', 'https://www.energyandwater.cl/gallery_gen/9fca2b80ef03973c6c70faaa8fa3a5a5_fit.jpg', true);

CREATE TABLE equipo_imagen (
    id INT PRIMARY KEY,
    equipo_id INT NOT NULL,
    imagen_id INT NOT NULL,
    FOREIGN KEY (equipo_id) REFERENCES equipo (id),
    FOREIGN KEY (imagen_id) REFERENCES imagen (id)
);

INSERT INTO equipo_imagen (id, equipo_id, imagen_id) VALUES 
(1, 4, 4), 
(2, 7, 5);

CREATE TABLE pregunta_frecuente (
    id INT PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE    
);

INSERT INTO pregunta_frecuente (id, pregunta, respuesta, activo) VALUES 
(1, '¿Cómo solicito una cotización?', 'Puedes contactarnos al correo contacto@energyandwater.cl o al +56232569798.',true),
(2, '¿Necesitas visita técnica?', 'Puedes solicitar visita técnica para evaluación mediante correo electrónico Contacto@energyandwater.cl o al +56232569798.',true),
(3, '¿Qué tipo de aire acondicionado pueden revisar?', 'Revisamos, reparamos e instalamos equipos domiciliarios e industriales.',true);