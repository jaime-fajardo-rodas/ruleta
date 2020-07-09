<?php 
	
	#Valido que lleguen todos los datos solicitados
	if (!isset($_POST["funcName"]) ) {
	    exit();
	}else{
		session_start();
		include_once "con_db.php";

		$opcion = $_POST["funcName"];
		switch ($opcion) {
			case 'Apostar':
				Apostar($conection_DB);
				break;

			case 'getJugadores':
				getJugadores($conection_DB);
				break;

			case 'getGanador':
				getGanador($conection_DB);
				break;

			case 'estadoRuleta':
				estadoRuleta($conection_DB);
				break;

			case 'esperarApuestas':
				esperarApuestas($conection_DB);
				break;
			
			default:
				# code...
				break;
		}
	}

	function Apostar($conection_DB)
	{
		
		#Valido que lleguen todos los datos solicitados
		if (!isset($_POST["numeroApostado"]) ) {
		    exit();
		}

		$idUsuario = $_SESSION["id"];
		$numApostado = $_POST["numeroApostado"];

		/*valido que la ruleta no se encuentre girando o que ya haya girado de ser así no acepto la apuesta*/
		$sqlRuleG = $conection_DB->prepare("SELECT * FROM ruleta_ganador ;");
		$sqlRuleG->execute();
		$rsRule = $sqlRuleG->fetchObject();
		$estadoRuleta = $rsRule->estado;

		/*1 girando - 2 girado, se controla el girado para que el admin sea quien habilite el recibimiento de apuestas*/
		if ($estadoRuleta == 1 || $estadoRuleta == 2) {
			header('HTTP/1.1 500 Internal Server casino');
	        header('Content-Type: application/json; charset=UTF-8');
	        die(json_encode(array('message' => 'La ruleta se encuentra girando o con un ganador', 'code' => 1)));
		}

		/*consulto el saldo del usuario*/
		$sql = $conection_DB->prepare("SELECT trunc(saldo) as saldo FROM usuarios WHERE id = ?;");
		$sql->execute([$idUsuario]);

		$usuario = $sql->fetchObject();

		/*valido que exista*/
		if (!$usuario) {
		    echo "¡No existe usuario";
		    exit();
		}

		/*obtengo el saldo para realizar los calculos del valor que se va a apostar*/
		$saldoUsuario = $usuario->saldo;
		echo "el saldo del usuario es: ".$saldoUsuario ."</br>";

		if ($saldoUsuario == 0) {
			echo "jugador sin saldo </br>";
			exit();
		}else if($saldoUsuario <= 1000){
			echo "usuario con 1000 o menos de saldo - All in </br>";
			$valorApostado = $saldoUsuario;
		}else{
			/*random entre 8 15 para calcular el valor de apuesta*/
			$porcentajeApuesta = random_int ( 8, 15 );

			if ($porcentajeApuesta < 10) {
				$porcentajeApuesta = "0.0".$porcentajeApuesta;
			}else{
				$porcentajeApuesta = "0.".$porcentajeApuesta;
			}		

			echo "porcentaje apuesta = ".$porcentajeApuesta."\n";
			$valorApostado = $saldoUsuario * $porcentajeApuesta;

			echo "valor a apostar es: ".$valorApostado."</br>";
		}


		/*querys a ejecutarse*/
		$sqlNumero = $conection_DB->prepare("SELECT * FROM ruleta WHERE numero_apostado = ?;");
		$sqlNumero->execute([$numApostado]);
		$rsNumero = $sqlNumero->fetchObject();

		$sqlUpdate = $conection_DB->prepare("UPDATE usuarios SET saldo = ? WHERE id = ?;");

		/*valido que no exista ese numero apostado en la ronda, de ser así permito la apuesta*/
		if (!$rsNumero) {

			$numApostado = trim($numApostado);

		    $sqlIns = $conection_DB->prepare("INSERT INTO ruleta VALUES ( ? , ?, ? );");
			$rsIns = $sqlIns->execute( [$idUsuario, $valorApostado, $numApostado] );

			if ($rsIns === true) {
				echo "Apuesta registrada </br>";

				/*apuesta ok, entonces se le actualiza el saldo al usuario*/
				$nuevoSaldo = $saldoUsuario - $valorApostado;
				echo "nuevo saldo es: ".$nuevoSaldo."</br>";
				
				$rsUpdate = $sqlUpdate->execute([$nuevoSaldo, $idUsuario]);
				if ($rsUpdate === true) {
				    echo "Saldo actualizado </br>";
				}

				/*se actualiza el saldo de la casa sumandole lo apostado por el usuario...*/
				$sqlCasino = $conection_DB->prepare("SELECT saldo_casa FROM casa ;");
				$sqlCasino->execute();
				$rsCasino = $sqlCasino->fetchObject();

				$saldoCasino = $rsCasino->saldo_casa;

				$nuevoSaldoCasino = $saldoCasino + $valorApostado;

				/*se actualiza el saldo del casino*/
				$sqlUpdateCasino = $conection_DB->prepare("UPDATE casa SET saldo_casa = ? ;");
				$rsUpdateCasino = $sqlUpdateCasino->execute([$nuevoSaldoCasino]);
				if ($rsUpdateCasino === true) {
				    echo "Saldo casino actualizado </br>";
				}

			} else {
			    echo "Algo salió mal registrando la apuesta. Por favor intente nuevamente";
			}
		}else{
			echo "el número seleccionado ya fue apostado en esta ronda, por favor seleccione otro";
		}

	}

	function getJugadores($conection_DB)
	{
		/*consulto cantidad de usuarios conectados*/
		$sql = $conection_DB->prepare("SELECT count(*) as cantidad FROM usuarios WHERE conectado = ?;");
		$sql->execute([1]);

		$can = $sql->fetchObject();

		echo 'Cantidad de jugadores conectados: '.$can->cantidad;
		exit();
	}
	
	function getGanador($conection_DB)
	{
		/*random entre 0 y 99 para definir el numero ganador*/
		$numGanador = random_int ( 0, 99);
		//$numGanador = 0;

		$sqlNumero = $conection_DB->prepare("SELECT * FROM ruleta WHERE trim(numero_apostado) = ?;");
		$sqlNumero->execute([$numGanador]);
		$rsNumero = $sqlNumero->fetchObject();

		/*valido si existe el numero*/
		if ($rsNumero) {
			/*si el numero existe, debo validar el valor a pagar y a que jugador se lo sumo en saldo, este valor a pagar se debe restar de la tabla casa, que es la que tiene el valor de dinero de la casa*/

			$usuarioGanador = $rsNumero->id_usuario;
			$valorApos = $rsNumero->valor_apostado;

			/*valido que sea verde para el calculo correspondiente*/
			if ($numGanador === 0 || $numGanador === 99) {
				/*se calcula el valor a pagar al usuario, pagandole 15 veces lo apostado, color verde*/
				$valorAPagar = $valorApos * 15; 
			}
			else{
				/*colo negro o rojo paga 2 veces lo apostado*/
				$valorAPagar = $valorApos * 2; 
			}

			/*se trae el valor de saldo del usuario*/
			$sqlUsuGana = $conection_DB->prepare("SELECT * FROM usuarios WHERE id = ?;");
			$sqlUsuGana->execute([$usuarioGanador]);
			$rsUsuaGana = $sqlUsuGana->fetchObject();

			$saldoActualUsuario = $rsUsuaGana->saldo;

			$nuevoSaldoUsuGana = $saldoActualUsuario + $valorAPagar;

			/*se le actualiza el saldo al usuario*/
			$sqlUpdateG = $conection_DB->prepare("UPDATE usuarios SET saldo = ? WHERE id = ?;");
			$rsUpdateG = $sqlUpdateG->execute([$nuevoSaldoUsuGana, $usuarioGanador]);
			if ($rsUpdateG === true) {
			    // echo "Saldo usuario ganador actualizado </br>";
			}

			/*el valor que se pago al usuario, se le resta a la casa (casino)*/
			$sqlCasino = $conection_DB->prepare("SELECT saldo_casa FROM casa ;");
			$sqlCasino->execute();
			$rsCasino = $sqlCasino->fetchObject();

			$saldoCasino = $rsCasino->saldo_casa;

			$nuevoSaldoCasino = $saldoCasino - $valorAPagar;

			/*se actualiza el saldo del casino*/
			$sqlUpdateCasino = $conection_DB->prepare("UPDATE casa SET saldo_casa = ? ;");
			$rsUpdateCasino = $sqlUpdateCasino->execute([$nuevoSaldoCasino]);
			if ($rsUpdateCasino === true) {
			    // echo "Saldo casino actualizado </br>";
			}

		}


		/*se limpia la tabla ruleta con el fin de una nueva ronda*/
		$sqlRule = $conection_DB->prepare("DELETE FROM ruleta ;");
		$rsRule = $sqlRule->execute();
		if ($rsRule === true) {
		    // echo "ruleta limpiada";
		}


		/*primero se limpia el resultado anterior, luego se actualiza el estado de la ruleta a girando con el fin de que se les muestre a los jugadores
		que la ruleta comenzo a girar, luego de 30 segundos la ruleta cambia el estado a girado y con esto se le muestra el numero ganador a los usuarios*/

		/*estados ruleta 
		0 no girando - para indicar que se limpiaron los ganadores y se esta a la espera de nuevas apuestas lo decide el admin
		1 girando
		2 girado*/

		$sqlRuleGana = $conection_DB->prepare("DELETE FROM ruleta_ganador ;");
		$rsRuleGana = $sqlRuleGana->execute();
		if ($rsRuleGana === true) {
		    
			$sqlInsR = $conection_DB->prepare("INSERT INTO ruleta_ganador VALUES ( ? );");
			$rsInsertR = $sqlInsR->execute( [1] );

			if ($rsInsertR === true) {
			    sleep(30);
			}

			$sqlUpdateRule = $conection_DB->prepare("UPDATE ruleta_ganador SET estado = ?,numeroGanador = ? ;");
			$sqlUpdateRule->execute([2,$numGanador]);

		}

		echo $numGanador;
	}

	function estadoRuleta($conection_DB)
	{
		$sqlRuleG = $conection_DB->prepare("SELECT * FROM ruleta_ganador ;");
		$sqlRuleG->execute();
		$rsRule = $sqlRuleG->fetchObject();

		echo $rsRule->estado."|".$rsRule->numeroganador;
	}
	
	/*el admin de la mesa es el que toma la decisión para recibir nuevas apuestas*/
	function esperarApuestas($conection_DB)
	{
		/*permite asignar el estado 0 a la tabla ruleta_ganador con el fin de indicar que se esta a la espera de apuestas*/
		$sqlUpdateRule = $conection_DB->prepare("UPDATE ruleta_ganador SET estado = ?,numeroganador = ? ;");
		$sqlUpdateRule->execute([0,'']);
	}

?>