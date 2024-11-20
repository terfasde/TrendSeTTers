<?php
date_default_timezone_set('America/Montevideo');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . '/../Controler/config/db.php');
include(__DIR__ . '/../Controler/config/functions.php');
include(__DIR__ . '/../Controler/ImageController.php');



if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Controler/login.php");
    exit();
}


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST['csrf_token'], $_SESSION['csrf_token']); // Añadir esta línea para depurar
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.');
    }
}


require_once(__DIR__ . '/../Model/PostModelo.php');
$PostObjeto = new PostModelo();
$posts = $PostObjeto->obtener_posts();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $image_url = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image_url = 'uploads/' . $file_name;
        }
    }

    $contenido = $_POST['content'];
    $id_usuario = $_SESSION['id_usuario'];
    $fecha_post = date('Y-m-d H:i:s');

    $respuesta = $PostObjeto->insert_Post($contenido, $id_usuario, $fecha_post);
    $id_post = $PostObjeto->get_last_insert_id();

    if ($image_url) {
        $PostObjeto->insert_image($id_post, $image_url);
    }

    header("Location: index.php");
    exit();
}
function obtener_posts() {
    $url = 'http://localhost/MVC_App/Controler/PostController.php'; // Cambia a la URL de tu API si es necesario
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        return [];
    }

    $decoded_response = json_decode($response, true);
    return is_array($decoded_response) ? $decoded_response : [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    require_once(_DIR_ . '/../Controler/PostController.php');  // Incluye tu controlador de post

    // Aquí llama a la función de tu controlador para insertar el post
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = _DIR_ . '/../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image_url = 'uploads/' . $file_name;
        } else {
            $image_url = null;
        }
    }

    // Ahora inserta el post en la base de datos
    $contenido = $_POST['content'];
    $id_usuario = $_SESSION['id_usuario'];
    $fecha_post = date('Y-m-d H:i:s');

    $PostObjeto = new PostModelo();
    $respuesta = $PostObjeto->insert_Post($contenido, $id_usuario, $fecha_post);

    // Obtener el ID del post recién insertado
    $id_post = $PostObjeto->get_last_insert_id();

    // Si hay imagen, la insertamos en la tabla Imagenes_Post
    if ($image_url) {
        $PostObjeto->insert_image($id_post, $image_url);
    }

    // Redirige de nuevo al index después de insertar el post
    header("Location: index.php");
    exit();
}

    $PostObjeto = new PostModelo();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Feed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="../Visual/icon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Danfo&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Varela+Round&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../Visual/styles.css">
    <script src="../Model/script.js"></script>
</head>

<style>
#nova {
    margin-left: 70px;
}
</style>

<body>
    <audio id="audio" src="../Visual/click.mp3"></audio>

    <nav class="navbar animate__animated animate__fadeInDown">
        <h2 id="nova">NOVA</h2>
        <a href="../Controler/logout.php" id="salir" style="margin-right: 20px;">Salir</a>
    </nav>

    <div class="sidebar animate__animated animate__fadeInLeft" id="sidebar">
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-home fa-lg"></i>
                    <span class="nav-text">Inicio</span>
                </a>
            </li>
            <li>
                <a href="perfil.php">
                    <i class="fa fa-user fa-lg"></i>
                    <span class="nav-text">Perfil</span>
                </a>
            </li>
            <li>
                <a href="amigos.php">
                    <i class="fa fa-heart fa-lg"></i>
                    <span class="nav-text">Amigos</span>
                </a>
            </li>
            <li>
                <a href="eventos.php">
                    <i class="fa fa-clock-o fa-lg"></i>
                    <span class="nav-text">Eventos</span>
                </a>
            </li>
            <li>
                <a href="mensajes.php">
                    <i class="fa fa-envelope fa-lg"></i> <!-- Icono de carta -->
                    <span class="nav-text">Mensajes</span>
                </a>
            </li>
        </ul>
    </div>

    <div style="text-align:center; margin-top: 20px;">
        <button id="show-post-form" class="animate__animated animate__fadeInDown">Crear Post</button>
    </div>

    <?php
$conexion = new mysqli('localhost', 'root', 'root', 'social_network');

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener los últimos 10 posts junto con el nombre de usuario de la tabla Usuario
$resultado = $conexion->query("
    SELECT 
        p.contenido, 
        p.image, 
        u.nombre_usuario, 
        p.fecha_post, 
        p.id_post, 
        p.likes_post 
    FROM 
        post p
    INNER JOIN 
        usuario u ON p.id_usuario = u.id_usuario
    ORDER BY 
        p.id_post DESC 
    LIMIT 10
");

// Almacenar los posts en un array
$posts = [];
while ($fila = $resultado->fetch_assoc()) {
    $posts[] = $fila;
}

$conexion->close();
?>

    <!-- Formulario de posteo -->
    <div id="post-form-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('post-form-modal')">&times;</span>
            <form id="post-form" method="POST" enctype="multipart/form-data" action="index.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="contenido" id="contenido" placeholder="Escribe algo..." required>
                <div id="dropArea" class="drop-area">
                    <p id="areaDropeada">Arrastra aquí los archivos o haz clic para subir</p>
                    <input type="file" id="image" name="image" style="display: none;" multiple>
                </div>
                <div id="file-list"></div>
                <button type="submit" id="publicar">Publicar</button>
            </form>
        </div>
    </div>

    <!-- Contenedor de posts -->
    <div id="posts-container" class="animate__animated animate__backInDown">
        <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-content">
                <p>
                    <?php echo htmlspecialchars($post['contenido']); ?>
                </p>
                <?php if (!empty($post['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Imagen del post"
                    style="max-width: 300px;">
                <?php endif; ?>
            </div>
            <div class="post-info">
                <p class="nombre-usuario">
                    <?php echo htmlspecialchars($post['nombre_usuario']); ?>
                </p>
                <p class="fecha">
                    <?php echo date('H:i - d/m/Y', strtotime($post['fecha_post'])); ?>
                </p>
                <div class="post-actions">
                    <button class="upvote-btn" onclick="upvotePost(<?php echo $post['id_post']; ?>)">
                        <i class="fa fa-arrow-up"></i> Upvote
                        <span id="upvotes-<?php echo $post['id_post']; ?>">
                            <?php echo $post['likes_post']; ?>
                        </span>
                    </button>

                    <button class="comment-btn" onclick="openCommentModal(<?php echo $post['id_post']; ?>)">
                        <i class="fa fa-comment"></i> Comentar
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal para el formulario de comentarios -->
    <div id="comment-form-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('comment-form-modal')">&times;</span>
        <h3>Agregar Comentario</h3>

        <!-- Formulario para agregar un comentario -->
        <form id="comment-form">
            <input type="hidden" name="post_id" id="comment-post-id" value="">
            <textarea name="comentario" id="comentario" placeholder="Escribe tu comentario..." required></textarea>
            <button type="submit">Comentar</button>
        </form>

        <!-- Contenedor de los comentarios con scroll -->
        <div id="comment-list" class="comment-list">
            <!-- Los comentarios se cargarán aquí dinámicamente -->
        </div>
    </div>
</div>

</body>


<script>
    // Obtener el elemento de audio que ya está en el HTML
    const clickSound = document.getElementById('audio');

    // Función para reproducir el sonido cuando se hace clic
    function playClickSound() {
        clickSound.play();
    }

    // Agregar un event listener para detectar clics en toda la página
    document.addEventListener('click', playClickSound);


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

    document.getElementById('show-post-form').addEventListener('click', function () {
        const postModal = document.getElementById('post-form-modal');

        // Verificamos que el modal exista y luego lo mostramos
        if (postModal) {
            postModal.style.display = 'flex';
            document.body.classList.add('modal-active');
        } else {
            console.error("No se encuentra el modal con ID 'post-form-modal'.");
        }
    });

    // Cerrar el modal cuando se hace clic fuera de él
    window.onclick = function (event) {
        const postModal = document.getElementById('post-form-modal');
        if (event.target == postModal) {
            postModal.style.display = 'none';
            document.body.classList.remove('modal-active');
        }
    };

    // Función para cerrar el modal cuando se hace clic en el botón de cerrar
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-active');
        } else {
            console.error(`No se encuentra el modal con ID ${modalId}.`);
        }
    }

    function openCommentModal(postId) {
        const commentModal = document.getElementById('comment-form-modal');
        const postIdInput = document.getElementById('comment-post-id');

        if (commentModal && postIdInput) {
            postIdInput.value = postId; // Asignamos el ID del post al input
            commentModal.style.display = 'flex';
            document.body.classList.add('modal-active');
        } else {
            console.error("No se encuentra el modal de comentarios o el campo de ID del post.");
        }
    }


    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    document.addEventListener('DOMContentLoaded', function () {
        const postButton = document.getElementById('show-post-form');
        const postForm = document.getElementById('post-form');

        postButton.addEventListener('click', function () {
            postForm.style.display = postForm.style.display === 'block' ? 'none' : 'block';
        });
    });

    document.getElementById('buscarBtn').addEventListener('click', function() {
    const nombre = document.getElementById('buscarUsuario').value;

    if (nombre) {
        fetch('/Controler/MensajeController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                action: 'buscarUsuario',
                nombre: nombre
            })
        })
        .then(response => response.json())
        .then(data => {
            const resultados = document.getElementById('resultadosBusqueda');
            resultados.innerHTML = ''; // Limpiar resultados anteriores
            data.forEach(usuario => {
                const userDiv = document.createElement('div');
                userDiv.textContent = usuario.nombre_usuario;
                userDiv.dataset.id = usuario.id_usuario;
                userDiv.style.cursor = 'pointer';
                userDiv.addEventListener('click', function() {
                    seleccionarUsuario(usuario.id_usuario, usuario.nombre_usuario);
                });
                resultados.appendChild(userDiv);
            });
        });
    }
});

function seleccionarUsuario(idUsuario, nombreUsuario) {
    document.getElementById('nombreUsuario').textContent = nombreUsuario;
    document.getElementById('nombreUsuario').dataset.id = idUsuario;
    cargarMensajes(idUsuario);
}

    document.getElementById('enviarMensajeBtn').addEventListener('click', function () {
        const mensajeTexto = document.getElementById('mensajeTexto').value;
        const destinatario = document.getElementById('nombreUsuario').dataset.id;

        if (mensajeTexto && destinatario) {
            fetch('/Controler/MensajeController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'enviar',
                    destinatario: destinatario,
                    mensaje: mensajeTexto
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('mensajeTexto').value = '';
                        cargarMensajes(destinatario); // Actualiza los mensajes
                    }
                });
        }
    });

    function openCommentModal(postId) {
        document.getElementById('comment-post-id').value = postId;
        document.getElementById('comment-form-modal').style.display = 'flex';
        document.body.classList.add('modal-active');
    }

    // Función para cerrar cualquier modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.classList.remove('modal-active');
    }

    function upvotePost(postId) {
    // Selecciona el contador de upvotes correspondiente al post
    const upvoteCount = document.getElementById(`upvotes-${postId}`);

    // Obtén el valor actual del contador de likes
    let currentCount = parseInt(upvoteCount.textContent);

    // Selecciona el botón de upvote
    const upvoteButton = document.querySelector(`[onclick="upvotePost(${postId})"]`);

    // Verifica si el botón ya está "liked" (por ejemplo, usando una clase)
    if (upvoteButton.classList.contains('liked')) {
        // Si ya tiene un like, lo restamos (unlike)
        upvoteCount.textContent = currentCount - 1;

        // Elimina la clase 'liked' para mostrar que ya no está likeado
        upvoteButton.classList.remove('liked');
    } else {
        // Si no tiene like, lo sumamos (like)
        upvoteCount.textContent = currentCount + 1;

        // Agrega la clase 'liked' para mostrar que está likeado
        upvoteButton.classList.add('liked');
    }
}



document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('comment-form');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

        // Obtener los datos del formulario
        const comentario = document.getElementById('comentario').value;
        const postId = document.getElementById('comment-post-id').value;

        // Validar que el comentario no esté vacío
        if (comentario.trim() === '') {
            alert('El comentario no puede estar vacío.');
            return;
        }

        // Crear un objeto FormData para enviar los datos
        const formData = new FormData();
        formData.append('comentario', comentario);
        formData.append('post_id', postId);

        // Enviar los datos a guardar_comentario.php usando fetch
        fetch('guardar_comentario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Limpiar el formulario después de enviar
                form.reset();

                // Agregar el nuevo comentario al DOM sin recargar la página
                agregarComentarioAlDom(data.comentario, data.fecha_comentario);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Función para agregar el comentario al DOM
    function agregarComentarioAlDom(contenido, fecha) {
        const contenedorComentarios = document.getElementById('comment-list');
        const nuevoComentario = document.createElement('div');
        nuevoComentario.classList.add('comentario');
        
        nuevoComentario.innerHTML = `
            <p>${contenido}</p>
            <p><small>${new Date(fecha).toLocaleString()}</small></p>
        `;
        
        contenedorComentarios.appendChild(nuevoComentario);
    }

    // Función para cargar los comentarios de un post al abrir el modal
    function cargarComentarios(postId) {
        // Limpiar los comentarios previos
        const contenedorComentarios = document.getElementById('comment-list');
        contenedorComentarios.innerHTML = 'Cargando comentarios...';

        // Hacer una solicitud AJAX para obtener los comentarios
        fetch(`obtener_comentarios.php?post_id=${postId}`)
        .then(response => response.json())
        .then(comentarios => {
            contenedorComentarios.innerHTML = ''; // Limpiar el mensaje de carga

            if (comentarios.length === 0) {
                contenedorComentarios.innerHTML = '<p>No hay comentarios todavía.</p>';
            } else {
                comentarios.forEach(comentario => {
                    agregarComentarioAlDom(comentario.comentario, comentario.fecha_comentario);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar los comentarios:', error);
        });
    }

    // Función para abrir el modal y cargar los comentarios de un post
    function openCommentModal(postId) {
        document.getElementById('comment-post-id').value = postId; // Establecer el ID del post
        cargarComentarios(postId); // Cargar los comentarios del post
        document.getElementById('comment-form-modal').style.display = 'block'; // Mostrar el modal
    }

    // Función para cerrar el modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
    }

    // Exportar las funciones globalmente para usarlas desde el HTML
    window.openCommentModal = openCommentModal;
    window.closeModal = closeModal;
});
</script>

</html>