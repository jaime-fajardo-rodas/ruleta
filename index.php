
<?php 
	include_once "encabezado.php";
	
	if( isset( $_SESSION['autentificado'] ) ){

		if ( $_SESSION['autentificado'] == "si") {
			header("Location: ruleta.php");
		}
	}
?>

	<div class="container">
		<div class="row">
			<div class="col-4">

				<?php 
					if ($_GET){	
						if ($_GET["error"] == "si"){
					?> 
						<td colspan="2" align="center" bgcolor=red>
						<span style="color:red"><b>Datos incorrectos</b></span>
					<?php 
						}
					} 
				?>


				<h1>Jugar!</h1>
				<form action="login.php" method="POST">
					<div class="form-group">
						<label for="cedula">Número de Cédula</label>
						<input required name="cedula" type="text" id="cedula" placeholder="Número cédula" class="form-control">
					</div>
					<button type="submit" class="btn btn-success">Ingresar</button>
				</form>
			</div>
		</div>
	</div>
