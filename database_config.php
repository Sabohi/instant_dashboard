<?php
//***************** DATABASE CONSTANTS *************************
//**************************************************************

	//~ $filecontent = file_get_contents("/var/www/html/"._BASEDIR_."/configs/config.txt");
	$filecontent = file_get_contents("/var/www/html/CZCRM/configs/config.txt");
	$fileArr = json_decode($filecontent,true);
	//~ session_start();

	define("DB_HOST_WRITE",$fileArr["DB_HOST_WRITE"]);
	define ("DB_HOST", $fileArr["DB_HOST_WRITE"]);
	
 	define("DB_SLAVE_WRITE",$fileArr["DB_SLAVE_WRITE"])       ;
    define("DB_HOST_READ",$fileArr["DB_HOST_READ"])      ;
	define("DB_SLAVE_READ",$fileArr["DB_SLAVE_READ"])      ;
	define ("DB_USERNAME",  "root");
	define ("DB_PASSWORD",  "sqladmin");
	define ("DB_PREFIX", "crm_manager_");
	if(!empty($_SESSION["CLIENT_ID"])){
		define ("DB_NAME", "crm_manager_".$_SESSION["CLIENT_ID"]);
	}
	else
	{
	    define ("DB_NAME", "czcrm_generic");
	}
	define ("GDB_NAME", "czcrm_generic");
	define ("DASH_DB_NAME", "data_visualization");
	
	//Service DB name is the name of the DB that will be used for single clients
	define ("SERVICE_DB_NAME", "crm_manager_1");
	
	
	//~ Constants for mongodb
	define ("MDB_HOST",  "localhost");
	define ("MDB_USERNAME",  "root");
	define ("MDB_PASSWORD",  "");
	define ("MDB_PORT",  "27017");
	

	define ("STRING", "STRING");
	define ("INT", "INT");
	define ("MYSQL_FUNCTION", "FUNCTION");

	define ("NUMBER", "NUMBER");
	define ("ORACLE_PASSWORD", "ORA_PASS");
	define ("MYSQL_PASSWORD", "MYSQL_PASS");

	// define ("DB_HOST_ORACLE", "172.16.3.40");
	// define ("DB_USERNAME_ORACLE", "tvttest");
	// define ("DB_PASSWORD_ORACLE", "tvttest123");
	// define ("DB_NAME_ORACLE", "TEST");


	define ("_INSERT_", 1);
	define ("_DELETE_", 2);
	define ("_UPDATE_", 3);
	define ("_CREATE_", 4);
?>
