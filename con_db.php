<?php

// archivo que me permite realizar la conexion a la base de datos

/*heroku*/

$contraseña = "64502ed069e4219fac3be9b95bf2a0f87a5cba2019779aaa84a28196bc5699d3";
$usuario = "yehvxytmjzriek";
$nombreBaseDeDatos = "demp0s116tal5s";
$rutaServidor = "ec2-18-214-119-135.compute-1.amazonaws.com";


/*local*/
/*
$contraseña = "admin";
$usuario = "postgres";
$nombreBaseDeDatos = "casino";
$rutaServidor = "127.0.0.1";
*/

$puerto = "5432";
try {
    $conection_DB = new PDO("pgsql:host=$rutaServidor;port=$puerto;dbname=$nombreBaseDeDatos", $usuario, $contraseña);
    $conection_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "Ocurrió un error con la base de datos: " . $e->getMessage();
}