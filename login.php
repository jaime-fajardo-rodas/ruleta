<?php 

	#Valido que lleguen todos los datos solicitados
	if (!isset($_POST["cedula"]) ) {
	    exit();
	}
	session_start();


	$cedula = $_POST["cedula"];

	include_once "con_db.php";

	$sql = $conection_DB->prepare("SELECT * FROM usuarios WHERE cedula = ?;");
	$sql->execute([$cedula]);
	$usuario = $sql->fetchObject();

	if (!$usuario) {
	    header("Location: index.php?error=si");
	}else{
		$_SESSION["autentificado"]= "si";
		$_SESSION["id"] = $usuario->id;
		$_SESSION["nombres"] = $usuario->nombres;
		$_SESSION["apellidos"] = $usuario->apellidos;
		$_SESSION["saldo"] = $usuario->saldo;
		$_SESSION["rol"] = $usuario->rol;

		$sqlUpdate = $conection_DB->prepare("UPDATE usuarios SET conectado = ? WHERE id = ?;");
		$rsUpdate = $sqlUpdate->execute([1, $usuario->id]);
		if ($rsUpdate === true) {
		    echo "usuario conectado </br>";
		}

		header("Location: ruleta.php");
	}

?>