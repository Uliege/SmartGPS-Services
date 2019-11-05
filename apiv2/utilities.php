<?php

class Utilities{
	
	function formatStringValue($value){
		return "'".$value."'";
	}

	function printArray($array, $tittle){
	   echo "<h1> ".$tittle." </h1>";
	   echo "<pre>";
	   print_r($array);
	   echo "</pre>";
	}
	
	function sendAlert($message, $page){
		echo "<script 
				type=\"text/javascript\">alert('$message'); 
				window.location='$page';
			  </script>";
	}

	function inputFormat($data) {
	  $data = trim($data);
	  $data = stripslashes($data); 
	  return $data;
	}

}

?>