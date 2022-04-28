<?php 
 if(!empty($_SESSION) && $_SESSION["user"]["user_id"]==1){
    $query1 = "SELECT * FROM users;";
    if(isset($_GET["success_delete"])){
        ?>
            <h1 style="color: red;">Інформацію про користувача було успішно видалено</h1>
        <?php
        }
        if(isset($_GET["success_update"])){
        ?>
            <h1 style="color: orange;">Дані про користувача було успішно оновлено</h1>
        <?php
        } 
?>
 
<h1>Головний адміністратор <?php $user = $PDOconn->query($query1)->fetch(); echo $user["user_surname"]." ".$user["user_name"]; ?></h1>
<p class="form-title">Список всіх користувачів</p>

<table class="content-table">
    <thead>
        <th>Прізвище користувача</th>
        <th>Ім'я користувача</th>
        <th>Електронна пошта користувача</th>       
        <th>Логін користувача</th>
        <th>Чи є користувач адміністратором?</th>
    </thead>

    <tbody>
    <?php foreach($PDOconn->query($query1) as $user) {?>
            <tr>
                <td>
                    <?= $user["user_surname"];?>
                </td>
                <td>
                    <?= $user["user_name"];?>
                </td>
                <td>
                    <?= $user["user_email"]; ?>
                </td>
               
                <td>
                    <?= $user["user_login"]; ?>
                </td>
                <td>
                    <?php
                        if($user["user_id"]==1){
                            echo "Права доступу: ГОЛОВНИЙ АДМІНІСТРАТОР";
                        }
                        else if($user["user_is_admin"]){
                            echo "Права доступу: АДМІНІСТРАТОР";
                        }
                        else{
                            echo "Права доступу:КОРИСТУВАЧ";
                        }
                    ?>
                </td>
                <?php
                    if($user["user_id"]!=1){
                        ?>
                        <td>
                            <button class="edit"><a href=<?php echo "index.php?action=edit_user&user_id=".$user["user_id"]; ?>>Змінити права доступу для користувача</a></button>
                        </td>
                        <td>
                            <button class="delete"><a href=<?php echo "index.php?action=delete_user&user_id=".$user["user_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про користувача?')">Видалити користувача</a></button>
                        </td>
                        <?php
                    }
                ?>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php
 }
else{
    ?>
        <h1 style="color: red;">Тільки головний адміністратор може переглядати всіх користувачів</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>