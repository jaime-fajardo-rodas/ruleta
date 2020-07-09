<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Plantilla básica para Bootstrap">
    <meta name="author" content="Parzibyte">
    <title>Plantilla inicial para Boostrap</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php
        @session_start();
    ?>
    <!-- Definición del menú -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="index.php">Ruleta</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#miNavbar" aria-controls="miNavbar" aria-expanded="false" aria-label="Mostrar u ocultar menú">
        <span class="navbar-toggler-icon"></span>
      </button>

        <div class="collapse navbar-collapse" id="miNavbar">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item active">
                    <a class="nav-link" href="formRegister.php">Registrarse</a>
                </li>

                <?php
                    if(isset($_SESSION['autentificado']) && isset($_SESSION["rol"])){

                        if ($_SESSION["autentificado"] == "si" && $_SESSION["rol"] == 0) {
                        
                            ?>
                            <li class="nav-item active">
                                <a class="nav-link" href="read.php">Ver Usuarios</a>
                            </li>
                            <?php
                        }
                    }
                ?>

                <?php
                    if( isset($_SESSION['autentificado']) ){
                        if ($_SESSION["autentificado"] == "si") {
                        
                            ?>
                            <li class="nav-item active">
                               <a class="nav-link" href="salir.php">Cerrar sesión</a>
                            </li>
                            <?php
                        }
                    }
                ?>
                    
                </li>
            </ul>
        </div>
    </nav>
    <!-- Termina la definición del menú -->

   <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>