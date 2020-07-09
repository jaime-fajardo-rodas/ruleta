<?PHP include ("seguridad.php");?>
<?php include_once "encabezado.php" ?>
<?php 
	$numeros = [];

	for ($i=1; $i < 99; $i++) { 
		$numeros[$i] = $i;
	}

	$countColumnas = 1;

 ?>

<?php
	if ($_SESSION["rol"] == 0) {
		?>

	<div class="container">
		<div>
			<button class="btn-primary" onclick="girarRuleta()">Girar</button>
		</div>
		<br>
		<div>
			<!-- permite recibir las nuevas apuestas -->
			<button class="btn-primary" onclick="esperarApuestas()">Liberar Ruleta</button>
		</div>
	</div>

	<?php
	}
?>

<br>

<div class="container">
	<div class="row">
		<label id="canJugadores"></label>
	</div>
</div>

<div class="container">
	<div class="row" style="display: inline-flex;">
		<div >
		    <img height="200px" src="https://thumbs.gfycat.com/YellowishNewEasternglasslizard-small.gif">
		</div>
		<div style="margin-left: 40px;width: 100px;height: 100px;border: solid 1px;" class="align-items-center">
			<label>Número Ganador:</label>
			<label id="numGanador"></label>
		</div>
	</div>
</div>

<?php
	if ($_SESSION["rol"] != 0) {
		?>

		<div class="fixed-bottom">
		 	<div class="container">
				<div class="row">
					<div class="col-12">
						<br>
						<label style="border: solid 1px;border-color: blue">Una vez seleccionado el número y se de clic en Apostar no se puede cancelar la apuesta</label>
						<br>
						<label>Número Seleccionado: <input type="text" disabled="true" name="numApostado" id="numApostado" style="width: 30%"></label>
						<button class="btn btn-primary" onclick="apostar()">Apostar</button>
						<label style="margin-left: 30%">Número Elegido: <input type="text" disabled="true" name="numElegido" id="numElegido" style="width: 20%"></label>


						<table class="table table-bordered">
							<thead>
								<tr>
									<?php 
									for ($i=1; $i <= 25; $i++) { 
									?>
										<th style="display: none;"></th>
									<?php 
									} 
									?>
								</tr>
							</thead>

							<tbody>
								<td style="background-color: green;color: black">0</td>
								<?php 
									for ($i=1; $i <= count($numeros); $i++) { 

								 		if ($numeros[$i]%2==0){
								 			?>
								 				<td style="background-color: #240202;color: white"> <?php echo $numeros[$i];  ?></td>
								 			<?php
										    // echo "el $numero es par";
										}else{
											?>
								 				<td style="background-color: #DF3838;color: black"> <?php echo $numeros[$i];  ?></td>
								 			<?php
										    // echo "el $numero es impar";
										}
									 	?>
									 </td>
								<?php 
									$countColumnas++;
									if ($countColumnas == 25) {
										$countColumnas = 0;

										?>
										<tr></tr>
										<?php
									}
								} 
								?>
								<td style="background-color: green;color: black">99</td>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
?>

<script type="text/javascript">

	jQuery(document).ready(function($) {
		/*cuando inicia*/
		cantidadJugadores();
		/*luego cada minuto valido cuantos jugadores hay*/
		setInterval(function(){
		 cantidadJugadores();
		}, 60000);
	});

	$( "td" ).click(function() {
		$("#numApostado").val($(this).text());
	  
	});

	function apostar() {
		var numApostado = $("#numApostado").val();

		if (numApostado != '') {

			$.ajax({
				url: 'apuestas_ruleta.php',
				type: 'POST',
				data: {funcName:"Apostar",numeroApostado: numApostado},
				success:function (data) {
					$("#numApostado").val('');
					$("#numElegido").val(numApostado);
					consultarEstado();
				},error:function(data) {
					alert(JSON.stringify(data['responseJSON']['message']));
				}
			});

		}else{
			alert("seleccione un numero");
		}
	}

	function cantidadJugadores() {
		$.ajax({
			url: 'apuestas_ruleta.php',
			type: 'POST',
			data: {funcName:"getJugadores"},
		})
		.done(function(data) {
			$("#canJugadores").text(data);
		})
		.fail(function(data) {
		})
		.always(function() {
		});	
	}

	function girarRuleta() {
		$.ajax({
			url: 'apuestas_ruleta.php',
			type: 'POST',
			data: {funcName:"getGanador"},
		})
		.done(function(data) {
			console.log("success>> "+data);
			$("#numGanador").text(data);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});		
	}

	function consultarEstado() {
		/*consulta si la ruleta se encuentra girando, esta quieta o hay ganador reciente*/

		$.ajax({
			url: 'apuestas_ruleta.php',
			type: 'POST',
			data: {funcName:"estadoRuleta"},
		})
		.done(function(data) {
			var informacion = data.split("|");
			var estado = informacion[0];
			var num = informacion[1];

			switch(estado) {
			  case '0':
			    setTimeout(function() {
		    	consultarEstado();}, 15000);
			    break;
			  case '1':
			    setTimeout(function() {
			    	consultarEstado();}, 2000);
			    break;

			  case '2':
			    $("#numGanador").text(num);
			    break;
			  default:
			    // code block
			}
			
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function esperarApuestas() {
		$.ajax({
			url: 'apuestas_ruleta.php',
			type: 'POST',
			data: {funcName:"esperarApuestas"},
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	
</script>