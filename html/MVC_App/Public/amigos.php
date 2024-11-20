<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos</title>
    <link rel="stylesheet" href="../Visual/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="../Visual/icon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Danfo&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Varela+Round&display=swap" rel="stylesheet">
</head>

<style>
#nova {
    margin-left: 70px;
}
</style>

<body>
    <nav class="navbar">
        <h2 id="nova">NOVA</h2>
        <a href="../Controler/logout.php" id="salir" style="margin-right: 20px;">Salir</a>
    </nav>

    <div class="sidebar">
        <ul>
            <li>
                <a href="index.php">
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
                <a href="#">
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
        </ul>
    </div>
</body>
</html>