-- --------------------------------------------------------------------------------- --
-- Hacer un pedido
-- --------------------------------------------------------------------------------- --

-- Se añade el pedido
INSERT INTO pedidoRealizadoA VALUES(
	NULL,
    nif_proveedor,
    coste,
    fecha_entrega
);

-- Para cada producto incluido en el pedido relacionarlo con este e indicar su cantidad
INSERT INTO incluye VALUES(
	id_producto,
    id_pedido_que_sea_el_mismo_que_justo_arriba,
    cantidad
);

-- --------------------------------------------------------------------------------- --
-- Obtener información de pedidos realizados (en requisitos no se entiende, es diferente)
-- --------------------------------------------------------------------------------- --
SELECT * FROM proveedor NATURAL JOIN 
(pedidorealizadoa NATURAL JOIN 
(SELECT * FROM incluye NATURAL JOIN 
(SELECT *FROM recursodefabricacion)));

-- --------------------------------------------------------------------------------- --
-- Anular un pedido
-- --------------------------------------------------------------------------------- --
DELETE FROM pedidoRealizadoA WHERE id_pedido=id_pedido_indicado; -- Borra de incluye en cascada

-- --------------------------------------------------------------------------------- --
-- Registrar llegada de un pedido realizado,
-- --------------------------------------------------------------------------------- --

-- Obtener la cantidad de cada uno de los productos que van en el pedido
SELECT id_producto, cantidad FROM incluye WHERE id_pedido=id_pedido_que_ha_llegado;

-- for cada producto en el pedido
-- Obtener las secciones en las que hay espacio para todas las unidades del producto
SELECT seccion,estanteria FROM almacen WHERE capacidad>=cantidad;

-- Si no hay ninguna con suficiente espacio habrá que dividirlo en varias secciones (encontrar todas donde quepa algo)
SELECT * FROM almacen WHERE capacidad>0;

-- Se guarda en el almacén la cantidad especificada (esto modifica la capacidad del almacén automáticamente con un disparador)
INSERT INTO almacena (seccion, estanteria, id_producto, cantidad)
	VALUES (seccion_elegida, estanteria_elegida, id_producto_llegado, cantidad_producto_llegado);
 
-- --------------------------------------------------------------------------------- --
-- Registrar entrada en almacén (esto es producir algo básicamente)
-- ---------------------------------------------------------------------------------- --

-- Obtener las secciones en las que haya suficiente espacio para guardarlo
SELECT seccion,estanteria FROM almacen WHERE capacidad>=cantidad_producida;

-- Puede que haya que dividirlo en varias secciones
SELECT * FROM almacen WHERE capacidad>0;

-- Guarda en el almacén (automáticamente se sacan del almacén los recursos utilizados con un disparador)
INSERT INTO almacena (seccion, estanteria, id_producto, cantidad)
	VALUES (seccion_elegida, estanteria_elegida, id_producto_fabricado, cantidad_producto_llegado);

-- --------------------------------------------------------------------------------- --
-- Modificar producto (modifica la localización de un producto en el almacén), es un poco diferente al requisito
-- --------------------------------------------------------------------------------- --

-- Se introduce una seccion y una estantería, lo primero es ver si puedo poner todos los productos de un tipo en dicha sección
SELECT capacidad FROM almacen WHERE seccion=seccion_introducida AND estanteria=estanteria_introducida;
SELECT sum(cantidad) FROM almacena WHERE id_producto=id_producto_a_modificar;

-- Se comprueba que capacidad >= cantidad_total, si no lo es se busca una sección con capacidad suficiente y se permite
-- al usuario elegirla, puede ser que no haya ninguna
SELECT seccion,estanteria FROM almacen WHERE capacidad>=sum_cantidad_calculada_antes;

-- Si no hay posibilidades se cancela la operación, si se elige alguna sección:
DELETE FROM almacena WHERE id_producto=id_producto_indicado;
INSERT INTO almacena (seccion, estanteria, id_producto, cantidad)
	VALUES (seccion_elegida, estanteria_elegida, id_producto_introducido, suma_cantidad_calculada_antes);

-- --------------------------------------------------------------------------------- --
-- Localizar producto (obtiene cantidad en cada sección aunque requisitos no lo hacen)
-- --------------------------------------------------------------------------------- --
SELECT seccion, estanteria, cantidad FROM almacena WHERE id_producto=id_introducido;

-- --------------------------------------------------------------------------------- --
-- Comprobar disponibilidad de un producto (devuelve varias cosas según requisitos)
-- --------------------------------------------------------------------------------- --

-- Cantidad total de productos de ese tipo
SELECT sum(cantidad) FROM almacena WHERE id_producto=id_producto_introducido;

-- Sólo si es producto (no recurso), cantidad total que se puede llegar a fabricar
SELECT id_producto_utilizado,cantidad FROM forma WHERE id_producto_formado=id_producto_introducido; -- Recursos que se utilizan en su fabricación
-- Para cada uno de esos recursos se ve la cantidad almacenada
SELECT sum(cantidad) FROM almacena WHERE id_producto=id_producto_utilizado;
-- Aquí con una división entre la cantidad almacenada y la necesitada se encuantra 
-- el número de productos totales que se pueden llegar a fabricar contando ese recurso sóload
-- el mínimo de todos esos será el total de productos de ese tipo que se puede fabricar

-- --------------------------------------------------------------------------------- --
-- Eliminar producto (asegurarse de que no hay unidades almacenadas, 
-- si no habría inconsistencias, sólo borra productos, no recursos)
-- --------------------------------------------------------------------------------- --
DELETE FROM producto WHERE id_producto=id_introducido;

-- --------------------------------------------------------------------------------- --
-- Retirar producto (saca del alamcén una o varias unidades del producto indicado para su venta)
-- --------------------------------------------------------------------------------- --
-- Ver si hay alguna sección con esa cantidad o más, si es así simplemente se resta ahí y diaparador aumenta la capacidad del almacén
SELECT seccion,estanteria FROM almacena WHERE id_producto=id_prod_a_retirar AND cantidad>=cantidad_a_retirar;
UPDATE almacena SET cantidad=cantidad-cantidad_retirada 
	WHERE seccion=seccion_obtenida_consulta AND estanteria=estanteria_obtenida_consulta AND id_producto=id_introducido;

-- Si no hay ninguna sección con suficientes, ver si hay suficientes en todo el almacén
SELECT sum(cantidad) FROM almacena WHERE id_producto=id_introducido;

-- Si es así, se obtienen las secciones y en cada una de ellas se resta la cantidad sacada, un disparador hace que si llega a 0, se borre la relación
SELECT seccion,estanteria,cantidad WHERE id_producto=id_introducido;
UPDATE almacena SET cantidad=cantidad-cantidad_retirada_en_esa_seccion	-- cantidad_retirada_en_esa_seccion=min(cantidad_total_esa_seccion,cantidad_que_queda_por_retirar)
	WHERE seccion=seccion_obtenida_consulta AND estanteria=estanteria_obtenida_consulta AND id_producto=id_introducido;
