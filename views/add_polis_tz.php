<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $maxId = $PDOconn->query("SELECT MAX(polis_tz_id) FROM polis_tz")->fetch();

    $query = "SELECT company_id, company_name FROM company;";
    
    if(isset($_POST["company_name"]) && $_POST["company_name"] != "none" &&
    isset($_POST["polis_tz_termin"]) && !empty($_POST["polis_tz_termin"])){
        $query2 = $PDOconn->prepare("INSERT INTO polis_tz VALUES(?,?,?,?);");
        $query2->execute(array($maxId["max"]+1,$_POST["polis_tz_termin"], $_POST["company_name"], $_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_tz_polis&success_add=1");
    }
?>

<div class="register-form-container">

    <h1 class="form-title">
        Додавання інформації про страховий поліс
    </h1>

    <form action=<?php echo "index.php?action=add_polis_tz"?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <?php if(!isset($_POST["company_name"])){ ?>
                <select name="company_name" class="input">
                    <option value="none">Оберіть компанію, яка видала поліс</option>
                <?php foreach($PDOconn->query($query) as $object){  ?>
                    <option value=<?php echo $object["company_id"]?>><?php echo "(".$object["company_id"].") ".$object["company_name"]?></option>    
                <?php } ?>
            </select>

            <?php }
                    if(isset($_POST["company_name"]) && $_POST["company_name"] == "none"){
                    ?>
                        <select name="company_name" class="input">
                            <option value="none">Оберіть компанію, яка видала поліс</option>
                <?php foreach($PDOconn->query($query) as $object){  ?>
                            <option value=<?php echo $object["company_id"]?>><?php echo "(".$object["company_id"].") ".$object["company_name"]; ?></option>    
                <?php } ?>
                        </select>
                        <p style="font-size: 24px; color: red;">Оберіть компанію, яка видала поліс</p>
                    <?php
                   
                    }

                    else if(isset($_POST["company_name"]) && $_POST["company_name"] != "none"){
                        $companyId = $_POST["company_name"];
                        ?>
                        <select name="company_name" class="input">
                            <option value="none">Оберіть компанію, яка видала поліс</option>
                    <?php foreach($PDOconn->query($query) as $object){  ?>
                            <option value=<?php echo $object["company_id"]?> <?php if($object["company_id"]==$companyId){ ?> selected <?php } ?> ><?php echo "(".$object["company_id"].") ".$object["company_name"]; ?></option>    
                    <?php } ?>
                        </select>
                <?php
                    }
                    
                ?>
 
            </div>
        
            <div class="form-field">
                <h1 class="form-title">Термін дії</h1>
                <input type="date" min=<?php echo date('Y-m-d'); ?> name="polis_tz_termin" class="input" name="polis_tz_termin" placeholder="Виберіть доки поліс дійсний" value=<?php 
                if(isset($_POST["polis_tz_termin"]) && !empty($_POST["polis_tz_termin"])){
                    echo $_POST["polis_tz_termin"];
                }
                ?>>
                <?php 
                if(isset($_POST["polis_tz_termin"]) && empty($_POST["polis_tz_termin"])){
                ?>
                    <p style="font-size: 24px; color: red;">Виберіть доки поліс дійсний</p>
                <?php
                }
                ?>
            </div>
    </div>
    <button class="button" type="submit">Додати страховий поліс</div>
    <br/>
</form>
<br/>
<button class="delete"><a href="index.php?action=all_tz_polis">Скасувати</a></button>
</div>
<br/>
<br/>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>