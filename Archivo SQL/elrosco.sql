SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos 'elrosco'
CREATE DATABASE IF NOT EXISTS `elrosco` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `elrosco`;

-- Eliminación de tablas si ya existen para evitar conflictos
DROP TABLE IF EXISTS `HISTORIAL`;
DROP TABLE IF EXISTS `ROSCO_PREGUNTA`;
DROP TABLE IF EXISTS `PREGUNTA`;
DROP TABLE IF EXISTS `PARTIDA_USUARIO`;
DROP TABLE IF EXISTS `ROSCO`;
DROP TABLE IF EXISTS `PARTIDA`;
DROP TABLE IF EXISTS `USUARIO`;

-- Tabla USUARIO
CREATE TABLE `USUARIO` (
  `idUsuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` VARCHAR(50) NOT NULL UNIQUE,
  `correoUsuario` VARCHAR(100),
  `fechaNacimiento` DATE NOT NULL,
  `contrasenia` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla PARTIDA
CREATE TABLE `PARTIDA` (
  `idPartida` INT(11) NOT NULL AUTO_INCREMENT,
  `ganador` INT(11),
  `tiempo` TIME NOT NULL,
  `dificultadPartida` ENUM('baja', 'media', 'alta') NOT NULL,
  `ayudaAdicional` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`idPartida`),
  CONSTRAINT `fk_ganador` FOREIGN KEY (`ganador`) 
    REFERENCES `USUARIO` (`idUsuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla ROSCO
CREATE TABLE `ROSCO` (
  `idRosco` INT(11) NOT NULL AUTO_INCREMENT,
  `estadoRosco` ENUM('completo', 'incompleto') NOT NULL,
  PRIMARY KEY (`idRosco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla PARTIDA_USUARIO
CREATE TABLE `PARTIDA_USUARIO` (
  `idPartida` INT(11) NOT NULL,
  `idUsuario` INT(11) NOT NULL,
  `puntaje` INT(11) NOT NULL DEFAULT 0,
  `tiempoRestante` TIME NOT NULL,
  `idRosco` INT(11) NOT NULL,
  PRIMARY KEY (`idPartida`, `idUsuario`),
  CONSTRAINT `fk_partida` FOREIGN KEY (`idPartida`) 
    REFERENCES `PARTIDA` (`idPartida`) ON DELETE CASCADE,
  CONSTRAINT `fk_usuario` FOREIGN KEY (`idUsuario`) 
    REFERENCES `USUARIO` (`idUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_rosco` FOREIGN KEY (`idRosco`) 
    REFERENCES `ROSCO` (`idRosco`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla PREGUNTA
CREATE TABLE `PREGUNTA` (
  `idPregunta` INT(11) NOT NULL AUTO_INCREMENT,
  `letra` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `palabra` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dificultadPregunta` ENUM('baja', 'media', 'alta') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idPregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- utf8mb4_bin para distinguir N de Ń

-- Tabla ROSCO_PREGUNTA
CREATE TABLE `ROSCO_PREGUNTA` (
  `idRosco` INT(11) NOT NULL,
  `idPregunta` INT(11) NOT NULL,
  `estadoRespuesta` ENUM('correcto', 'incorrecto', 'sinResponder') NOT NULL,
  PRIMARY KEY (`idRosco`, `idPregunta`),
  CONSTRAINT `fk_rosco_pregunta_rosco` FOREIGN KEY (`idRosco`) 
    REFERENCES `ROSCO` (`idRosco`) ON DELETE CASCADE,
  CONSTRAINT `fk_rosco_pregunta_pregunta` FOREIGN KEY (`idPregunta`) 
    REFERENCES `PREGUNTA` (`idPregunta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla HISTORIAL
CREATE TABLE `HISTORIAL` (
  `idUsuario1` INT(11) NOT NULL,
  `idUsuario2` INT(11) NOT NULL,
  `victoriasJugador1` INT(11) DEFAULT 0,
  `victoriasJugador2` INT(11) DEFAULT 0,
  PRIMARY KEY (`idUsuario1`, `idUsuario2`),
  CONSTRAINT `fk_historial_usuario1` FOREIGN KEY (`idUsuario1`) 
    REFERENCES `USUARIO` (`idUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_historial_usuario2` FOREIGN KEY (`idUsuario2`) 
    REFERENCES `USUARIO` (`idUsuario`) ON DELETE CASCADE,
  UNIQUE (`idUsuario1`, `idUsuario2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Inserción de palabras nivel BAJA
INSERT INTO `PREGUNTA` (`letra`, `palabra`, `descripcion`, `dificultadPregunta`) VALUES
('A', 'agua', 'Liquido esencial para la vida.', 'baja'),
('A', 'arbol', 'Planta de tronco leñoso.', 'baja'),
('A', 'amigo', 'Persona con la que se tiene amistad.', 'baja'),
('B', 'barco', 'Medio de transporte maritimo.', 'baja'),
('B', 'bota', 'Calzado que cubre el pie.', 'baja'),
('B', 'blanco', 'Color sin pigmentacion.', 'baja'),
('C', 'casa', 'Lugar donde vive una persona.', 'baja'),
('C', 'cama', 'Mueble para dormir.', 'baja'),
('C', 'coche', 'Vehiculo de cuatro ruedas.', 'baja'),
('D', 'dedo', 'Extremidad de la mano.', 'baja'),
('D', 'diente', 'Parte dura en la boca.', 'baja'),
('D', 'danza', 'Movimiento rítmico del cuerpo.', 'baja'),
('E', 'elefante', 'Animal terrestre de gran tamaño.', 'baja'),
('E', 'ensalada', 'Mezcla de vegetales crudos.', 'baja'),
('E', 'estrella', 'Cuerpo celeste brillante.', 'baja'),
('F', 'flor', 'Parte de la planta que produce semillas.', 'baja'),
('F', 'fuego', 'Reaccion quimica que produce calor.', 'baja'),
('F', 'feliz', 'Estado de satisfacción y alegría.', 'baja'),
('G', 'gato', 'Animal domestico.', 'baja'),
('G', 'gallo', 'Ave macho de la gallina.', 'baja'),
('G', 'gol', 'Accion de anotar en un deporte.', 'baja'),
('H', 'hoja', 'Parte plana de una planta.', 'baja'),
('H', 'hombre', 'Persona de sexo masculino.', 'baja'),
('H', 'helado', 'Postre congelado dulce.', 'baja'),
('I', 'isla', 'Porción de tierra rodeada de agua.', 'baja'),
('I', 'iglesia', 'Lugar de culto religioso.', 'baja'),
('I', 'idea', 'Pensamiento o concepto.', 'baja'),
('J', 'judo', 'Arte marcial japonés.', 'baja'),
('J', 'jamon', 'Carne de cerdo curada.', 'baja'),
('J', 'jardin', 'Terreno cultivado con plantas.', 'baja'),
('K', 'kilo', 'Unidad de medida de masa.', 'baja'),
('K', 'kayak', 'Embarcacion ligera de remo.', 'baja'),
('K', 'kermes', 'Feria o fiesta popular.', 'baja'),
('L', 'luna', 'Satélite natural de la Tierra.', 'baja'),
('L', 'libro', 'Conjunto de hojas de papel encuadernadas.', 'baja'),
('L', 'luz', 'Radiación que permite ver.', 'baja'),
('M', 'mesa', 'Mueble con superficie plana.', 'baja'),
('M', 'mano', 'Extremidad del cuerpo humano.', 'baja'),
('M', 'mar', 'Gran masa de agua salada.', 'baja'),
('N', 'nido', 'Habitación de las aves.', 'baja'),
('N', 'nube', 'Partícula de vapor de agua en el aire.', 'baja'),
('N', 'niño', 'Persona joven.', 'baja'),
('Ñ', 'ñandu', 'Ave similar a un avestruz.', 'baja'),
('Ñ', 'ñoño', 'Persona que se comporta de manera exageradamente cariñosa.', 'baja'),
('Ñ', 'añafil', 'Instrumento musical de viento.', 'baja'),
('O', 'oro', 'Metal precioso de color amarillo.', 'baja'),
('O', 'ola', 'Movimiento del agua en el mar.', 'baja'),
('O', 'oso', 'Animal mamífero de gran tamaño.', 'baja'),
('P', 'perro', 'Animal doméstico conocido como el mejor amigo del hombre.', 'baja'),
('P', 'pollo', 'Ave joven, especialmente la que se cría para la carne.', 'baja'),
('P', 'papel', 'Material hecho de pulpa de madera.', 'baja'),
('Q', 'queso', 'Producto lácteo obtenido de la leche.', 'baja'),
('Q', 'quien', 'Pronombre interrogativo.', 'baja'),
('Q', 'qatar', 'País árabe.', 'baja'),
('R', 'rosa', 'Planta ornamental conocida por sus flores.', 'baja'),
('R', 'raton', 'Roedor pequeño.', 'baja'),
('R', 'reloj', 'Instrumento para medir el tiempo.', 'baja'),
('S', 'sol', 'Estrella que ilumina la Tierra.', 'baja'),
('S', 'sala', 'Espacio cerrado en una casa o edificio.', 'baja'),
('S', 'salto', 'Acción de elevarse del suelo.', 'baja'),
('T', 'tapa', 'Cubierta de un recipiente.', 'baja'),
('T', 'tarta', 'Pastel o dulce que se sirve en ocasiones especiales.', 'baja'),
('T', 'tren', 'Medio de transporte sobre rieles.', 'baja'),
('U', 'uva', 'Fruto de la vid.', 'baja'),
('U', 'uso', 'Acción de utilizar algo.', 'baja'),
('U', 'uno', 'Número cardinal.', 'baja'),
('V', 'vaca', 'Animal de pastoreo.', 'baja'),
('V', 'velo', 'Tejido ligero.', 'baja'),
('V', 'vino', 'Bebida alcohólica hecha de uvas.', 'baja'),
('W', 'web', 'Conjunto de páginas en Internet.', 'baja'),
('W', 'whisky', 'Bebida alcohólica destilada.', 'baja'),
('W', 'wolverine', 'Animal conocido como glotón.', 'baja'),
('X', 'xilofono', 'Instrumento musical de percusión.', 'baja'),
('X', 'xeno', 'Prefijo que indica extraño o ajeno.', 'baja'),
('X', 'xerox', 'Marca registrada de fotocopiadoras.', 'baja'),
('Y', 'yate', 'Embarcación de recreo.', 'baja'),
('Y', 'yogur', 'Producto lácteo fermentado.', 'baja'),
('Y', 'yoyo', 'Juguete que sube y baja con una cuerda.', 'baja'),
('Z', 'zapato', 'Calzado para los pies.', 'baja'),
('Z', 'zebra', 'Animal herbívoro de rayas.', 'baja'),
('Z', 'zorro', 'Mamífero conocido por su astucia.', 'baja');

-- Inserción de palabras nivel MEDIA
INSERT INTO `PREGUNTA` (`letra`, `palabra`, `descripcion`, `dificultadPregunta`) VALUES
('A', 'abogado', 'Profesional del derecho.', 'media'),
('A', 'ardilla', 'Roedor ágil que vive en los árboles.', 'media'),
('A', 'atajo', 'Camino más corto para llegar a un lugar.', 'media'),
('B', 'balanza', 'Instrumento para medir peso.', 'media'),
('B', 'barco', 'Embarcación para navegar.', 'media'),
('B', 'bulbo', 'Parte de algunas plantas.', 'media'),
('C', 'camara', 'Dispositivo para tomar fotos.', 'media'),
('C', 'colmena', 'Casa de las abejas.', 'media'),
('C', 'cuaderno', 'Libro para tomar notas.', 'media'),
('D', 'delfin', 'Mamífero marino inteligente.', 'media'),
('D', 'diamante', 'Piedra preciosa.', 'media'),
('D', 'dromedario', 'Animal del desierto con una joroba.', 'media'),
('E', 'escuadra', 'Herramienta de medición en ángulo recto.', 'media'),
('E', 'escalera', 'Estructura para subir o bajar.', 'media'),
('E', 'estanteria', 'Mueble con estantes.', 'media'),
('F', 'fanfarria', 'Sonido de trompetas para anunciar algo.', 'media'),
('F', 'fiesta', 'Celebración o evento social.', 'media'),
('F', 'ferrocarril', 'Transporte sobre rieles.', 'media'),
('G', 'guitarra', 'Instrumento musical de cuerdas.', 'media'),
('G', 'guerrero', 'Persona que combate en una guerra.', 'media'),
('G', 'goloso', 'Persona a la que le gusta mucho comer.', 'media'),
('H', 'hazaña', 'Hecho notable o heroico.', 'media'),
('H', 'hierba', 'Planta pequeña, generalmente de hoja estrecha.', 'media'),
('H', 'huracan', 'Viento fuerte y destructivo.', 'media'),
('I', 'iguana', 'Reptil grande y herbívoro.', 'media'),
('I', 'imán', 'Objeto que atrae metales ferrosos.', 'media'),
('I', 'imagen', 'Representación visual de algo.', 'media'),
('J', 'joya', 'Objeto de adorno hecho con metales y piedras preciosas.', 'media'),
('J', 'jengibre', 'Raíz utilizada como especia o medicina.', 'media'),
('J', 'jamon', 'Carne de cerdo curada.', 'media'),
('K', 'kilómetro', 'Unidad de medida de distancia.', 'media'),
('K', 'karaoke', 'Actividad de cantar con música grabada.', 'media'),
('K', 'koala', 'Marsupial que vive en Australia.', 'media'),
('L', 'lago', 'Cuerpo de agua rodeado de tierra.', 'media'),
('L', 'limon', 'Fruto ácido y refrescante.', 'media'),
('L', 'lobo', 'Mamífero carnívoro relacionado con los perros.', 'media'),
('M', 'murcielago', 'Mamífero volador.', 'media'),
('M', 'maquillaje', 'Cosméticos aplicados en la piel.', 'media'),
('M', 'monstruo', 'Criatura de aspecto aterrador o extraño.', 'media'),
('N', 'nebulosa', 'Nube de gas y polvo en el espacio.', 'media'),
('N', 'noche', 'Período de oscuridad después de la tarde.', 'media'),
('N', 'noria', 'Atracción de feria con grandes ruedas giratorias.', 'media'),
('Ñ', 'ñu', 'Animal herbívoro de gran tamaño.', 'media'),
('Ñ', 'ñandutí', 'Encaje tradicional de Paraguay.', 'media'),
('Ñ', 'añoranza', 'Sentimiento de nostalgia.', 'media'),
('O', 'ópera', 'Forma de arte que combina música y teatro.', 'media'),
('O', 'océano', 'Extensa masa de agua salada.', 'media'),
('O', 'oruga', 'Etapa larval de algunos insectos.', 'media'),
('P', 'palacio', 'Gran edificio donde vive una persona importante.', 'media'),
('P', 'planeta', 'Cuerpo celeste que orbita alrededor de una estrella.', 'media'),
('P', 'perfume', 'Sustancia con fragancia agradable.', 'media'),
('Q', 'quimica', 'Ciencia que estudia la composición de la materia.', 'media'),
('Q', 'quiosco', 'Pequeño establecimiento para vender productos.', 'media'),
('Q', 'quemar', 'Reducir algo a cenizas mediante fuego.', 'media'),
('R', 'rebelde', 'Persona que se opone a la autoridad.', 'media'),
('R', 'radio', 'Dispositivo para recibir ondas sonoras.', 'media'),
('R', 'relojero', 'Persona que repara relojes.', 'media'),
('S', 'serpiente', 'Reptil alargado sin extremidades.', 'media'),
('S', 'sombrero', 'Accesorio para la cabeza.', 'media'),
('S', 'sutil', 'Algo que es delicado o tenue.', 'media'),
('T', 'tambor', 'Instrumento musical de percusión.', 'media'),
('T', 'tortuga', 'Reptil que lleva un caparazón.', 'media'),
('T', 'tornado', 'Viento giratorio violento.', 'media'),
('U', 'universo', 'Todo lo que existe en el espacio.', 'media'),
('U', 'uranio', 'Elemento químico radiactivo.', 'media'),
('U', 'ukelele', 'Instrumento musical de cuerda.', 'media'),
('V', 'ventilador', 'Dispositivo para mover aire.', 'media'),
('V', 'venado', 'Mamífero herbívoro similar al ciervo.', 'media'),
('V', 'volcan', 'Abertura de la Tierra por donde sale magma.', 'media'),
('W', 'wafle', 'Alimento en forma de galleta crujiente.', 'media'),
('W', 'walabi', 'Marsupial australiano similar al canguro.', 'media'),
('W', 'wolverine', 'Animal conocido como glotón.', 'media'),
('X', 'xilofono', 'Instrumento musical de percusión.', 'media'),
('X', 'xenofobia', 'Miedo o rechazo a lo extranjero.', 'media'),
('X', 'xero', 'Término relacionado con sequedad.', 'media'),
('Y', 'yoga', 'Práctica de ejercicios físicos y respiración.', 'media'),
('Y', 'yate', 'Embarcación de recreo.', 'media'),
('Y', 'yeso', 'Material de construcción suave y blanco.', 'media'),
('Z', 'zanahoria', 'Vegetal naranja, rico en vitamina A.', 'media'),
('Z', 'zafiro', 'Piedra preciosa de color azul.', 'media'),
('Z', 'zodiaco', 'División del cielo en constelaciones.', 'media');

-- Inserción de palabras nivel ALTO
INSERT INTO `PREGUNTA` (`letra`, `palabra`, `descripcion`, `dificultadPregunta`) VALUES
('A', 'acantilado', 'Formación geográfica costera.', 'alta'),
('A', 'atemporal', 'Que no tiene tiempo o es eterno.', 'alta'),
('A', 'alpinista', 'Persona que practica la escalada en montañas.', 'alta'),
('B', 'bisiesto', 'Año que tiene un día adicional en febrero.', 'alta'),
('B', 'bibliografia', 'Lista de libros o documentos consultados.', 'alta'),
('B', 'burbuja', 'Esfera de aire o gas en un líquido.', 'alta'),
('C', 'cacofonia', 'Sonido discordante o desagradable.', 'alta'),
('C', 'contemplar', 'Mirar con atención o admiración.', 'alta'),
('C', 'conquistador', 'Persona que conquista o domina.', 'alta'),
('D', 'deshonesto', 'Que carece de honestidad o integridad.', 'alta'),
('D', 'defenestrar', 'Lanzar a alguien por la ventana.', 'alta'),
('D', 'difamador', 'Persona que daña la reputación de alguien.', 'alta'),
('E', 'epifania', 'Manifestación de algo divino.', 'alta'),
('E', 'eufonico', 'Que tiene un sonido agradable.', 'alta'),
('E', 'eternidad', 'Estado de lo que no tiene fin.', 'alta'),
('F', 'fascinante', 'Que atrapa o cautiva la atención.', 'alta'),
('F', 'fundamental', 'Que es esencial o básico.', 'alta'),
('F', 'frivolidad', 'Actitud superficial o poco seria.', 'alta'),
('G', 'galeria', 'Espacio alargado con paredes abiertas al exterior.', 'alta'),
('G', 'generoso', 'Que da o comparte con los demás.', 'alta'),
('G', 'geométrico', 'Relativo a las formas y medidas.', 'alta'),
('H', 'holograma', 'Imagen tridimensional creada con luz.', 'alta'),
('H', 'hemorragia', 'Pérdida de sangre excesiva.', 'alta'),
('H', 'hibernar', 'Estado de inactividad durante el invierno.', 'alta'),
('I', 'ilusionista', 'Persona que realiza trucos de magia.', 'alta'),
('I', 'intrascendente', 'Que carece de importancia o relevancia.', 'alta'),
('I', 'incertidumbre', 'Estado de no saber con certeza.', 'alta'),
('J', 'jeroglífico', 'Sistema de escritura en imágenes.', 'alta'),
('J', 'jubilacion', 'Fin de la vida laboral.', 'alta'),
('J', 'juxtaposicion', 'Colocación de cosas una al lado de la otra.', 'alta'),
('K', 'kiwi', 'Fruta comestible con piel marrón.', 'alta'),
('K', 'kilovatio', 'Unidad de medida de potencia eléctrica.', 'alta'),
('K', 'kinesologia', 'Estudio del movimiento humano.', 'alta'),
('L', 'laberinto', 'Estructura complicada con caminos que se cruzan.', 'alta'),
('L', 'luminosidad', 'Cantidad de luz emitida por un objeto.', 'alta'),
('L', 'licuadora', 'Electrodoméstico para mezclar alimentos.', 'alta'),
('M', 'metamorfosis', 'Cambio de forma o estructura.', 'alta'),
('M', 'magnitud', 'Tamaño o cantidad de algo.', 'alta'),
('M', 'monumental', 'De gran tamaño o importancia.', 'alta'),
('N', 'nostalgia', 'Sentimiento de añoranza por el pasado.', 'alta'),
('N', 'nocturno', 'Que ocurre o se realiza por la noche.', 'alta'),
('N', 'neologismo', 'Palabra o expresión nueva.', 'alta'),
('Ñ', 'ñandú', 'Ave grande y no voladora.', 'alta'),
('Ñ', 'ñoñería', 'Actitud excesivamente tierna o afectuosa.', 'alta'),
('Ñ', 'ñoño', 'Persona que es muy tierna o insignificante.', 'alta'),
('O', 'oquedad', 'Cavidad o espacio vacío.', 'alta'),
('O', 'oligárquico', 'Relativo a un pequeño grupo que ejerce el poder.', 'alta'),
('O', 'ostentoso', 'Que muestra riqueza o lujo de manera excesiva.', 'alta'),
('P', 'platónico', 'Relativo a las ideas de Platón.', 'alta'),
('P', 'polifacético', 'Que tiene muchas facetas o habilidades.', 'alta'),
('P', 'paradigmático', 'Que sirve de ejemplo o modelo.', 'alta'),
('Q', 'quijote', 'Idealista que persigue sueños imposibles.', 'alta'),
('Q', 'querencia', 'Apego o cariño por un lugar o persona.', 'alta'),
('Q', 'quilombo', 'Desorden o confusión.', 'alta'),
('R', 'repudio', 'Desaprobación o rechazo a algo.', 'alta'),
('R', 'resplandor', 'Luz intensa que emite algo.', 'alta'),
('R', 'retorica', 'Arte de hablar o escribir de manera efectiva.', 'alta'),
('S', 'sinestesia', 'Percepción de un sentido a través de otro.', 'alta'),
('S', 'sostenibilidad', 'Capacidad de mantenerse en el tiempo.', 'alta'),
('S', 'semaforo', 'Dispositivo de señalización de tráfico.', 'alta'),
('T', 'telepatia', 'Habilidad de comunicarse mentalmente.', 'alta'),
('T', 'telescopio', 'Instrumento para observar objetos lejanos.', 'alta'),
('T', 'tertulia', 'Reunion de personas para conversar.', 'alta'),
('U', 'umbilical', 'Relacionado con el ombligo.', 'alta'),
('U', 'utopico', 'Relativo a una sociedad ideal.', 'alta'),
('U', 'unidimensional', 'Que tiene una sola dimension.', 'alta'),
('V', 'voracidad', 'Gran apetito o deseo insaciable.', 'alta'),
('V', 'veracidad', 'Verdad o precision en lo que se dice.', 'alta'),
('V', 'velocipedo', 'Vehiculo de dos o mas ruedas impulsado por una persona.', 'alta'),
('W', 'walkman', 'Reproductor de audio portatil.', 'alta'),
('W', 'wifi', 'Tecnologia de red inalambrica.', 'alta'),
('W', 'wolverine', 'Tipo de animal o personaje de comic.', 'alta'),
('X', 'xenofobo', 'Que rechaza a los extranjeros.', 'alta'),
('X', 'xilografia', 'Tecnica de grabado en madera.', 'alta'),
('X', 'xeroftalmia', 'Enfermedad ocular por falta de humedad.', 'alta'),
('Y', 'yacimiento', 'Lugar donde se encuentra un mineral.', 'alta'),
('Y', 'yodado', 'Que contiene yodo.', 'alta'),
('Y', 'yuppie', 'Joven profesional que busca exito.', 'alta'),
('Z', 'zancada', 'Paso largo al caminar.', 'alta'),
('Z', 'zoofilia', 'Atraccion sexual hacia animales.', 'alta'),
('Z', 'zodiaco', 'Conjunto de constelaciones en astrologia.', 'alta');