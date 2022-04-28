<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title>Міські пасажирські перевезення </title>
	<link rel="stylesheet" href="./css/style.css"/>
	<script src="./js/jquery-3.6.0.min.js"></script>

</head>

<body>
	
<?php 
	if(empty($_SESSION)){
?>
	<h1>Ви не авторизовані</h1>		
<?php
	}	
	else{
?>
	<h1>Ви зайшли під ім'ям <?php echo $_SESSION["user"]["user_surname"]." ".$_SESSION["user"]["user_name"];?></h1>
<?php
	}
?>

	<div class='header' id='top'>
		<h1 class="title">
			Міські перевезення
		</h1>
	</div>
