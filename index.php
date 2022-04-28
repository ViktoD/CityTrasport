<?php
	session_start(); 
	if(!isset($_GET["marshrut_id"])){
		require_once("./layout/header.php");
		require_once("./layout/left_menu.php");
	}
	require_once("./views/connect.php");
	$filename="";
	if(isset($_GET["action"]))
	{ 
		$filename = "./views/".$_GET["action"].".php";
	}

	if(isset($_GET["action"]) && file_exists($filename))
	{
		require_once($filename);
	}


	else
	{
		require_once("./views/main.php");
	}

	if(!isset($_GET["marshrut_id"])){
    require_once("./layout/footer.php");
	}