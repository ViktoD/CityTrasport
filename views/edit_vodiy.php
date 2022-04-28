<?php 
 if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["vodiy_id"])){
        $query1 = $PDOconn->prepare("SELECT vodiy.vodiy_id, vodiy.vodiy_surname, vodiy.vodiy_name, vodiy.vodiy_lastname, 
        vodiy.vodiy_birthday, vodiy.vodiy_address, vodiy.vodiy_polis, vodiy.vodiy_pereviznyk, pereviznyk.pereviznyk_id, pereviznyk.pereviznyk_surname, pereviznyk.pereviznyk_name
        FROM vodiy INNER JOIN pereviznyk ON vodiy.vodiy_pereviznyk = pereviznyk.pereviznyk_id
        WHERE vodiy.vodiy_id=?");
        $query1->execute(array($_GET["vodiy_id"]));

        if(isset($_POST["vodiy_surname"]) && !empty($_POST["vodiy_surname"])&&isset($_POST["vodiy_name"]) && !empty($_POST["vodiy_name"])&&
        isset($_POST["vodiy_address"]) && !empty($_POST["vodiy_address"])){
            $query = $PDOconn->prepare("UPDATE vodiy 
            SET vodiy_surname=?, vodiy_name=?, vodiy_address=?, vodiy_polis=?, vodiy_pereviznyk=?, vodiy_user_id=?
            WHERE vodiy_id=?");
            $query->execute(array($_POST["vodiy_surname"],$_POST["vodiy_name"],$_POST["vodiy_address"],$_POST["vodiy_polis"],
            $_POST["vodiy_pereviznyk"],$_SESSION["user"]["user_id"],$_GET["vodiy_id"]));
            header("Location: index.php?action=all_vodiys&success_update=1");
        }
    
?>

<h1 class="form-title">
   Редагування інформація про водія 
<?php 
   $vodiy = $query1->fetch(); 
   echo $vodiy["vodiy_surname"].' '.$vodiy["vodiy_name"];
   $polisId = $vodiy["vodiy_polis"];
   $pereviznykId = $vodiy["pereviznyk_id"];

   $query2 = "SELECT polis_vodiy_id, polis_vodiy_termin, company_name
   FROM polis_vodiy INNER JOIN company ON polis_vodiy_company=company_id;"; 

   $query3 = "SELECT pereviznyk_id, pereviznyk_surname, pereviznyk_name
   FROM pereviznyk";
?>    
</h1>

<div class="register-form-container">
    
    <form action=<?php echo "index.php?action=edit_vodiy&vodiy_id=".$_GET["vodiy_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер водія</h1>
                <input class="input" value=<?php echo $_GET["vodiy_id"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">Прізвище водія</h1>
                <input type="text" name="vodiy_surname" class="input" value=<?php echo $vodiy["vodiy_surname"];?>>
                <?php 
                    if(isset($_POST["vodiy_surname"]) && empty($_POST["vodiy_surname"])){
                        ?>
                         <p style="font-size: 24px; color: red;">Введіть прізвище водія</p>
                        <?php
                       
                    }
                ?>
            </div>

            <div class="form-field">
            <h1 class="form-title">Ім'я водія</h1>
                <input type="text" name="vodiy_name" class="input" value=<?php echo $vodiy["vodiy_name"];?>>
                <?php 
                    if(isset($_POST["vodiy_name"]) && empty($_POST["vodiy_name"])){
                        ?>
                         <p style="font-size: 24px; color: red;">Введіть ім'я водія</p>
                        <?php
                        
                    }
                ?>
            </div>

            <div class="form-field">
            <h1 class="form-title">Ім'я по-батькові водія</h1>
                <input type="text" name="vodiy_lastname" class="input" value=<?php echo $vodiy["vodiy_name"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">День народження</h1>
                <input class="input" value=<?php echo $vodiy["vodiy_birthday"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">Адреса проживання</h1>
                <input type="text" name="vodiy_address" class="input" value='<?php echo $vodiy["vodiy_address"];?>'>
                <?php 
                    if(isset($_POST["vodiy_address"]) && empty($_POST["vodiy_address"])){
                        ?>
                         <p style="font-size: 24px; color: red;">Введіть адресу водія</p>
                        <?php
                      
                    }
                ?>
            </div>

            <div class="form-field">
            <h1 class="form-title">Страховий поліс</h1>
                <select name="vodiy_polis" class="input">
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                        <option value=<?php echo $object["polis_vodiy_id"]?> <?php if($object["polis_vodiy_id"] == $polisId){ $flag1=true?> selected <?php } ?> ><?php echo "(".$object["polis_vodiy_id"].") ".$object["polis_vodiy_termin"]." ".$object["company_name"]; ?></option>    
                <?php } ?>
                </select>
            </div>

            <div class="form-field">
            <h1 class="form-title">Перевізник</h1>
                <select name="vodiy_pereviznyk" class="input">
                <?php foreach($PDOconn->query($query3) as $object){  ?>
                        <option value=<?php echo $object["pereviznyk_id"]?> <?php if($object["pereviznyk_id"] == $pereviznykId){?> selected <?php } ?> ><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
                </select>
            </div>
        </div>
        
        <button class="button" type="submit">Зберегти зміни</div>
        <br/>
        <button class="delete"><a href="index.php?action=all_vodiys">Скасувати</a></button>
    </form>
</div>
<?php }

else{
    ?>
        <h3 style="color: red;">Вказаного водія не існує</h3>
        <button class="button"><a href="index.php?action=all_vodiys">Показати всіх водіїв</a></button>
    <?php
 }
}

else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може редагувати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php 
}?>