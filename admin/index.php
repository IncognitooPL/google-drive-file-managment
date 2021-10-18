<?php
  session_start();

  	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *');

	if((isset($_SESSION['loged'])) && ($_SESSION['loged'] == true))
	{
    	include("websites/main.php");
	}

	if((isset($_SESSION['loged']) == false) OR (isset($_SESSION['loged']) == null)  && ($_SESSION['loged'] == false))
	{
    	include("websites/login.php");
	}
