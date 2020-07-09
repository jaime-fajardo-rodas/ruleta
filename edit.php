<?php
#Valido que lleguen todos los datos solicitados
if (!isset($_GET["id"])) {
    exit();
}

$id = $_GET["id"];

include_once "con_db.php";

$sql = $conection_DB->prepare("SELECT * FROM usuarios WHERE id = ?;");
$sql->execute([$id]);
$usuario = $sql->fetchObject();

if (!$usuario) {
    echo "¡No existe usuario con ese ID!";
    exit();
}

?>

<?php include_once "encabezado.php" ?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h1>Editar</h1>
			<form action="saveEdit.php" method="POST">
				<input type="hidden" name="id" value="<?php echo $usuario->id; ?>">
				<div class="form-group">
					<label for="cedula">Cédula</label>
					<input value="<?php echo $usuario->cedula; ?>"  class="form-control" disabled="true">
				</div>
				<div class="form-group">
					<label for="nombres">Nombres</label>
					<input value="<?php echo $usuario->nombres; ?>" required name="nombres" type="text" id="nombres" placeholder="Nombres del usuario" class="form-control">
				</div>
				<div class="form-group">
					<label for="apellidos">Apellidos</label>
					<input value="<?php echo $usuario->apellidos; ?>" required name="apellidos" type="text" id="apellidos" placeholder="Apellidos de usuario" class="form-control">
				</div>
				<div class="form-group">
					<label for="celular">Celular</label>
					<input value="<?php echo $usuario->celular; ?>" required name="celular" type="number" id="celular" placeholder="Celular de usuario" class="form-control">
				</div>
				<button type="submit" class="btn btn-success">Guardar</button>
				<a href="./read.php" class="btn btn-warning">Volver</a>
			</form>
		</div>
	</div>
</div>
