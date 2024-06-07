DROP DATABASE IF EXISTS logsData;
CREATE DATABASE logsData;
USE logsData;

CREATE TABLE usuario (
email varchar(40) PRIMARY KEY,
userName char(20),
passW varchar(20));

CREATE TABLE palabra_clave (
codClave char(10) PRIMARY KEY,
nombre varchar(70),
email varchar(40));

CREATE TABLE terminal (
    codTerminal INT AUTO_INCREMENT,
	email VARCHAR(40),
    nombreTerminal VARCHAR(40),
    PRIMARY KEY(codTerminal, email),
    CONSTRAINT FK_email FOREIGN KEY (email) REFERENCES usuario(email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE nodo(
codLog varchar(50) PRIMARY KEY,
nombreNodo char(20));

CREATE TABLE fecha_registro (
    codFecha INT AUTO_INCREMENT,
    email VARCHAR(40),
    fechaRegistro DATETIME,
    PRIMARY KEY(codFecha, email),
    CONSTRAINT FK_email FOREIGN KEY (email) REFERENCES usuario(email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE informacion(
codLog varchar(50),
codFecha INT,
codInf char(10),
codClave char(6),
fechaInfo DATETIME,
tiempoTrans char(30),
PRIMARY KEY(codLog, codFecha, codInf),
CONSTRAINT FK_codLog FOREIGN KEY (codLog)
REFERENCES nodo(codLog) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT FK_codFecha FOREIGN KEY (codFecha)
REFERENCES fecha_registro(codFecha) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT FK_codClave FOREIGN KEY (codClave)
REFERENCES palabra_clave(codClave) ON DELETE RESTRICT ON UPDATE CASCADE);


Insert into palabra_clave values
("WTS", "Web tier servlet", null),
("ASC", "Application shutdown completed.", null),
("SUC", "Start up complete.", null),
("BDU", "Bridge Daemon is up.", null),
("BSRN4R", "Bridge synchronous requests: Is N4 ready to serve bridge's requests?", null),
("SXPS", "Stop XPSObject Translation Service:", null),
("N4RRT", "isN4Ready: Return TRUE", null),
("BSRGMO", "Bridge synchronous requests: Get miscellaneous objects", null),
("N4RSR", "N4 is READY to serve requests", null),
("WRSN4", "Waiting for READY signal from N4.", null),
("LXPSO", "loading xps object", null),
("ARABIS", "Awaiting reconnect attempts before initiating shutdown.", null);


