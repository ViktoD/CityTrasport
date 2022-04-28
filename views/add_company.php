<?php 
  if(!empty($_SESSION) && $_SESSION["user"]["user_is_admin"]==true){
    $maxId = $PDOconn->query("SELECT MAX(company_id) FROM company")->fetch();

    if(isset($_POST["company_name"]) && !empty($_POST["company_name"]) &&
    isset($_POST["company_address"]) && !empty($_POST["company_address"])){
        $query = $PDOconn->prepare("INSERT INTO company VALUES(?,?,?,?);");
        $query->execute(array($maxId["max"]+1,$_POST["company_name"], $_POST["company_address"], $_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_companies&success_add=1");
    }
?>
<div class="register-form-container">

    <h1 class="form-title">
        Додавання інформації про страхову компанію
    </h1>

    <form action="index.php?action=add_company" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Назва компанії</h1>
                <input type="text" class="input" name="company_name" placeholder="Введіть назву компанії" value='<?php 
                if(isset($_POST["company_name"]) && !empty($_POST["company_name"])){
                    echo $_POST["company_name"];
                }
                ?>'>
                <?php 
                if(isset($_POST["company_name"]) && empty($_POST["company_name"])){
                ?>
                    <p style="font-size: 24px; color: red;">Введіть назву компанії</p>
                <?php
                }
            ?>
            </div>
        
            <div class="form-field">
                <h1 class="form-title">Адреса компанії</h1>
                <input type="text" class="input" name="company_address" placeholder="Введіть адресу компанії" value='<?php 
                if(isset($_POST["company_address"]) && !empty($_POST["company_address"])){
                    echo $_POST["company_address"];
                }
                ?>'>
                <?php 
                if(isset($_POST["company_address"]) && empty($_POST["company_address"])){
                ?>
                    <p style="font-size: 24px; color: red;">Введіть адресу компанії</p>
                <?php
                }
                ?>
            </div>
    </div>
    <button class="button" type="submit">Додати нову страхову компанію</div>
</form>
<br/>
<button class="delete"><a href="index.php?action=all_companies">Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
} ?>