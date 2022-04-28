<?php 
if(!empty($_SESSION["user"])){
    $query = $PDOconn->prepare("SELECT * FROM users WHERE user_id=?;");
    $query->execute(array($_SESSION["user"]["user_id"]));
    $user = $query->fetch();

    if(isset($_GET["submitted"]) && isset($_POST["user_name"]) && !empty($_POST["user_name"])&&
    isset($_POST["user_surname"]) && !empty($_POST["user_surname"]) && isset($_POST["user_email"]) && !empty($_POST["user_email"])
    &&isset($_POST["user_login"]) && !empty($_POST["user_login"])){
        $query1 = $PDOconn->prepare("UPDATE users
        SET user_name=?, user_surname=?,user_email=?,user_login=?
        WHERE user_id=?;");
        $query1->execute(array($_POST["user_name"],$_POST["user_surname"],$_POST["user_email"],$_POST["user_login"],$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=index&success_update=1");
    }
?>

<h1>Оновлення профілю користувача <?php echo $_SESSION["user"]["user_surname"]." ".$_SESSION["user"]["user_name"]; ?></h1>

<div class="register-form-container">

    <form action="index.php?action=edit_profile&submitted=1" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Прізвище користувача</h1>
                <input type="text" name="user_surname" class="input" placeholder="Введіть прізвище" value='<?php
                    echo $user["user_surname"];
                ?>'>
                <?php if(isset($_POST["user_surname"]) && empty($_POST["user_surname"])){
                    ?>
                        <p style="color: red;">Введіть ваше прізвище</p>
                    <?php
                } ?>
            </div>
            
            <div class="form-field">
                <h1 class="form-title">Ім'я користувача</h1>
                <input type="text" name="user_name" class="input" placeholder="Введіть ім'я" value='<?php 
                    echo $user["user_name"];
                 ?>'>
                <?php if(isset($_POST["user_name"]) && empty($_POST["user_name"])){
                    ?>
                        <p style="color: red;">Введіть ваше ім'я</p>
                    <?php
                } ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Електронна пошта користувача</h1>
                <input type="email" class="input" name="user_email" placeholder="Ваша електронна адреса" value='<?php
                    echo $user["user_email"];
                ?>'>
                <?php if(isset($_POST["user_email"]) && empty($_POST["user_email"])){
                    ?>
                        <p style="color: red;">Введіть вашу електронну адресу</p>
                    <?php
                } ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Логін користувача</h1>
                <input type="text" class="input" name="user_login" placeholder="Введіть логін" value='<?php 
                    echo $user["user_login"];
                ?>'>
                  <?php if(isset($_POST["user_login"]) && empty($_POST["user_login"])){
                    ?>
                        <p style="color: red;">Введіть ваш логін</p>
                    <?php
                } ?>
            </div>
        </div>
        <button class="button" type="submit">Зберегти зміни</div>\
        <br/>
        <button class="delete"><a href="index.php?action=main">Скасувати</a></button>
    </form>
</div>
<?php
}
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач може редагувати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>  
    <?php
}?>