create database casino;
	
create table usuarios(
	id serial primary key,
	cedula  varchar(15) UNIQUE,
	nombres varchar(20),
	apellidos varchar(20),
	celular varchar(20),
	saldo numeric(20,2),
	conectado boolean,
	rol int
);

create table ruleta(
	id_usuario int,
	valor_apostado numeric(20,2),
	numero_apostado varchar(5)
);

create table casa(
	saldo_casa numeric(20,2)
);

create table ruleta_ganador(
	estado int,
	numeroGanador varchar(5)
);

insert into usuarios (cedula,nombres,rol) values ('1','admin',0);