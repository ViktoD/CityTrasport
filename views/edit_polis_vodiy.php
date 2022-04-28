<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["polis_vodiy_id"])){
        
        $query1 = $PDOconn->prepare("SELECT polis_vodiy_id, polis_vodiy_termin, company_name
        FROM polis_vodiy INNER JOIN company ON polis_vodiy_company=company_id
        WHERE polis_vodiy_id=?;");
        $query1->execute(array($_GET["polis_vodiy_id"]));

        $polis_vodiy = $query1->fetch();
        if($polis_vodiy){

            if(isset($_GET["submitted"])){
                $query2 = $PDOconn->prepare("UPDATE polis_vodiy SET polis_vodiy_termin=?, polis_vodiy_user_id=?
                WHERE polis_vodiy_id=?;");
                $query2->execute(array($_POST["polis_vodiy_termin"],$_SESSION["user"]["user_id"] ,$_GET["polis_vodiy_id"]));
                header("Location: index.php?action=all_vodiy_polis&success_update=1");
            }
?>

<div class="register-form-container">

    <h1 class="form-title">
        Редагування інформації про страховий поліс
    </h1>

    <form action=<?php echo "index.php?action=edit_polis_vodiy&submitted=1&polis_vodiy_id=".$_GET["polis_vodiy_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер полісу</h1>
                <input type="text" class="input" disabled value=<?php 
                echo $polis_vodiy["polis_vodiy_id"];
                ?>>
            </div>

            <div class="form-field">
            <h1 class="form-title">Компанія, яка видала поліс</h1>
                <input type="text" class="input" disabled value='<?php 
                echo $polis_vodiy["company_name"];
                ?>'>
            </div>
        
            <div class="form-field">
                <h1 class="form-title">Термін дії</h1>
                <input type="date" min=<?php echo $polis_vodiy["polis_vodiy_termin"]; ?> name="polis_vodiy_termin" class="input" name="polis_vodiy_termin" placeholder="Виберіть доки поліс дійсний" value='<?php 
                echo $polis_vodiy["polis_vodiy_termin"];
                ?>'>
                <?php 
                if(isset($_POST["polis_vodiy_termin"]) && empty($_POST["polis_vodiy_termin"])){
                ?>
                    <p style="font-size: 24px; color: red;">Виберіть доки поліс дійсний</p>
                <?php
                }
                ?>
            </div>
    </div>
    <button class="button" type="submit">Зберегти зміни</div>
    <br/>
    <button class="delete"><a href="index.php?action=all_vodiy_polis">Скасувати</a></button>
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