<?php

require "constantes.php";
require_once "service.php";
require_once "utilities.php";


$dspValidateId = $_GET['rgs'];
$typeValidate = $_GET['rsp'];

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
 
if($typeValidate  == "hd5hrt3fd6"){

    $contenido = $service->updateDevice($mysqli, $dspValidateId);
    //$util->printArray($contenido, "Result");
    $contenido = json_encode($contenido);
    echo $contenido;
}


$mysqli->close();
?>
