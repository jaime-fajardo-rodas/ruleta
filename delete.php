<?php
#Valido que lleguen todos los datos solicitados
if (!isset($_GET["id"])) {
    exit();
}

$id = $_GET["id"];

include_once "con_db.php";

$sql = $conection_DB->prepare("DELETE FROM usuarios WHERE id = ?;");
$rs = $sql->execute([$id]);
if ($rs === true) {
    header("Location: read.php");
} else {
    echo "Algo salió mal";
}