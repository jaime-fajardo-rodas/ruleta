<!-- formulario que me permite agregar los usuarios -->
<?php include_once "encabezado.php";
	
	if( isset( $_SESSION['autentificado'] ) ){

		if ( $_SESSION['autentificado'] == "si") {
			header("Location: ruleta.php");
		}
	}

 ?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h1>Agregar Usuario</h1>
			<form action="create.php" method="POST">
				<div class="form-group">
					<label for="cedula">Número de Cédula</label>
					<input required name="cedula" type="text" id="cedula" placeholder="Número cédula" class="form-control">
				</div>
				<div class="form-group">
					<label for="nombres">Nombres</label>
					<input required name="nombres" type="text" id="nombres" placeholder="Nombres" class="form-control">
				</div>
				<div class="form-group">
					<label for="apellidos">Apellidos</label>
					<input required name="apellidos" type="text" id="apellidos" placeholder="Apellidos" class="form-control">
				</div>
				<div class="form-group">
					<label for="celular">Celular</label>
					<input required name="celular" type="number" id="celular" placeholder="Celular" class="form-control">
				</div>
				<!-- el saldo sera guardo como 10.000 -->
				<button type="submit" class="btn btn-success">Guardar</button>
			</form>
		</div>
	</div>
</div>