const botonesAnular    = document.getElementsByClassName("btn-anular");
const botonesRegistrar = document.getElementsByClassName("btn-registrar");
const botonesConsultar = document.getElementsByClassName("btn-consultar");

for(let i=0; i<botonesAnular.length; i++){ 
    botonesAnular[i].onclick = function(e){ 
        let id_pedido = botonesAnular[i].dataset.id;

        let request = new XMLHttpRequest();
        request.open('DELETE',"pedido/" + id_pedido);
        request.send(null);
        request.onload = function(){
            console.log(request.response);
            window.location.href = "gestion_pedidos";
        }
    } 
}

for(let i=0; i<botonesRegistrar.length; i++){ 
    botonesRegistrar[i].onclick = function(e){ 
        let id_pedido = botonesRegistrar[i].dataset.id;

        let request = new XMLHttpRequest();
        request.open('PUT',"registrar/" + id_pedido);
        request.send(null);
        request.onload = function(){
            console.log(request.response);
            window.location.href = "gestion_pedidos";
        }
    } 
}

for(let i=0; i<botonesConsultar.length; i++){ 
    botonesConsultar[i].onclick = function(e){ 
        let id_pedido = botonesConsultar[i].dataset.id;

        let request = new XMLHttpRequest();
        request.open('GET',"consultar_pedido/" + id_pedido);
        request.send(null);
        request.onload = function(){
            document.getElementById('modal-consultar').innerHTML = request.response;
            console.log(request.response);
        }
    } 
}
