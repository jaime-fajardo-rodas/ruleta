<!-- permite listar los usuarios guardados -->
<?php

include_once "con_db.php";

$sql = "select * from usuarios";

$rs = $conection_DB->prepare($sql, [
    PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
]);

$rs->execute();
?>

<?php include_once "encabezado.php" ?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h1>Listar Usuarios</h1>
			<br>
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>Cedula</th>
						<th>Nombres</th>
						<th>Apellidos</th>
						<th>Celular</th>
						<th>Saldo</th>
						<th colspan="2">Acciones</th>
					</tr>
				</thead>
				<tbody>

					<?php while ($usuario = $rs->fetchObject()){ ?>
						<tr>
							<td><?php echo $usuario->cedula ?></td>
							<td><?php echo $usuario->nombres ?></td>
							<td><?php echo $usuario->apellidos ?></td>
							<td><?php echo $usuario->celular ?></td>
							<td><?php echo $usuario->saldo ?></td>
							<td>
								<a class="btn btn-warning" href="<?php echo "edit.php?id=" . $usuario->id?>">Editar </a>
							</td>
							<td>
								<a class="btn btn-danger" href="<?php echo "delete.php?id=" . $usuario->id?>">Eliminar </a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
