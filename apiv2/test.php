<?php

echo "POST ".$_POST['type'];
echo "REQUEST ".$_REQUEST['type'];
echo "GET ".$_GET['type'];

$type = $_REQUEST['type'];


switch ($type) {
    case "vero":
        echo "Verito";
        break;		
	case "giova":
        echo "Giovanny ";
        break;
    default:
        echo "URL sin accion...-----.____.".$type.";;;;;;;;";
        break;
}

?>
