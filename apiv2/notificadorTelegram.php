<?php
 
 require_once "constantes.php";
 require_once "notificacionTelegram.php"; 

  $mensaje = $_GET['msj']; 
   
	$notificacionClass = new Notificacion(); 
	  if($mensaje != null){  
			$notificacionClass->notificar($mensaje, CHATIDGROUP);
			echo "Notificado";
	  }else{ 
		  $notificacionClass->notificar("Ingrese un parametro", CHATIDGROUP); 
		  echo "Ingrese un parametro <br>"; 
		}
	

?>