const botonesEliminar = document.getElementsByClassName("btn-eliminar");
const botonRetirar    = document.getElementById("btn-retirar");
const campoCantidad   = document.getElementById("cantidad-");

var seccion;
var estanteria;
var id_producto;

for(let i=0; i<botonesEliminar.length; i++){ 
    botonesEliminar[i].onclick = function(e){ 
        seccion = botonesEliminar[i].dataset.seccion;
        estanteria = botonesEliminar[i].dataset.estanteria;
        id_producto = botonesEliminar[i].dataset.id;
    } 
}

botonRetirar.onclick = function(e){
    let cantidad = campoCantidad.value;

    let request = new XMLHttpRequest();
    request.open('PUT',"almacen/" + seccion + "/" + estanteria + "/" + cantidad + "/" + id_producto);
    request.send(null);

    request.onload = function(){
        window.location.href = "gestion_productos_almacen";
        console.log(request.response);
    }
}