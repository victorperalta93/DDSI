-- ---------------------------------------------------------------------------------- --
-- Disparadores que hay que implementar (los 4 primeros son after y el último es before):
-- 	Al insertar en almacena reducir la capacidad en la sección y estantería en la que se inserta en la cantidad indicada en la tabla almacén
-- 	Al borrar de almacena aumentar la capacidad en la sección y estantería en la que se inserta en la cantidad indicada en la tabla almacén
-- 	Al actualizar almacena (esto modifica la cantidad de productos en una sección) modificar la capacidad en la sección y estantería modificadas en almacén
-- 	Si al actualizar en almacena cantidad llega a 0 hacer un delete de la tupla
-- 	Al registrar la entrada de un producto en almacén, en caso de que sea un producto fabricado a partir de otros, eliminar esos del almacén (será hacer updates en almacena)
-- --------------------------------------------------------------------------------- --

-- --------------------------------------------------------------------------------- --
-- Creación de la base de datos
-- --------------------------------------------------------------------------------- --

create database FabricaBienDDSI;
use FabricaBienDDSI;

create table Proveedor(
	NIF varchar(9) not null,
    nombre varchar(30) not null,
    direccion varchar(40),
    correo_electronico varchar(30),
    telefono varchar(20),
    primary key(NIF)
);

Create table PedidoRealizadoA(
	id_pedido int(7) not null auto_increment,
    NIF varchar(9) not null,
    coste double(8,2) check(coste>=0),
    fechaEntrega date,
    primary key(id_pedido),
    foreign key(NIF) references Proveedor(NIF)
);

create table producto(
	id_producto int(5) not null auto_increment,
    nombre varchar(50) not null,
    primary key(id_producto)
);

create table productoFabricado(
	id_producto int(5) not null,
    precio double(7,2) check(precio>=0),
    primary key(id_producto),
    foreign key(id_producto) references producto(id_producto) on delete cascade
);

create table recursoDeFabricacion(
	id_producto int(5) not null,
    primary key(id_producto),
    foreign key(id_producto) references producto(id_producto)
);

create table incluye(
	id_producto int(5) not null,
    id_pedido int(7) not null,
    cantidad integer check(cantidad>0),
    primary key(id_producto, id_pedido),
    foreign key(id_producto) references recursoDeFabricacion(id_producto),
    foreign key(id_pedido) references PedidoRealizadoA(id_pedido) on delete cascade
);

create table vende(
	id_producto int(5) not null,
    NIF varchar(9) not null,
    precio double(7,2) check(precio>0),
    primary key(id_producto, NIF),
    foreign key(id_producto) references recursoDeFabricacion(id_producto),
    foreign key(NIF) references Proveedor(NIF)
);

create table forma(
	id_producto_utilizado int(5) not null,
    id_producto_formado int(5) not null,
    cantidad integer check(cantidad>0),
    primary key(id_producto_utilizado, id_producto_formado),
    foreign key(id_producto_utilizado) references producto(id_producto) on delete cascade,
    foreign key(id_producto_formado) references producto(id_producto) on delete cascade
);

create table almacen(
	seccion varchar(1) not null,
    estanteria integer not null check(estanteria>0),
    capacidad integer check(capacidad>=0),
    primary key(seccion, estanteria)
);

create table almacena(
	seccion varchar(1) not null,
    estanteria integer not null check(estanteria>0),
    id_producto int(5) not null,
    cantidad integer check(cantidad>=0),
    primary key(seccion, estanteria, id_producto),
    foreign key(seccion, estanteria) references almacen(seccion, estanteria),
    foreign key(id_producto) references producto(id_producto)
);

-- --------------------------------------------------------------------------------- --
-- Inserción de tuplas para las que no se ha implementado su inserción y hacen falta
-- --------------------------------------------------------------------------------- --

insert into producto values(
	null,
	"tablero de madera de pino"
);

insert into producto values(
	null,
	"tablero de madera de roble"
);

insert into producto values(
	null,
	"placa de aluminio"
);

insert into producto values(
	null,
	"plancha de PVC"
);

insert into producto values(
	null,
	"placa de acero"
);

insert into producto values(
	null,
	"placa de hierro forjado"
);

insert into producto values(
	null,
	"placa de vidrio templado"
);

insert into recursoDeFabricacion values(
	1
);

insert into recursoDeFabricacion values(
	2
);

insert into recursoDeFabricacion values(
	3
);

insert into recursoDeFabricacion values(
	4
);

insert into recursoDeFabricacion values(
	5
);

insert into recursoDeFabricacion values(
	6
);

insert into recursoDeFabricacion values(
	7
);

insert into Proveedor values(
	"723444567",
    "Maderas Pepe Robles Nogales",
    "C/Los Cerezos 13, Huelva",
    "ventas@rnogales.com",
    "959583402"
);

insert into Proveedor values(
	"242315627",
    "Metalúrgicas Dudu",
    "C/Recogidas 22, Granada",
    "ventas@dudu.com",
    "958234802"
);

insert into Proveedor values(
	"111222333",
    "Vidriolo",
    "C/Aben Humeya 3, Granada",
    "ventas@vidriolo.com",
    "958887766"
);

insert into Proveedor values(
	"123456789",
    "PVCings",
    "C/Abeja 22, Granada",
    "ventas@abeja.com",
    "958445566"
);

insert into vende values(
	1,
    723444567,
    20.00
);

insert into vende values(
	2,
    723444567,
    26.00
);

insert into vende values(
	3,
    242315627,
    30.00
);

insert into vende values(
	4,
    123456789,
    30.00
);

insert into vende values(
	5,
    242315627,
    32.00
);

insert into vende values(
	6,
    242315627,
    25.00
);

insert into vende values(
	7,
    111222333,
    40.00
);

insert into almacen values(
	"A",
    1,
    100
);

insert into almacen values(
	"A",
    2,
    220
);

insert into almacen values(
	"A",
    3,
    100
);

insert into almacen values(
	"A",
    4,
    150
);

insert into almacen values(
	"B",
    1,
    110
);

insert into almacen values(
	"B",
    2,
    125
);

insert into almacen values(
	"C",
    1,
    100
);

insert into almacen values(
	"C",
    2,
    200
);

-- --------------------------------------------------------------------------------- --
-- Disparadores
-- --------------------------------------------------------------------------------- --
-- Javi, actualizar capacidad de una sección y estantería del almacén al insertar en almacena
CREATE TRIGGER nuevaCapacidadInsert
AFTER INSERT ON almacena
FOR EACH ROW
	UPDATE almacen SET capacidad = capacidad - NEW.cantidad WHERE
		seccion = NEW.seccion AND estanteria = NEW.estanteria;