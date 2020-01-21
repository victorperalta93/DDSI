var elementos = document.getElementsByClassName('check');
const boton = document.getElementById("boton-enviar");
const nombre_producto = document.getElementById("exampleFormControlInput1");

boton.addEventListener("click", (e) =>{
    e.preventDefault();
    let obj = {};
    obj.productos = {};

    // añadir el proveedor que recibirá el encargo
    obj["nombre"] = nombre_producto.value;

    // por cada producto, añadir al objeto su id como clave y cantidad como valor
    for(let i=0;i<elementos.length;i++){
        if(elementos[i].checked){
            let id_producto = elementos[i].id.substr(6, elementos[i].id.length);
            obj.productos[id_producto] = document.getElementById('cantidad-' + id_producto).value;
        }
    }

    let request = new XMLHttpRequest();
    request.open('POST',"producto");
    request.setRequestHeader("Content-Type","application/json;charset=UTF-8");
    request.send(JSON.stringify(obj));

    request.onload = function(){
        console.log(request.response);
        alert("Producto añadido.");
        window.location.href = "gestion_productos";
    }
});

