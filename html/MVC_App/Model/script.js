const postButton = document.getElementById('show-post-form');
const postForm = document.getElementById('post-form');

postButton.addEventListener('click', function () {
    postForm.style.display = postForm.style.display === 'block' ? 'none' : 'block';
});

var dropArea = document.getElementById('dropArea');
var fileInput = document.getElementById('image');
var fileList = document.getElementById('file-list');

dropArea.addEventListener('dragover', function (e) {
    e.preventDefault();
    dropArea.classList.add('highlight');
});

dropArea.addEventListener('dragleave', function () {
    dropArea.classList.remove('highlight');
});

dropArea.addEventListener('drop', function (e) {
    e.preventDefault();
    dropArea.classList.remove('highlight');
    var files = e.dataTransfer.files;
    fileInput.files = files;
    displayFiles(files);
});

dropArea.addEventListener('click', function () {
    fileInput.click();
});

fileInput.addEventListener('change', function () {
    displayFiles(fileInput.files);
});

function displayFiles(files) {
    fileList.innerHTML = '';
    for (var i = 0; i < files.length; i++) {
        var fileItem = document.createElement('div');
        fileItem.textContent = files[i].name;
        fileList.appendChild(fileItem);
    }
}

function upvotePost(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../Controler/PostController.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var upvotesElement = document.getElementById("upvotes-" + postId);
        upvotesElement.textContent = xhr.responseText + " Upvotes";
        }
    };
    xhr.send("id_post=" + postId + "&action=upvote");
}

document.getElementById('buscarBtn').addEventListener('click', function() {
var nombre = document.getElementById('buscarUsuario').value;

fetch('../Controler/MensajeController.php?nombre=' + nombre)
.then(response => response.json())
.then(data => {
    var resultados = document.getElementById('resultadosBusqueda');
    resultados.innerHTML = ''; // Limpiar resultados anteriores

    if (data.length > 0) {
        data.forEach(usuario => {
            var userDiv = document.createElement('div');
            userDiv.textContent = usuario.nombre;
            userDiv.style.cursor = 'pointer';
            userDiv.addEventListener('click', function() {
                seleccionarUsuario(usuario.id_usuario, usuario.nombre);
            });
            resultados.appendChild(userDiv);
        });
    } else {
        resultados.innerHTML = '<p>No se encontraron usuarios</p>';
    }
})
.catch(error => console.error('Error en la búsqueda:', error));
});
// Selección de usuario
function seleccionarUsuario(idUsuario, nombreUsuario) {
document.getElementById('nombreUsuario').textContent = nombreUsuario;
document.getElementById('chatContainer').style.display = 'block';

// Cargar mensajes previos con este usuario
cargarMensajes(idUsuario);

document.getElementById('enviarMensajeBtn').onclick = function() {
enviarMensaje(idUsuario);
};
}

// Cargar mensajes con el usuario seleccionado
function cargarMensajes(idUsuario) {
fetch('../Controler/MensajeController.php?usuario=' + idUsuario)
.then(response => response.json())
.then(mensajes => {
    var mensajesContainer = document.getElementById('mensajesContainer');
    mensajesContainer.innerHTML = ''; // Limpiar mensajes previos

    mensajes.forEach(mensaje => {
        var mensajeDiv = document.createElement('div');
        mensajeDiv.textContent = mensaje.contenido_mensaje;
        mensajesContainer.appendChild(mensajeDiv);
    });
})
.catch(error => console.error('Error al cargar mensajes:', error));
}


// Enviar mensaje
function enviarMensaje(idUsuario) {
var mensajeTexto = document.getElementById('mensajeTexto').value;
var fechaMensaje = new Date().toISOString().split('T')[0]; // Fecha en formato YYYY-MM-DD

fetch('../Controler/MensajeController.php', {
method: 'POST',
headers: {
    'Content-Type': 'application/json',
},
body: JSON.stringify({
    id_usuario: idUsuario,
    contenido_mensaje: mensajeTexto,
    fecha_mensaje: fechaMensaje
})
})
.then(response => response.json())
.then(data => {
cargarMensajes(idUsuario); // Recargar los mensajes después de enviar uno nuevo
document.getElementById('mensajeTexto').value = ''; // Limpiar el área de texto
})
.catch(error => console.error('Error al enviar mensaje:', error));
}