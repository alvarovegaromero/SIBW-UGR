function mostrarComentarios() //Mostrar seccion con comentarios
{
    let comentarios = document.getElementById("comentarios"); //variable referencia a div donde estan los comentarios
    let boton = document.getElementById("botoncomentarios"); //Boton para abrir y cerrar sección de comentarios

    if (comentarios.style.display === "block") //Si se está mostrando, si le damos al boton, dejamos de mostrar
    {
        boton.innerHTML = "Mostrar"; //Boton cambia texto a mostrar
        comentarios.style.display = "none"; //Ocultar sección de comentarios
    }
    else /* display === none */
    {
        comentarios.style.display = "block"; //Lo mostramos como bloque (aunque hay mas opciones)
        boton.innerHTML = "Ocultar";
    }
  }

////////////////////////////////////////////////////////////////////////////////

function corregirComentarioTiempoReal(palabrotas) //Problema, último caracter no lo coge debido al onkeydown
{
  comentario_final = document.getElementById('opinion').value;

  for (palabra of palabrotas) //ver cada palabra
      if (comentario_final.match(palabra.palabrota)) //campo palabrota de la tabla palabrotas
        document.getElementById('opinion').value = comentario_final.replace(palabra.palabrota, "*".repeat(palabra.palabrota.length)); //Que sustituya por tantos ** como letras tenga la palabra
}

////////////////////////////////////////////////////////////////////////////////
// Función actualizada de la P2, que verifica que los campos hayan sido rellenados

function verificarComentario() //P4
{
  let messages = []

      //Cogemos el comentario parcial que ya tiene procesados los insultos
  if(document.getElementById('opinion').value === null || document.getElementById('opinion').value === '')
    messages.push("Un comentario es requerido");

  if(messages.length > 0) //Si hay errores, mostrar mensajes de error
  {
    document.getElementById('error').innerHTML = messages.join(' - '); //Muestre mensaje de error
    return false; //fue mal
  }
  else
    return true;
}

////////////////////////////////////////////////////////////////////////////////
// Ya no las usamos
function ponerComentario(autor, texto)
{
  var fecha = new Date();

  let comentario = document.getElementById("comentario_usuario"); //variable referencia a div donde estan los comentarios
  let form = document.getElementById("postear_comentario"); //Boton para abrir y cerrar sección de comentarios

  var html_a_insertar = '<div class="comentario"> <p class= "autor">' + autor //html a poner en el comentariio este
  + '</p> <p class = "fechayhora">' + fecha.getDate() + "/" + (fecha.getMonth()+1) + "/" + (fecha.getFullYear()) + '</p> <p class = "fechayhora">'
  + fecha.getHours()+":"+fecha.getMinutes() + '</p> <p class = "textocomentario">'
  + texto + '</p> </div>';

    /* Para poder poner varios comentarios*/
  document.getElementById("comentarios").insertAdjacentHTML('beforeend', html_a_insertar);
}

function verificarEmail(mail) //https://es.stackoverflow.com/questions/142/validar-un-email-en-javascript-que-acepte-todos-los-caracteres-latinos
{
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(mail) ? true : false;
}

/* P3
function verificarComentario()
{
  
  let messages = []

  if(document.getElementById('nombre').value === '' || document.getElementById('nombre').value === null) //Si el nombre no se ha introducido
    messages.push("Nombre es requerido");

  if(document.getElementById('email').value === null || document.getElementById('email').value === '')
    messages.push("El email es requerido");
  else //Si hay algo en el campo de email, comprobar que es valido
    if(!verificarEmail(document.getElementById('email').value)) //Si el email introduce no es valido
      messages.push("El email debe ser válido");

      //Cogemos el comentario parcial que ya tiene procesados los insultos
  if(document.getElementById('opinion').value === null || document.getElementById('opinion').value === '')
    messages.push("Un comentario es requerido");

  if(messages.length > 0) //Si hay errores, mostrar mensajes de error
  {
    document.getElementById('error').innerHTML = messages.join(' - '); //Muestre mensaje de error
    return false; //fue mal
  }
  else
    return true;
} */