DROP TABLE IF EXISTS Archivo;

DROP TABLE IF EXISTS Mensaje;

DROP TABLE IF EXISTS Beneficiario;

DROP TABLE IF EXISTS Vacacion;

DROP TABLE IF EXISTS Descuento;

DROP TABLE IF EXISTS Movimiento;

DROP TABLE IF EXISTS Permiso;

DROP TABLE IF EXISTS Baja;

DROP TABLE IF EXISTS Trabajador;

DROP TABLE IF EXISTS Pase;

DROP TABLE IF EXISTS Reingreso;

DROP TABLE IF EXISTS Expediente;

DROP TABLE IF EXISTS Gastos;

DROP TABLE IF EXISTS Configuracion;

DROP TABLE IF EXISTS Historial_Plaza;

DROP TABLE IF EXISTS Plaza;

DROP TABLE IF EXISTS Puesto;

DROP TABLE IF EXISTS Departamento;

DROP TABLE IF EXISTS Periodo;

DROP TABLE IF EXISTS Historial;

DROP TABLE IF EXISTS Prenomina;

DROP TABLE IF EXISTS Empleado;

DROP TABLE IF EXISTS Usuario;

DROP TABLE IF EXISTS Autorizacion;

DROP TABLE IF EXISTS Rol;

-- DROP TABLE IF EXISTS Autorizacion_Usuario;

DROP TABLE IF EXISTS Rol_Usuario;

DROP TABLE IF EXISTS Rol_Autorizacion;

DROP TABLE IF EXISTS Categoria;

CREATE TABLE Periodo(
    id_periodo INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    dias INT NOT NULL
);

CREATE TABLE Prenomina(
    id_prenomina INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    del DATE NOT NULL,
    al DATE NOT NULL,
    url VARCHAR(100),
    observacion VARCHAR(255),
    id_periodo VARCHAR(50) NOT NULL,
    estado INT DEFAULT 0,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Configuracion(
    id_configuracion INT PRIMARY KEY AUTO_INCREMENT,
    logo VARCHAR(100),
    nombre VARCHAR(100)
);

CREATE TABLE Archivo(
    id_archivo INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    dias_pago INT NOT NULL,
    url VARCHAR(50) NOT NULL,
    fecha_pago DATE NOT NULL,
    del DATE NOT NULL,
    al DATE NOT NULL,
    puesto VARCHAR(100) NOT NULL,
    id_periodo INT NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Usuario(
    RFC VARCHAR(20) PRIMARY KEY NOT NULL,
    categoria VARCHAR(10) NOT NULL,
    contrasenia VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    telefono VARCHAR(20),
    urlFoto VARCHAR(50),
    nombre VARCHAR(100),
    domicilio VARCHAR(100),
    estado VARCHAR(5) DEFAULT 'alta',
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Empleado(
    id_empleado INT NOT NULL,
    RFC VARCHAR(20) PRIMARY KEY NOT NULL,
    CURP VARCHAR(18) NOT NULL,
    fechaRelLab VARCHAR(10) NOT NULL,
    apellidop VARCHAR(50) NOT NULL,
    apellidom VARCHAR(50) NOT NULL,
    nombres VARCHAR(50) NOT NULL,
    id_periodo INT NOT NULL,
    id_puesto INT NOT NULL,
    banca VARCHAR(30),
    afiliacion VARCHAR(30),
    postal VARCHAR(10),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(RFC) REFERENCES Usuario(RFC) ON DELETE CASCADE
);

CREATE TABLE Historial(
    id_historial INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    retroactivo INT DEFAULT 0,
    id_prenomina INT NOT NULL,
    url VARCHAR(100),
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE
);

CREATE TABLE Gastos(
    id_gastos INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    concepto VARCHAR(500) NOT NULL,
    fecha DATE NOT NULL,
    monto DECIMAL(8, 2) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    url VARCHAR(100),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_prenomina INT NOT NULL,
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Expediente(
    RFC VARCHAR(20) NOT NULL PRIMARY KEY,
    acta VARCHAR(100) NULL,
    antecedentes VARCHAR(100) NULL,
    disciplinarios VARCHAR(100) NULL,
    curp VARCHAR(100) NULL,
    curriculum VARCHAR(100) NULL,
    identificacion VARCHAR(100) NULL,
    constancia VARCHAR(100) NULL,
    recomendacion VARCHAR(100) NULL,
    estudios VARCHAR(100) NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC) ON DELETE CASCADE
);

CREATE TABLE Reingreso(
    id_reingreso INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    inicio DATE NOT NULL,
    id_plaza INT,
    observacion VARCHAR(500),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Mensaje(
    id_mensaje INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    mensaje VARCHAR(500),
    emisor VARCHAR(13) NOT NULL,
    receptor VARCHAR(13) NOT NULL,
    estado INT NOT NULL DEFAULT 0,
    url VARCHAR(100),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Beneficiario(
    id_beneficiario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    beneficiario VARCHAR(50) NOT NULL,
    parentesco VARCHAR(20) NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    url VARCHAR(100),
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Permiso(
    id_permiso INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    -- fecha DATE NOT NULL,
    dias INT NOT NULL,
    del DATE NOT NULL,
    al DATE NOT NULL,
    categoria INT NOT NULL,
    descripcion VARCHAR(500),
    url VARCHAR(100),
    materno INT NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_prenomina INT NOT NULL,
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Vacacion(
    id_vacacion INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    -- fecha DATE NOT NULL,
    dias INT NOT NULL,
    del DATE NOT NULL,
    al DATE NOT NULL,
    descripcion VARCHAR(500),
    url VARCHAR(100),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_prenomina INT NOT NULL,
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Descuento(
    id_descuento INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    dias INT NOT NULL,
    -- fecha DATE NOT NULL,
    fechas VARCHAR(500) NOT NULL,
    motivo VARCHAR(500) NOT NULL,
    url VARCHAR(100),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_prenomina INT NOT NULL,
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Movimiento(
    id_movimiento INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    puesto VARCHAR(100) NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    plaza INT NOT NULL,
    tipoTrabajador VARCHAR(100) NOT NULL,
    url VARCHAR(100),
    puestoAnterior VARCHAR(100) NOT NULL,
    departamentoAnterior VARCHAR(100) NOT NULL,
    tipoTrabajadorAnterior VARCHAR(100) NOT NULL,
    plazaAnterior INT NOT NULL,
    observacion VARCHAR(500),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_prenomina INT NOT NULL,
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Baja(
    id_baja INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    razon VARCHAR(100) NOT NULL,
    id_plaza INT,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Departamento(
    id_departamento INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Puesto(
    id_puesto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    id_departamento INT NOT NULL,
    id_trabajador INT NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_departamento) REFERENCES Departamento(id_departamento)
);

CREATE TABLE Trabajador(
    id_trabajador INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Pase(
    id_pase INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    RFC VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    hora VARCHAR(10) NOT NULL,
    categoria INT NOT NULL,
    observacion VARCHAR(500),
    url VARCHAR(100),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_prenomina INT NOT NULL,
    FOREIGN KEY(id_prenomina) REFERENCES Prenomina(id_prenomina) ON DELETE CASCADE,
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC)
);

CREATE TABLE Plaza(
    id_plaza INT PRIMARY KEY AUTO_INCREMENT,
    id_puesto INT NOT NULL,
    RFC VARCHAR(20),
    dias INT NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado INT DEFAULT 1,
    FOREIGN KEY(id_puesto) REFERENCES Puesto(id_puesto)
);

CREATE TABLE Historial_Plaza(
    id_historial_plaza INT PRIMARY KEY AUTO_INCREMENT,
    id_plaza INT NOT NULL,
    RFC VARCHAR(20) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_plaza) REFERENCES Plaza(id_plaza),
    FOREIGN KEY(RFC) REFERENCES Empleado(RFC) ON DELETE CASCADE
);




-- -----------------------------------------------------------------------------------------------

CREATE TABLE Categoria(
    id_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL
);


CREATE TABLE Autorizacion(
    id_autorizacion INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    id_categoria INT NOT NULL,
    FOREIGN KEY(id_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE
);

CREATE TABLE Rol(
    id_rol INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Rol_Usuario(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_rol INT NOT NULL,
    RFC VARCHAR(20) NOT NULL,
    FOREIGN KEY(id_rol) REFERENCES Rol(id_rol)
);

CREATE TABLE Rol_Autorizacion(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_autorizacion INT NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY(id_rol) REFERENCES Rol(id_rol) ON DELETE CASCADE,
    FOREIGN KEY(id_autorizacion) REFERENCES Autorizacion(id_autorizacion) ON DELETE CASCADE
);

-- CREATE TABLE Autorizacion_Usuario(
--     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
--     id_autorizacion INT NOT NULL,
--     RFC VARCHAR(20) NOT NULL,
--     FOREIGN KEY(id_autorizacion) REFERENCES Autorizacion(id_autorizacion) ON DELETE CASCADE
-- );
-- -----------------------------------------------------------------------------------------------

INSERT INTO
    Usuario(categoria, contrasenia, nombre, RFC, email)
VALUES
    (
        'admin',
        'admin',
        'Administrador',
        'admin',
        'admin@admin.com'
    );

INSERT INTO
    Usuario(categoria, contrasenia, nombre, RFC, email)
VALUES
    (
        'admin',
        'palabra1',
        'Consulta Nomina',
        'develop',
        'info@consultanomina.com'
    );

INSERT INTO Trabajador(nombre) VALUES('BASE');

INSERT INTO Trabajador(nombre) VALUES('CONFIANZA');

INSERT INTO Trabajador(nombre) VALUES('HONORARIOS');

INSERT INTO Trabajador(nombre) VALUES('EVENTUAL');

INSERT INTO Trabajador(nombre) VALUES('SINDICALIZADO');

INSERT INTO Trabajador(nombre) VALUES('DIETAS');

INSERT INTO
    Configuracion(logo, nombre)
VALUES("logo.png", "COMONFORT");

INSERT INTO Periodo(nombre, dias) VALUES("CATORCENAL", 14);

INSERT INTO Periodo(nombre, dias) VALUES("MENSUAL", 30);

INSERT INTO Periodo(nombre, dias) VALUES("OTRA PERIODICIDAD", 0);

-- -----------------------------------------------------------------------------------------------


INSERT INTO Rol(nombre) VALUES('ADMINISTRADOR');

INSERT INTO Categoria(id_categoria, nombre) VALUES(1, 'empleados');
INSERT INTO Categoria(id_categoria, nombre) VALUES(2, 'usuarios');
INSERT INTO Categoria(id_categoria, nombre) VALUES(3, 'prenomina');
INSERT INTO Categoria(id_categoria, nombre) VALUES(4, 'CDFI');
INSERT INTO Categoria(id_categoria, nombre) VALUES(5, 'nominas');
INSERT INTO Categoria(id_categoria, nombre) VALUES(6, 'catalogos');
INSERT INTO Categoria(id_categoria, nombre) VALUES(7, 'plazas');
INSERT INTO Categoria(id_categoria, nombre) VALUES(8, 'mensajes');
INSERT INTO Categoria(id_categoria, nombre) VALUES(9, 'reportes');

-- ALTER TABLE Autorizacion AUTO_INCREMENT=0;

INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Empleados", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Alta y baja de empleados", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Importar Empleados", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Exportar Empleados", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Actualizar Empleados", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Editar información del Empleado", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Eliminar empleado", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Restablecer contraseña del empleado", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar CFDI del empleado", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar incidencias", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Registrar incidencias y cargar archivos", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Cargar archivos de expediente del empleado", 1);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Usuarios", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Crear usuarios", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Editar usuarios", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Eliminar Usuarios", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Crear roles de usuario", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Editar roles de usuario", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Eliminar roles de usuario", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Prenómina", 3);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Generar prenómina", 3);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Autorizar prenómina", 3);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Descargar prenómina", 3);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Importar CFDI", 4);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Registrar empleados por CFDI", 4);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Nominas", 5);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Eliminar registro de CFDI", 5);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Catálogos", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Crear puesto y departamento", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Importar puestos y departamentos", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Exportar puestos y departamentos", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Descargar reporte de puestos agrupados", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Editar puesto y departamento", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Eliminar puesto y departamento", 6);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Plazas", 7);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Crear plaza", 7);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Importar plazas", 7);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Eliminar plaza", 7);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Suspender y activar plazas", 7);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Mensajes", 8);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Visualizar página Reportes", 9);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Generar reporte General", 9);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Generar reporte Plazas", 9);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Generar reporte Usuarios", 9);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Baja de usuario", 2);
INSERT INTO Autorizacion(descripcion, id_categoria) VALUES("Restablecer contraseña de usuario", 2);

ALTER TABLE Usuario ADD postal VARCHAR(10) AFTER estado;
ALTER TABLE Expediente ADD otros VARCHAR(100) NULL AFTER estudios;

INSERT INTO `Rol_Usuario` (`id_rol`, `RFC`) VALUES ( '1', 'admin');
INSERT INTO `Rol_Usuario` (`id_rol`, `RFC`) VALUES ( '1', 'develop');




CREATE TABLE Documento(
    id_documento INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Fichero(
    id_fichero INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,
    RFC VARCHAR(20) NOT NULL,
    url VARCHAR(100),
    elaboracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



SET @id_documento = 1;

-- Insertamos los documentos
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Acta de nacimiento'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'CURP'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Currículum'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Carta de no antecedentes penales'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Carta de no antecedentes disciplinarios'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Identificación oficial'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Constancia de situación fiscal'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Recomendación'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Últimos estudios'); SET @id_documento = @id_documento + 1;
INSERT INTO Documento (id_documento, nombre) VALUES (@id_documento, 'Otro'); SET @id_documento = @id_documento + 1;
