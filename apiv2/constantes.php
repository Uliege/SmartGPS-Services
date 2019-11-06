<?php

	//Action
	define("ACTIVO_VALUE", 1);
	define("INACTIVO_VALUE", 0);

	//Rols
	define("ROL_ID_USER", 1);
	
	//Database
	define("SERVER_NAME", "70.40.217.137");
	define("DATA_BASE", "gmoncayo_smart_gps_v2");
	define("USERNAME_DB", "gmoncayo_gps");
	define("PASSWORD_DB", "smart_gps");
 
	//ApiTelegram
	define("APITOKEN", "668505391:AAHV0viLbSvnbZfH1oj-tj33MSoNmZs55EU");
	define("CHATID", "567694053");  
	define("CHATIDGROUP", "-257215878");
	define("URLBOT", "https://api.telegram.org/bot");
	define("URLUPDATES", URLBOT.APITOKEN."/getUpdates");
	define("URLSENDMESSAGE", URLBOT.APITOKEN."/sendMessage?");

	//Mail
	define("PORT_MAIL", 465);
	define("SECURE_MAIL", 'ssl'); 
	define("FROM_NAME_MAIL", "SmartGPS");

?>