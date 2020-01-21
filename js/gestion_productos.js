const botonesEliminar = document.getElementsByClassName("btn-eliminar");

for(let i=0; i<botonesEliminar.length; i++){ 
    botonesEliminar[i].onclick = function(e){ 
        let id_producto = botonesEliminar[i].dataset.id;

        let request = new XMLHttpRequest();
        request.open('DELETE',"producto/" + id_producto);
        request.send(null);
        request.onload = function(){
            console.log(request.response);
            window.location.href = "gestion_productos";
        }
    } 
}