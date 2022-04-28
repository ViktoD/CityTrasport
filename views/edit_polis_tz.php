<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["polis_tz_id"])){
        
        $query1 = $PDOconn->prepare("SELECT polis_tz_id, polis_tz_termin, company_name
        FROM polis_tz INNER JOIN company ON polis_tz_company=company_id
        WHERE polis_tz_id=?;");
        $query1->execute(array($_GET["polis_tz_id"]));

        $polis_tz = $query1->fetch();
        if($polis_tz){

            if(isset($_GET["submitted"])){
                $query2 = $PDOconn->prepare("UPDATE polis_tz SET polis_tz_termin=?, polis_tz_user_id=?
                WHERE polis_tz_id=?;");
                $query2->execute(array($_POST["polis_tz_termin"],$_SESSION["user"]["user_id"],$_GET["polis_tz_id"]));
                header("Location: index.php?action=all_tz_polis&success_update=1");
            }
?>

<div class="register-form-container">

    <h1 class="form-title">
        Редагування інформації про страховий поліс
    </h1>

    <form action=<?php echo "index.php?action=edit_polis_tz&submitted=1&polis_tz_id=".$_GET["polis_tz_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер полісу</h1>
                <input type="text" class="input" disabled value=<?php 
                echo $polis_tz["polis_tz_id"];
                ?>>
            </div>

            <div class="form-field">
            <h1 class="form-title">Компанія, яка видала поліс</h1>
                <input type="text" class="input" disabled value='<?php 
                echo $polis_tz["company_name"];
                ?>'>
            </div>
        
            <div class="form-field">
                <h1 class="form-title">Термін дії</h1>
                <input type="date" min=<?php echo $polis_tz["polis_tz_termin"]; ?> name="polis_tz_termin" class="input" name="polis_tz_termin" placeholder="Виберіть доки поліс дійсний" value='<?php 
                echo $polis_tz["polis_tz_termin"];
                ?>'>
                <?php 
                if(isset($_POST["polis_tz_termin"]) && empty($_POST["polis_tz_termin"])){
                ?>
                    <p style="font-size: 24px; color: red;">Виберіть доки поліс дійсний</p>
                <?php
                }
                ?>
            </div>
    </div>
    <button class="button" type="submit">Зберегти зміни</div>
    <br/>
    <button class="delete"><a href="index.php?action=all_tz_polis">Скасувати</a></button>
</form>
</div>
<br/>
<br/>
<?php }
else{
    ?>
        <h1 style="color: red;">Вказаного страхового поліса не існує</h1>
    <?php
 } 
}
else{
    ?>
        <h1 style="color: red;">Вказаного страхового полісу не існує</h1>
    <?php
 } 
}
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може редагувати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>  
    <?php
}?>