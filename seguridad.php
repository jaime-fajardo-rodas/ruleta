<?PHP
	//Inicio la sesión
	session_start();

	//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
	if ($_SESSION["autentificado"] != "si") {
		//si no existe, se dirige a la Página de Inicio
		header("Location: index.php");
		//salimos del script
		exit();
	}	
	
?>