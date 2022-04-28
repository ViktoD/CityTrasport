<?php 
    $match = false;
    $query3 = "SELECT user_login FROM users;";
    if($match==false && isset($_POST["user_name"]) && !empty($_POST["user_name"])&&isset($_POST["user_surname"]) && !empty($_POST["user_surname"])
    &&isset($_POST["user_email"]) && !empty($_POST["user_email"])&&isset($_POST["user_login"]) && !(preg_match("/[!,.@#$%^&*()+=~?`><№;:]/",$_POST["user_login"]) || strlen($_POST["user_login"]) < 4)
    &&isset($_POST["user_password"]) && (preg_match("/[A-ZА-Я]/",$_POST["user_password"]) && preg_match("/[a-zа-я]/",$_POST["user_password"]) && preg_match("/\d/",$_POST["user_password"]) && strlen($_POST["user_password"]) >= 8)){

        $maxId = $PDOconn->query("SELECT MAX(user_id) FROM users")->fetch();
        $query = $PDOconn->prepare("INSERT INTO users VALUES(?,?,?,?,?,?,?);");
        $hashpassword = password_hash($_POST["user_password"],PASSWORD_BCRYPT);
        $query->execute(array($maxId["max"]+1,$_POST["user_surname"],$_POST["user_name"],$_POST["user_email"],$hashpassword,"false",$_POST["user_login"]));
        header("Location: index.php?action=registration_successful");
    }

?>

<div class="register-form-container">

    <h1 class="form-title">
        Реєстрація
    </h1>
    
    <form action="index.php?action=registration" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <input type="text" name="user_surname" class="input" placeholder="Введіть прізвище" value='<?php
                if(isset($_POST["user_surname"]) && !empty($_POST["user_surname"])){
                    echo $_POST["user_surname"];
                }
                ?>'>
                <?php if(isset($_POST["user_surname"]) && empty($_POST["user_surname"])){
                    ?>
                        <p style="color: red;">Введіть ваше прізвище</p>
                    <?php
                } ?>
            </div>
            
            <div class="form-field">
                <input type="text" name="user_name" class="input" placeholder="Введіть ім'я" value='<?php 
                if(isset($_POST["user_name"]) && !empty($_POST["user_name"])){
                    echo $_POST["user_name"];
                }
                 ?>'>
                <?php if(isset($_POST["user_name"]) && empty($_POST["user_name"])){
                    ?>
                        <p style="color: red;">Введіть ваше ім'я</p>
                    <?php
                } ?>
            </div>

            <div class="form-field">
                <input type="email" class="input" name="user_email" placeholder="Ваша електронна адреса" value='<?php
                    if(isset($_POST["user_email"]) && !empty($_POST["user_email"])){
                        echo $_POST["user_email"];
                    }
                ?>'>
                <?php if(isset($_POST["user_email"]) && empty($_POST["user_email"])){
                    ?>
                        <p style="color: red;">Введіть вашу електронну адресу</p>
                    <?php
                } ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="user_login" placeholder="Введіть логін" value='<?php 
                if(isset($_POST["user_login"]) && !(preg_match("/[!,.@#$%^&*()+=~?`><№;:]/",$_POST["user_login"]) || strlen($_POST["user_login"]) < 4)){
                    echo $_POST["user_login"];
                }
                ?>'>
                <?php
                    if(isset($_POST["user_login"])){
                        foreach($PDOconn->query($query3) as $logins){
                            if($logins["user_login"]==$_POST["user_login"]){
                                $match = true;
                                ?>
                                    <p style="color: red;">Користувач із вказаним логіном вже існує. Будь ласка виберіть інший логін</p>
                                <?php
                                break;
                            }
                        }
                    }
                
                    if(isset($_POST["user_login"]) && (preg_match("/[!,.@#$%^&*()+=~?`><№;:]/",$_POST["user_login"]) || strlen($_POST["user_login"]) < 4)){
                        ?>
                            <p style="color: red;">Ваш логін може містити лише кириличні або латинські літери, а також цифри. Довжина логіна має складати не менше 4 символів </p>
                        <?php
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="password" name="user_password" class="input" placeholder="Введіть пароль" value='<?php 
                if(isset($_POST["user_password"]) && (preg_match("/[A-ZА-Я]/",$_POST["user_password"]) && preg_match("/[a-zа-я]/",$_POST["user_password"]) && preg_match("/\d/",$_POST["user_password"]) && strlen($_POST["user_password"]) >= 8)){
                    echo $_POST["user_password"];
                }
                ?>'>
                <?php
                    if(isset($_POST["user_password"]) && (!preg_match("/[A-ZА-Я]/",$_POST["user_password"]) || !preg_match("/[a-zа-я]/",$_POST["user_password"]) || !preg_match("/\d/",$_POST["user_password"]) || strlen($_POST["user_password"]) < 8)){
                        ?>
                            <p style="color: red;">Пароль обов'язково має містити принаймні одну велику літеру, одну малу літеру та одну цифру. Довжина паролю має бути не меншою 8 символів.</p>
                        <?php
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="password" class="input" name="retype_password" placeholder="Повторіть пароль" value='<?php 
                if(isset($_POST["user_password"]) && isset($_POST["retype_password"]) && $_POST["user_password"]==$_POST["retype_password"]){
                    echo $_POST["retype_password"];
                }
                ?>'>
                <?php
                    if(isset($_POST["user_password"]) && isset($_POST["retype_password"]) && !($_POST["user_password"]==$_POST["retype_password"])){
                    ?>
                        <p style="color: red;">Паролі не співпадають</p>
                    <?php
                    }
                ?>

            </div>
        </div>
        <button class="button" type="submit">Зареєструватись</div>
    </form>
</div>