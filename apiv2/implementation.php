<?php
include "constantes.php";
require_once "utilities.php"; 

$util = new Utilities();

class Implementation{
 
	function selectUserAuth($mysqli, $usrNick, $usrPass, $fcmToken){
		global $util;
		$query = " SELECT 
		
				PRS.PRS_ID,
				USR.USR_ID,
				USR.USR_NICK, 
				PRS.PRS_NOMBRES,
				PRS.PRS_CORREO,   
				USRO.USRO_ID, 
				USRO.ROL_ID,
				USR.USR_ESTADO
				
				FROM 
				
				PERSONA PRS, 
				USUARIO USR, 
				USUARIO_ROL USRO
				
				WHERE 
				
				PRS.PRS_ID = USR.PRS_ID 
				AND USRO.USR_ID = USR.USR_ID 
				AND USRO.ROL_ID = 1
				AND USR.USR_ESTADO = '1'
				AND USRO.USRO_ESTADO = '1'
				AND USR.USR_NICK = ".$util->formatStringValue($usrNick)."
				LIMIT 1";

		$result = $mysqli->query($query);

		if ($result->num_rows > 0) {
			return $result->fetch_assoc(); 
		} else {
		    //echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	
	function selectUserDevice($mysqli, $usrId, $dspSerie, $dspDescription){
		global $util;
		$query = "SELECT 
		
				DSP.DSP_ID,
				DSP.DSP_SERIE,
				DSP.DSP_DESCRIPCION,
				DSP.DSP_TOKEN,
				DSP.DSP_HORA
				
				FROM 
				
				PERSONA PRS, 
				USUARIO USR, 
				USUARIO_ROL USRO,
				DISPOSITIVO DSP
				
				WHERE 
				
				PRS.PRS_ID = USR.PRS_ID 
				AND USRO.USR_ID = USR.USR_ID 
				AND DSP.USRO_ID = USRO.USRO_ID
				AND USRO.ROL_ID = 1
				AND USR.USR_ESTADO = '1'
				AND USRO.USRO_ESTADO = '1'
				AND DSP.DSP_SERIE = ".$util->formatStringValue($dspSerie)." 
				AND DSP.DSP_DESCRIPCION = ".$util->formatStringValue($dspDescription)."
				AND USR.USR_ID = ".$usrId."
				LIMIT 1";

		$result = $mysqli->query($query);

		if ($result->num_rows > 0) {
			return $result->fetch_assoc(); 
		} else {
		    //echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	 
	function selectDevice($mysqli, $dspId){
		global $util;
		$query = "SELECT 
		
				DSP.DSP_ID,
				DSP.DSP_SERIE,
				DSP.DSP_DESCRIPCION,
				DSP.DSP_TOKEN,
				DSP.DSP_HORA
				
				FROM 
				 
				DISPOSITIVO DSP
				
				WHERE 
				DSP.DSP_ID = ".$dspId;

		$result = $mysqli->query($query);

		if ($result->num_rows > 0) {
			return $result->fetch_assoc(); 
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}

	function selectQuestionsNewUsers($mysqli){
 
		$query = " SELECT 
		
					DISTINCT(PRG_ID) as idQuiz, 
					BLQ_ID, 
					BLQ_DESCRIPCION, 
					PRG_NUMERO, 
					PRG_DESCRIPCION as txtDescription, 
					PRG_URL_IMAGE as imgView, 
					PRG_TIPO as type, 
					OPC_IDENTIFICADOR as opcKey, 
					OPC_DESCRIPCION as opcDescription 
					
					from ( 

						SELECT blq1.BLQ_ID, blq1.BLQ_DESCRIPCION, prg1.PRG_ID, prg1.PRG_NUMERO, prg1.PRG_DESCRIPCION, prg1.PRG_URL_IMAGE, prg1.PRG_TIPO, opc1.OPC_IDENTIFICADOR, opc1.OPC_DESCRIPCION

						FROM BLOQUE blq1, 
						BLOQUE_PREGUNTA blpr1, 
						PREGUNTA prg1, 
						OPCION opc1
						 
						WHERE blq1.blq_id = blpr1.blq_id 
						AND prg1.prg_id = blpr1.prg_id
						AND opc1.prg_id = prg1.prg_id 
						AND blpr1.blpr_estado = '1'
						AND prg1.prg_estado = '1'
						AND opc1.opc_estado = '1'
						AND blq1.blq_estado = '2'
						
						UNION ALL

						SELECT blq2.BLQ_ID, blq2.BLQ_DESCRIPCION, prg2.PRG_ID, prg2.PRG_NUMERO, prg2.PRG_DESCRIPCION, prg2.PRG_URL_IMAGE, prg2.PRG_TIPO, NULL OPC_IDENTIFICADOR, NULL OPC_DESCRIPCION

						FROM BLOQUE blq2, 
						BLOQUE_PREGUNTA blpr2, 
						PREGUNTA prg2
						 
						WHERE blq2.blq_id = blpr2.blq_id 
						AND prg2.prg_id = blpr2.prg_id
						AND blpr2.blpr_estado = '1'
						AND prg2.prg_estado = '1'
						AND blq2.blq_estado = '2'
						AND prg2.prg_id NOT IN (
							SELECT DISTINCT(prg3.prg_id)

							FROM BLOQUE blq3, 
							BLOQUE_PREGUNTA blpr3, 
							PREGUNTA prg3, 
							OPCION opc3
							 
							WHERE blq3.blq_id = blpr3.blq_id 
							AND prg3.prg_id = blpr3.prg_id
							AND opc3.prg_id = prg3.prg_id 
							AND blpr3.blpr_estado = '1'
							AND prg3.prg_estado = '1'
							AND opc3.opc_estado = '1'
							AND blq3.blq_estado = '2'
						) 
						
					) preguntas
					order by PRG_NUMERO";
				
		$result = $mysqli->query($query);

		if ($result->num_rows > 0) {
			$retorno = Array();
			while($row = $result->fetch_assoc()) {
				$retorno[] = $row; 
    			}
			return $retorno;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	
	function insertPerson($mysqli, $prsNombres, $prsPrimerApellido, $prsSegundoApellido, $prsCorreo){ 
		global $util;
 	 
		$query = "INSERT INTO PERSONA (prs_nombres, prs_primer_apellido, prs_segundo_apellido, prs_correo) VALUES (".$util->formatStringValue($prsNombres).", ".$util->formatStringValue($prsPrimerApellido).", ".$util->formatStringValue($prsSegundoApellido).", ".$util->formatStringValue($prsCorreo).")";
	
		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}

	function insertUser($mysqli, $prsId, $usrNick, $usrPass){ 
		global $util;
	
		$query = "INSERT INTO USUARIO (prs_id , usr_nick, usr_password, usr_fecha_creacion, usr_estado) VALUES ($prsId, ".$util->formatStringValue($usrNick).", ".$util->formatStringValue($usrPass).", NOW(), 1)";
		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}

	function insertUserRol($mysqli, $usrId, $rolId){ 	

		$query = "INSERT INTO USUARIO_ROL (usr_id, rol_id, usro_estado) VALUES ($usrId, $rolId, ".ACTIVO_VALUE.")";
		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	
	function insertDevice($mysqli, $usroId, $dspSerie, $dspDescription, $dspToken, $dspSdkInt){ 	
		global $util;

		$query = "INSERT INTO DISPOSITIVO (usro_id, dsp_serie, dsp_descripcion, dsp_token, dsp_hora, dsp_sdk_int) VALUES ($usroId, ".$util->formatStringValue($dspSerie).", ".$util->formatStringValue($dspDescription).", ".$util->formatStringValue($dspToken).", '21:00:00', ".$util->formatStringValue($dspSdkInt).")";
		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	
	function insertAnswer($mysqli, $dspId, $prgId, $rspDescription, $rspOption, $rspTime){ 	
		global $util;

		$query = "INSERT INTO RESPUESTA (dsp_id, prg_id, rsp_descripcion, rsp_opcion, rsp_fecha) VALUES ($dspId, $prgId, ".$util->formatStringValue($rspDescription).", ".$util->formatStringValue($rspOption).", ".$util->formatStringValue($rspTime).")";

		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	
	function insertInfoSensor($mysqli, $dspId, $pscJson){ 	
		global $util;

		$query = "INSERT INTO POSICION (dsp_id, psc_json, psc_fecha)  VALUES ($dspId, ".$util->formatStringValue($pscJson).", NOW());";
		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
	
	function insertNotificationAnswer($mysqli, $rpPreguntaNotif, $rpRespuestaNotif, $rpRespNotifDate, $dspId){
		global $util;
		$query = "INSERT INTO NOTIF_DATA (pregunta, respuesta, fecha_resp_notificacion, dspId) VALUES (".$util->formatStringValue($rpPreguntaNotif).", ".$util->formatStringValue($rpRespuestaNotif).", ".$util->formatStringValue($rpRespNotifDate).", $dspId);";
		if ($mysqli->query($query) === TRUE) {
		    $last_id = $mysqli->insert_id;
		    return $last_id;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}

	function updateDevice($mysqli, $dspId){ 
		global $util;
	
		$query = "UPDATE DISPOSITIVO SET DSP_TOKEN = '1' WHERE DSP_ID = $dspId ";
		if ($mysqli->query($query) === TRUE) {
		    return TRUE;
		} else {
		    echo "Error: " . $query. "<br>" . $mysqli->error;
		    return FALSE;
		}
	}
 
}
?>
