<?php 
    if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==1){
        if(isset($_GET["user_id"])){
            $query = $PDOconn->prepare("SELECT user_surname, user_name, user_is_admin
            FROM users
            WHERE user_id=?;");
            $query->execute(array($_GET["user_id"]));
            $user = $query->fetch();
            if(isset($_POST["access"]) && $_POST["access"]!="none"){
                $query1 = $PDOconn->prepare("UPDATE users SET user_is_admin=?
                WHERE user_id=?;");
                $query1->execute(array($_POST["access"],$_GET["user_id"]));
                header("Location: index.php?action=all_users&success_update=1");
            }

?>

<div class="register-form-container">

    <h1 class="form-title">
        Зміна прав доступу для користувача <?php echo $user["user_surname"]." ".$user["user_name"]; ?>
    </h1>

    <form action=<?php echo "index.php?action=edit_user&user_id=".$_GET["user_id"]; ?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Поточні права доступу</h1>
                <input type="text" disabled class="input" value='<?php 
                if($user["user_is_admin"]){
                    echo "АДМІНІСТРАТОР";
                }
                else{
                    echo "КОРИСТУВАЧ";
                }
                ?>'>
            </div>

            <div class="form-field">
                <h1 class="form-title">Змінити права доступу</h1>
                <select name="access" class="input">
                    <option value="none">Оберіть права доступу для користувача</option>
                    <option value="false">Користувач</option>
                    <option value="true">Адміністратор</option>
                </select>
                <?php if(isset($_POST["access"]) && $_POST["access"]=="none"){
                    ?>
                        <p style="color: red;">Оберіть права доступу для користувача</p>
                    <?php
                } ?>
            </div>
    </div>
    <button class="button" type="submit">Зберегти зміни</div>
</form>
<br/>
<button class="delete"><a href="index.php?action=all_users">Скасувати</a></button>
</div>

<?php
    }
        else{
            ?>
                <h1 style="color: red;">Вказаного користувача не існує</h1>
                <button class="button"><a href="index.php?action=main">На головну</a></button>
            <?php
        }
    }
    else{
        ?>
            <h1 style="color: red;">Тільки головний адміністратор може редагувати інформацію про користувачів</h1>
            <button class="button"><a href="index.php?action=main">На головну</a></button>
        <?php
    }

?>