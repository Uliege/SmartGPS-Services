 <?php

require("constantes.php");
require("phpmailer/class.phpmailer.php");
require("phpmailer/class.smtp.php");

class Mail{

	function sendMail($host, $username, $password, $port, $secure, $from, $fromName, $subject, $body, $mails){

		$mail = new PHPMailer();
		$mail->IsSMTP();
		//$mail->SMTPDebug = 4;
		$mail->SMTPAuth = true;
		$mail->Host = $host; 
		$mail->Username = $username; 
		$mail->Password = $password; 
		$mail->Port = $port; 
		$mail->SMTPSecure = $secure;
		$mail->From = $from;
		$mail->FromName = $fromName; 
		foreach($mails as $contactMail){
			$mail->AddAddress($contactMail);
		}
		$mail->IsHTML(true); 
		$mail->Subject = $subject; 
		$mail->Body = $body;

		$exito = $mail->Send(); 
		if($exito){
			//echo 'El correo fue enviado correctamente.';
		}else{
			//echo $exito;
			//echo '<br> No se pudo enviar el correo. Contacta a un administrador.';
		}
	}

	function createBodyActivacion($enlace){
		
		$body = "<br>";
		$body .= "<br>Bienvenido a <strong>SmartGPS</strong>.<br/>";
		$body .= "<br>";
		$body .= "<br>";
		$body .= "Para verificar tu cuenta ingresa al siguiente link:.<br/>";
		$body .= "<br>";
		$body .= "<br>";
		$body .= "<strong><a href=\"".$enlace."\">Activar cuenta</a></strong>";
		$body .= "<br>";
		$body .= "<br>";
		$body .= "<br>";
		$body .= "Saludos.";
		$body .= "<br>";
		$body .= "<br>";
		
		return $body;
	}

	function createLink($value){
		
		$link = "http://gmoncayoresearch.com/SmartGPS/api/validate.php?rsp=hd5hrt3fd6&rgs=";
		$link .= $value;
		
		return $link;
	}
}