<ul class="navbar">
			<li><a href="index.php?action=main">Головна</a></li>
			<li><a href="index.php?action=find_information">Переглянути...</a></li>
			<?php
				if(!empty($_SESSION["user"])){
			?>
			<li><a href="index.php?action=logout">Вийти</a></li>
			<li><a href="index.php?action=edit_profile">Оновити профіль</a></li>
			<li><a href="index.php?action=edit_password">Змінити пароль</a></li>
			<?php if($_SESSION["user"]["user_id"]==1){
				?>
			<li><a href="index.php?action=all_users">Всі користувачі</a></li>
				<?php
			} ?>
			<?php
			} 
				else{
			?>
			<li><a href="index.php?action=login">Увійти</a></li>
			<li><a href="index.php?action=registration">Зареєструватись</a>
			<?php
	
			}?>
</ul>