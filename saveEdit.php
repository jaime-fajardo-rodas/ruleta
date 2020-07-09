<!-- guarda los datos editados del usuario -->
<?php

#Valido que lleguen todos los datos solicitados
if ( !isset($_POST["id"]) || !isset($_POST["nombres"]) || !isset($_POST["apellidos"]) || !isset($_POST["celular"]) ) {
   exit();
}

include_once "con_db.php";

$id = $_POST["id"];
$nombres = $_POST["nombres"];
$apellidos = $_POST["apellidos"];
$celular = $_POST["celular"];

$sql = $conection_DB->prepare("UPDATE usuarios SET nombres = ?, apellidos = ?, celular = ? WHERE id = ?;");
$rs = $sql->execute([$nombres, $apellidos, $celular, $id]);
if ($rs === true) {
    header("Location: read.php");
} else {
    echo "Algo salió mal. Por favor verifica que la tabla exista, así como el ID del usuario";
}