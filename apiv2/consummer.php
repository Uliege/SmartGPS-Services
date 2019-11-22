<?php

require "constantes.php";
require_once "service.php";
require_once "utilities.php";

//echo "POST ".$_POST['type'];
//echo "REQUEST ".$_REQUEST['type'];
//echo "GET ".$_GET['type'];

//$type = $_GET['type'];
//$dspId = $_GET['dspId'];

//echo "...........";
$type = $_POST['type'];
//echo "type ".$type;
//echo "...........";

$dspId = $_POST['dspId'];

$userAuth = $_POST['userName'];
$passAuth = $_POST['passUser'];
$tokenAuth = $_POST['fcmToken'];

$dspSerie = $_POST['dspSerie'];
$dspDescription = $_POST['dspDescription'];
$dspSdkInt = $_POST['dspSdkInt']; 
$dspToken = $_POST['dspToken']; 
$answers = $_POST['answers'];
  
$sensorInfo = $_POST['sensorInfo'];

$appName = $_POST['appName'];

//Ingreso de las respuestas de las notificaciones
$rpPreguntaNotif = $_POST['rpPregunta'];
$rpRespuestaNotif = $_POST['rpRespuesta'];
$rpRespNotifDate = $_POST['rpFecha'];
  
//$type = $_GET['type'];
//$userAuth = $_GET['userName'];

$service = new Service();
$util = new Utilities(); 


$mysqli = new mysqli(SERVER_NAME, USERNAME_DB, PASSWORD_DB, DATA_BASE);
if (mysqli_connect_errno()) {
    printf("Error de conexion: %s\n", mysqli_connect_error());
    exit();
}
if (!$mysqli->set_charset("utf8")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
    exit();
}


switch ($type) {
    case "questionsWithoutSession": 
        $contenido = $service->getQuestionsNewUsers($mysqli);
        //$util->printArray($contenido, "Result");
        $contenido = json_encode($contenido); 
        echo $contenido;
        $contenido = null;
        $type = null;
        break;
    case "authUser":
        $contenido = $service->getAuth($mysqli, $userAuth, NULL, NULL );
        //$util->printArray($contenido, "Result");
        $contenido = json_encode($contenido);
        echo $contenido;
        $contenido = null;
        $type = null;
        break;
    case "setUser": 
        $answers = json_decode($answers);
        $contenido = $service->setUser($mysqli, $answers[0]->{'txtDescription'}, $answers[0]->{'txtDescription'}, $answers[0]->{'txtDescription'}, $answers[1]->{'txtDescription'}, $answers[1]->{'txtDescription'}, NULL, $dspSerie, $dspDescription, "0", $dspSdkInt, $answers);
        //$util->printArray($contenido, "Result");
        $contenido = json_encode($contenido);
        echo $contenido;
        $contenido = null;
        $type = null;
        break;
    case "setInfoSensor":
        $sensorInfo2 = json_decode($sensorInfo);
        $contenido = $service->setInfoSensor($mysqli, $dspId, $sensorInfo2);
        //$util->printArray($contenido, "Result");
        $contenido = json_encode($contenido);
        echo $contenido;
        $contenido = null;
        $type = null;
        break;
    case "getDevice":
        $contenido = $service->getDevice($mysqli, $dspId);
        //$util->printArray($contenido, "Result");
        $contenido = json_encode($contenido);
        echo $contenido;
        $contenido = null;
        $type = null;
        break;
	case "setNotificationAnswer":
		$contenido = $service->setNotificationAnswer($mysqli, $dspId, $rpPreguntaNotif, $rpRespuestaNotif, $rpRespNotifDate);
		$contenido = json_encode($contenido);
		echo $contenido;
		$contenido = null;
        $type = null;
        break;
    default:
        echo "URL sin accion...---..--..".$type;
        break;
}

$mysqli->close();
?>
