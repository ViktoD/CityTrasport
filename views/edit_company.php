<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["company_id"])){
    $query1 = $PDOconn->prepare("SELECT *
    FROM company
    WHERE company_id=?;");
    $query1->execute(array($_GET["company_id"]));
    $company = $query1->fetch();

    if(isset($_POST["company_name"]) && !empty($_POST["company_name"]) &&
    isset($_POST["company_address"]) && !empty($_POST["company_address"])){
        $query = $PDOconn->prepare("UPDATE company SET company_name=?, company_address=?, company_user_id=?
        WHERE company_id=?;");
        $query->execute(array($_POST["company_name"], $_POST["company_address"], $_SESSION["user"]["user_id"], $_GET["company_id"]));
        header("Location: index.php?action=all_companies&success_update=1");
    }
?>
<div class="register-form-container">

    <h1 class="form-title">
        Редагування інформації про страхову компанію
    </h1>

    <form action=<?php echo "index.php?action=edit_company&company_id=".$_GET["company_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер компанії</h1>
                <input type="text" class="input" disabled value=<?php 
                echo $company["company_id"];
                ?>>
            </div>

            <div class="form-field">
                <h1 class="form-title">Назва компанії</h1>
                <input type="text" class="input" name="company_name" placeholder="Введіть назву компанії" value='<?php 
                echo $company["company_name"];
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
                echo $company["company_address"];
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
    <button class="button" type="submit">Зберегти зміни</div>
    <br/>
    <button class="delete"><a href="index.php?action=all_companies">Скасувати</a></button>
</form>
</div>


<?php }
    else{
        ?>
            <h1 style="color: red;">Дану страхову компанію не знайдено</h1>
        <?php
    }
}
else{
    ?>
       <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може редагувати інформацію</h1>
       <button class="button"><a href="index.php?action=main">На головну</a></button>
   <?php
}
?>
