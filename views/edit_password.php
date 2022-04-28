<?php 
  if(!empty($_SESSION["user"])){  
    $query1 = $PDOconn->prepare("SELECT user_password FROM users
    WHERE user_id=?;");
    $query1->execute(array($_SESSION["user"]["user_id"]));
    $password = $query1->fetch();

    if(isset($_POST["user_password"]) && password_verify($_POST["user_password"],$password["user_password"]) && !empty($_POST["user_password"])
    &&isset($_POST["user_new_password"]) && (preg_match("/[A-ZА-Я]/",$_POST["user_new_password"]) && preg_match("/[a-zа-я]/",$_POST["user_new_password"]) && preg_match("/\d/",$_POST["user_new_password"]) && strlen($_POST["user_new_password"]) >= 8)
    &&isset($_POST["user_new_password"]) && isset($_POST["user_new_retypepassword"]) && $_POST["user_new_password"]==$_POST["user_new_retypepassword"]){
        $passwordHash = password_hash($_POST["user_new_password"],PASSWORD_BCRYPT);
        $query2 = $PDOconn->prepare("UPDATE users 
        SET user_password=?
        WHERE user_id=?;");
        $query2->execute(array($passwordHash,$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=index&success_update=1");
    }

?>
<div class="register-form-container">
    <h1 class="form-title">
    Зміна паролю
    </h1>

    <form action="index.php?action=edit_password" method="POST">

    <div class="form-fields">
        <div class="form-field">
            <h1 class="form-title">Старий пароль</h1>
            <input type="password" name="user_password" class="input" placeholder="Введіть старий пароль" value='<?php
                if(isset($_POST["user_password"]) && !empty($_POST["user_password"])){
                    echo $_POST["user_password"];
                }
            ?>'>
            <?php 
                if(isset($_POST["user_password"]) && empty($_POST["user_password"])){
                    ?>
                        <p style="color: red;">Введіть старий пароль</p>
                    <?php
                }
            ?>
            <?php 
                if(isset($_POST["user_password"]) && !password_verify($_POST["user_password"],$password["user_password"]) && !empty($_POST["user_password"])){
                    ?>
                        <p style="color: red;">Старий пароль вказано невірно</p>
                    <?php
                }
            ?>
        </div>
        <div class="form-field">
            <h1 class="form-title">Новий пароль</h1>
            <input type="password" name="user_new_password" class="input" placeholder="Введіть новий пароль" value='<?php 
            if(isset($_POST["user_new_password"]) && (preg_match("/[A-ZА-Я]/",$_POST["user_new_password"]) && preg_match("/[a-zа-я]/",$_POST["user_new_password"]) && preg_match("/\d/",$_POST["user_new_password"]) && strlen($_POST["user_new_password"]) >= 8)){
                echo $_POST["user_new_password"];
            }
            ?>'>
            <?php
                if(isset($_POST["user_new_password"]) && (!preg_match("/[A-ZА-Я]/",$_POST["user_new_password"]) || !preg_match("/[a-zа-я]/",$_POST["user_new_password"]) || !preg_match("/\d/",$_POST["user_new_password"]) || strlen($_POST["user_new_password"]) < 8)){
                    ?>
                        <p style="color: red;">Пароль обов'язково має містити принаймні одну велику літеру, одну малу літеру та одну цифру. Довжина паролю має бути не меншою 8 символів.</p>
                    <?php
                }
            ?>
        </div>
        <div class="form-field">
            <h1 class="form-title">Повторіть новий пароль</h1>
            <input type="password" name="user_new_retypepassword" class="input" placeholder="Повторіть новий пароль" value='<?php 
            if(isset($_POST["user_new_password"]) && isset($_POST["user_new_retypepassword"]) && $_POST["user_new_password"]==$_POST["user_new_retypepassword"]){
                echo $_POST["user_new_retypepassword"];
            }
            ?>'>
            <?php
                if(isset($_POST["user_new_password"]) && isset($_POST["user_new_retypepassword"]) && !($_POST["user_new_password"]==$_POST["user_new_retypepassword"])){
                ?>
                    <p style="color: red;">Паролі не співпадають</p>
                <?php
                }
            ?>
        </div>
    </div>
    <button class="button" type="submit">Зберегти зміни</button>
    </form>
</div>
<br/>
<button class="delete"><a href="index.php?action=main">Скасувати</a></button>
<?php } 
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач може редагувати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>