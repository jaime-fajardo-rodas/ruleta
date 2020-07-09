<!-- archivo que permite el guardado de usuarios en la bd -->
<?php
#Valido que lleguen todos los datos solicitados
if (!isset($_POST["cedula"]) || !isset($_POST["nombres"]) || !isset($_POST["apellidos"]) || !isset($_POST["celular"]) ) {
    exit();
}

include_once "con_db.php";

$cedula = $_POST["cedula"];
$nombres = $_POST["nombres"];
$apellidos = $_POST["apellidos"];
$celular = $_POST["celular"];


$sql = $conection_DB->prepare("INSERT INTO usuarios (cedula,nombres,apellidos,celular,saldo,conectado,rol) VALUES ( ? , ?, ? , ?, ?, ?,?);");
$rs = $sql->execute( [$cedula, $nombres, $apellidos, $celular, 10000,0,1] );

if ($rs === true) {
	 header("Location: index.php");
} else {
    echo "Algo salió mal. Por favor verifica que la tabla exista";
}