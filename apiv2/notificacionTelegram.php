<?php 

 include "constantes.php";
 
 class Notificacion{
	  
	function notificar($notificacion,$chatId){ 	
		$data = [
			'chat_id' => $chatId,
			'text' => $notificacion
		]; 
		$response = file_get_contents( URLSENDMESSAGE . http_build_query($data) );
		return $response;
	}
 }
?>