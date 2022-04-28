<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["transport_id"])){
        $query1 = $PDOconn->prepare("SELECT transport_vin, transport_type, transport_model, transport_year, 
        transport_seats, transport_polis, transport_pereviznyk, pereviznyk_id, pereviznyk_surname, pereviznyk_name
        FROM transport INNER JOIN pereviznyk ON transport_pereviznyk = pereviznyk_id
        WHERE transport_vin=?");
        $query1->execute(array($_GET["transport_id"]));
        
        if(isset($_GET["submitted"])){
            $query = $PDOconn->prepare("UPDATE transport 
            SET transport_seats=?, transport_pereviznyk=?, transport_polis=?, transport_user_id=?
            WHERE transport_vin=?");
            $query->execute(array($_POST["transport_seats"],$_POST["transport_pereviznyk"],$_POST["transport_polis"],$_SESSION["user"]["user_id"],$_GET["transport_id"]));
            header("Location: index.php?action=all_transports&success_update=1");
        }
    
?>

<h1 class="form-title">
   Редагування інформація про транспортний засіб
<?php 
   $transport = $query1->fetch(); 
   $polisId = $transport["transport_polis"];
   $pereviznykId = $transport["pereviznyk_id"];

   $query2 = "SELECT polis_tz_id, polis_tz_termin, company_name
   FROM polis_tz INNER JOIN company ON polis_tz_company=company_id;"; 

   $query3 = "SELECT pereviznyk_id, pereviznyk_surname, pereviznyk_name
   FROM pereviznyk";
?>    
</h1>

<div class="register-form-container">
    
    <form action=<?php echo "index.php?action=edit_transport&submitted=1&transport_id=".$_GET["transport_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер транспорту</h1>
                <input class="input" value=<?php echo $_GET["transport_id"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">Модель транспортного засобу</h1>
                <input class="input" value=<?php echo $transport["transport_model"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">Тип транспортного засобу</h1>
                <input class="input" value=<?php echo $transport["transport_type"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">Дата випуску</h1>
                <input class="input" value=<?php echo $transport["transport_year"];?> disabled>
            </div>

            <div class="form-field">
            <h1 class="form-title">Кількість місць для сидіння</h1>
                <input type="number" min=20 max=60 class="input" name="transport_seats" value=<?php 
                    echo $transport["transport_seats"];
                ?>>
            </div>

            <div class="form-field">
            <h1 class="form-title">Страховий поліс</h1>
                <select name="transport_polis" class="input">
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                        <option value=<?php echo $object["polis_tz_id"]?> <?php if($object["polis_tz_id"] == $polisId){ $flag1=true?> selected <?php } ?> ><?php echo "(".$object["polis_tz_id"].") ".$object["polis_tz_termin"]." ".$object["company_name"]; ?></option>    
                <?php } ?>
                </select>
            </div>

            <div class="form-field">
            <h1 class="form-title">Перевізник</h1>
                <select name="transport_pereviznyk" class="input">
                <?php foreach($PDOconn->query($query3) as $object){  ?>
                        <option value=<?php echo $object["pereviznyk_id"]?> <?php if($object["pereviznyk_id"] == $pereviznykId){?> selected <?php } ?> ><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
                </select>
            </div>
        </div>
        
        <button class="button" type="submit">Зберегти зміни</div>
        <br/>
        <button class="delete"><a href="index.php?action=all_transports">Скасувати</a></button>
    </form>
</div>
<?php }

else{
    ?>
        <h3 style="color: red;">Вказаного транспорту не існує</h3>
        <button class="button"><a href="index.php?action=all_transports">Показати всі транспорти</a></button>
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