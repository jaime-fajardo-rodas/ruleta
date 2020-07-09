<?PHP
    session_start();

    include_once "con_db.php";
    $sqlUpdate = $conection_DB->prepare("UPDATE usuarios SET conectado = ? WHERE id = ?;");
	$rsUpdate = $sqlUpdate->execute([0, $_SESSION["id"]]);
	if ($rsUpdate === true) {
	    echo "usuario desconectado </br>";
	}

    session_destroy();


	
?>
<html>
    <head>  <title>Fin de Sesión</title>  </head>

 <body>

	<div class="container">
		<div class="row">
			<div class="col-4">
				<h2>Gracias por tu acceso</h2>
				<h3><a href="index.php">Ir a la pagina de inicio</a></h3>
			</div>
		</div>
	</div>

 </body>

</html>
