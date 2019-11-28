<?php
include "constantes.php";
require_once "implementation.php"; 
require_once "mail.php"; 

$impl = new Implementation();
$mail = new Mail();
 
class Service{
 
	function getAuth($mysqli, $usrNick, $usrPass, $fcmToken){
		global $impl;
		$list =  $impl->selectUserAuth($mysqli, $usrNick, $usrPass, $fcmToken);
		if($list !== FALSE){
			return $list;
		}else{
			return "Error al autenticar.";
		}
	}
	 
	function getQuestionsNewUsers($mysqli){
		global $impl;
		
		$filterList;		
		$list =  $impl->selectQuestionsNewUsers($mysqli);
		$lastId = 0;
		   
		foreach($list as $item => $value){
			 
			if($value["idQuiz"] == $lastId && $value["opcKey"] != NULL){
				
				foreach($list as $itemRec => $valueRec){
					if($valueRec["idQuiz"] == $lastId){  
						$value["opcOptions"][] = array("opcKey"=> $valueRec["opcKey"], "opcDescription"=> $valueRec["opcDescription"]);
					}
				} 
				$filterList[count($filterList)-1] = $value;
				
			}else if($value["idQuiz"] != $lastId){
				$filterList[] = $value;
			}
			$lastId = $value["idQuiz"];
		}
		$list = $filterList;
		
		if($list !== FALSE){
			return $list;
		}else{
			return "Error al buscar preguntas iniciales.";
		}
	}

	function getDevice($mysqli, $dspId){
		global $impl;
		$device =  $impl->selectDevice($mysqli, $dspId);
		
		$dspId = $device['DSP_ID'];
		$dspToken = $device['DSP_TOKEN'];

		//if($dspToken == "1"){
		//	$dspToken = "Cuenta activa.";
		//}else{
		//	$dspToken = "Sin verificar información. Revise su correo electrónico.";
		//}

		$device = array(
			"dspId"=> $dspId,
			"fcmToken"=> $dspToken
		);

		if($device !== FALSE){
			return $device;
		}else{
			return "Error al verificar dispositivo.";
		}
	}
	
	function setUser($mysqli, $prsNombres, $prsPrimerApellido, $prsSegundoApellido, $prsCorreo, $usrNick, $usrPass, $dspSerie, $dspDescription, $dspToken, $dspSdkInt, $answers){ 	
		global $impl;
		global $mail;

		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(); 
		
		$userInfo = $impl->selectUserAuth($mysqli, $usrNick, $usrPass, $fcmToken);
		if(!$userInfo){
			$prsId =  $impl->insertPerson($mysqli, $prsNombres, $prsPrimerApellido, $prsSegundoApellido, $prsCorreo);
			$usrId =  $impl->insertUser($mysqli, $prsId, $usrNick, $usrPass);
			$usroId = $impl->insertUserRol($mysqli, $usrId, ROL_ID_USER); 
		}else{
			$prsId = $userInfo['PRS_ID'];
			$usrId = $userInfo['USR_ID'];
			$usroId = $userInfo['USRO_ID'];
			$prsNombres = $userInfo['PRS_NOMBRES'];
			$prsCorreo = $userInfo['PRS_CORREO'];
			$usrNick = $userInfo['USR_NICK'];
		}

		$dspHora = "20:00:00";
		$deviceUser = $impl->selectUserDevice($mysqli, $usrId, $dspSerie, $dspDescription);
		if(!$deviceUser){

			$dspId = $impl->insertDevice($mysqli, $usroId, $dspSerie, $dspDescription, $dspToken, $dspSdkInt); 

			$subject = "Activar cuenta. SmartGPS.";
			$body = $mail->createBodyActivacion($mail->createLink($dspId));
			$mails = array();
			$mails[] = $prsCorreo;

			$mail->sendMail("mail.gmoncayoresearch.com", "smartgps@gmoncayoresearch.com", 'smartgps2018', PORT_MAIL, SECURE_MAIL, "smartgps@gmoncayoresearch.com", FROM_NAME_MAIL, $subject, $body, $mails);

		}else{
			$dspId = $deviceUser['DSP_ID'];
			$dspHora = $deviceUser['DSP_HORA'];
			$dspToken = $deviceUser['DSP_TOKEN'];
		}
		 
		
		foreach($answers as $item=>$value){   
			$rspId =  $impl->insertAnswer($mysqli, $dspId, $value->{'idQuiz'}, $value->{'txtDescription'}, $value->{'opcOptions'}[0]->{'opcKey'}, $value->{'dateQuiz'});
 
			if($rspId == 0){ 
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				return "Error al ingresar la respuesta a la pregunta ".$prgId;
			}
		}

		//if($dspToken == "1"){
		//	$dspToken = "Cuenta activa.";
		//}else{
		//	$dspToken = "Sin verificar información. Revise su correo electrónico.";
		//}

		$response = array(
			"id" => $usrId,
			"nombres" => $prsNombres,
			"usuario" => $usrNick,
			"correoElectronico" => $prsCorreo,
			"usroId" => $usroId,
			"rolId" => "1",
			"dspId" => $dspId,
			"fcmToken" => $dspToken,
			"estado" => "1",
			"horaSinc" => $dspHora
		);
			
		if($dspId != 0){
			$mysqli->commit();
			$mysqli->autocommit(TRUE);
			return $response;
		}else{
			$mysqli->rollback();
			$mysqli->autocommit(TRUE);
			return 'Error al ingresar al usuario '.$usrNick;
		}
	}
	
	function setInfoSensor($mysqli, $dspId, $sensorInfo){ 	
		global $impl;
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(); 

		$dsp = $impl->selectDevice($mysqli, $dspId);
		$dspToken = $dsp['DSP_TOKEN'];
		
		if($dspToken == "1"){
			
			foreach($sensorInfo as $value){ 
			
				$pscId =  $impl->insertInfoSensor($mysqli, $dspId, json_encode($value));
				if($pscId == 0){
					$mysqli->rollback();
					$mysqli->autocommit(TRUE);
					return "Error al ingresar la información del dispositivo ".$dspId;
				}
			}

		}else{

			$mysqli->commit();
			$mysqli->autocommit(TRUE);
			return 'Fail';
		}

		$mysqli->commit();
		$mysqli->autocommit(TRUE);
		return 'OK';
	}
	
	function setNotificationAnswer($mysqli, $dspId, $rpPreguntaNotif, $rpRespuestaNotif, $rpRespNotifDate){
		global $impl;
		$mysqli->autocommit(FALSE);
		$mysqli->begin_transaction(); 

		$dsp = $impl->selectDevice($mysqli, $dspId);
		$dspToken = $dsp['DSP_TOKEN'];
		
		if($dspToken == "1"){
			
			$pscId =  $impl->insertNotificationAnswer($mysqli, $rpPreguntaNotif, $rpRespuestaNotif, $rpRespNotifDate, $dspId);
			if($pscId == 0){
				$mysqli->rollback();
				$mysqli->autocommit(TRUE);
				return "Error al ingresar la información del dispositivo ".$dspId;
			}

		}else{

			$mysqli->commit();
			$mysqli->autocommit(TRUE);
			return 'Fail';
		}

		$mysqli->commit();
		$mysqli->autocommit(TRUE);
		return 'OK';
	}
	
	function getNotificationQuestion($mysqli, $pregId){
		global $impl;
		$listPreg = $impl->selectQuestion($mysqli, $pregId);
		if($listPreg !== FALSE){
			return $listPreg;
		}else{
			return "Error";
		}
	}

	function updateDevice($mysqli, $dspId){ 	
		global $impl;
		$dsp  =  $impl->updateDevice($mysqli, $dspId);
		if(!$dsp){
			return "Error al actualizar el estado del dispositivo ".$dspId;
		}else{
			return "Dispositivo verificado.";
		}
	}

}
?>
