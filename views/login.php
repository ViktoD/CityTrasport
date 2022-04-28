<?php 
    if(isset($_POST["user_login"]) && isset($_POST["user_password"])){

        $query = $PDOconn->prepare("SELECT user_id, user_login, user_password, user_is_admin, user_surname, user_name
        FROM users
        WHERE user_login=?;");
        $query->execute(array($_POST["user_login"]));
        $user = $query->fetch();
        if($user && password_verify($_POST["user_password"],$user["user_password"])){
            $_SESSION["user"] = ["user_id"=>$user["user_id"], "user_login"=>$user["user_login"], "user_surname"=>$user["user_surname"], "user_name"=>$user["user_name"], "user_is_admin"=>$user["user_is_admin"]];
            header("Location: index.php?action=index");
        }
        else if(isset($_GET["submitted"])){
            ?>
                <h1 style="color: red;">Невірний логін або пароль. Спробуйте ще раз</h1>
            <?php
        }

    }
?>

<div class="register-form-container">

    <h1 class="form-title">
        Вхід у систему
    </h1>
    
    <form action="index.php?action=login&submitted=1" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <input type="text" class="input" name="user_login" placeholder="Введіть логін">
            </div>

            <div class="form-field">
                <input type="password" name="user_password" class="input" placeholder="Введіть пароль">
            </div>
        </div>
        <button class="button" type="submit">Увійти</button>
    </form>
</div>