var elementos = document.getElementsByClassName('check');
const boton = document.getElementById("boton-enviar");
const fecha = document.getElementById("form23");
const proveedor = document.getElementById("sel1");

boton.addEventListener("click", (e) =>{
    e.preventDefault();
    let obj = {};
    obj.productos = {};

    // añadir el proveedor que recibirá el encargo
    obj["proveedor"] = proveedor.options[proveedor.selectedIndex].text;

    // añadir la fecha del pedido
    obj["fecha"] = fecha.value;

    // por cada producto, añadir al objeto su id como clave y cantidad como valor
    for(let i=0;i<elementos.length;i++){
        if(elementos[i].checked){
            let id_producto = elementos[i].id.substr(6, elementos[i].id.length);
            obj.productos[id_producto] = document.getElementById('cantidad-' + id_producto).value;
        }
    }

    console.log(obj);

    let request = new XMLHttpRequest();
    request.open('POST',"pedido");
    request.setRequestHeader("Content-Type","application/json;charset=UTF-8");
    request.send(JSON.stringify(obj));

    request.onload = function(){
        console.log(request.response);
        alert("Pedido realizado.");
        window.location.href = "gestion_pedidos";
    }
});

