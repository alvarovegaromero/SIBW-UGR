
function realizarBusquedaProductos(rol) {
    $.ajax({
        data: {rol_post: rol, busqueda_post: document.getElementById("productos_buscar").value},
        url: './buscaProductos.php',
        type: 'POST',
        beforeSend: function () {
        },
        success: function(productos) {
            console.log(productos);
            mostrarProductosBusqueda(productos);
        }
    });
}

function mostrarProductosBusqueda(productos) {
    $("#resultado_busqueda").empty();

    let maximoProductosBusqueda = 5;

    if(maximoProductosBusqueda > productos.length) //Máximo 5 productos o menos en la salida
        maximoProductosBusqueda = productos.length;

    for (let i = 0; i < maximoProductosBusqueda; i++) {

        let enlace = document.createElement('a');
        enlace.setAttribute('href', "./producto.php?prod=" + productos[i]['id_producto']);

        let div = document.createElement('div');

        let p = document.createElement('p');
        p.appendChild(document.createTextNode(productos[i]['nombre_producto']));

        div.appendChild(p);

        enlace.appendChild(div);

        document.getElementById("resultado_busqueda").appendChild(enlace);
    }
} 