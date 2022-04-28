<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $query1 = "SELECT polis_tz_id, polis_tz_termin, company_name
    FROM polis_tz INNER JOIN company ON polis_tz_company=company_id
    WHERE date_part('year',polis_tz_termin)-date_part('year',CURRENT_DATE) >= 1;"; 

    $query2 = "SELECT pereviznyk_id, pereviznyk_surname, pereviznyk_name
    FROM pereviznyk";
  
   
    $maxId = $PDOconn->query("SELECT MAX(transport_vin) FROM transport")->fetch();

    if(isset($_POST["transport_model"]) && !empty($_POST["transport_model"]) && isset($_POST["transport_type"]) && !empty($_POST["transport_type"]) && 
    isset($_POST["transport_date"]) && !empty($_POST["transport_date"]) && isset($_POST["transport_seats"]) && !empty($_POST["transport_seats"]) 
    && isset($_POST["transport_polis"]) && $_POST["transport_polis"]!="none"
    && isset($_POST["transport_pereviznyk"]) && $_POST["transport_pereviznyk"] != "none"){
        
        $query = $PDOconn->prepare("INSERT INTO transport VALUES(?,?,?,?,?,?,?,?);");
        $query->execute(array($maxId["max"]+1,$_POST["transport_model"],$_POST["transport_type"],$_POST["transport_date"],
        $_POST["transport_seats"],$_POST["transport_pereviznyk"],$_POST["transport_polis"],$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_transports&success_add=1");
    }
?>



<div class="register-form-container">

    <h1 class="form-title">
        Додавання нового транспортного засобу
    </h1>
    
    <form action="index.php?action=add_transport" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <input type="text" class="input" name="transport_model" placeholder="Введіть модель транспорту" value=<?php 
                if(isset($_POST["transport_model"]) && !empty($_POST["transport_model"])){
                    echo $_POST["transport_model"];
                }
                ?>>
                <?php 
                    if(isset($_POST["transport_model"]) && empty($_POST["transport_model"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть модель транспорту</p>
                    
                    <?php
              
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="transport_type" placeholder="Вкажіть тип транспортного засобу"  value=<?php 
                if(isset($_POST["transport_type"]) && !empty($_POST["transport_type"])){
                    echo $_POST["transport_type"];
                }
                ?>>
                <?php 
                    if(isset($_POST["transport_type"]) && empty($_POST["transport_type"])){
                    ?>
                    
                        <p style="font-size: 24px; color: red;">Введіть тип транспортного засобу</p>
                        
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Вкажіть дату випуску транспорту</h1>
                <input type="date" max=<?= date('Y-m-d'); ?>  class="input" name="transport_date" value=<?php 
                if(isset($_POST["transport_date"]) && !empty($_POST["transport_date"])){
                    echo $_POST["transport_date"];
                }
                ?>>
                <?php 
                    if(isset($_POST["transport_date"]) && empty($_POST["transport_date"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Вкажіть дату випуску</p>
                    
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="number" min=20 max=60 class="input" name="transport_seats" placeholder="Виберіть кількість місць для сидіння" value=<?php 
                if(isset($_POST["transport_seats"]) && !empty($_POST["transport_seats"])){
                    echo $_POST["transport_seats"];
                }
                ?>>
                <?php 
                    if(isset($_POST["transport_seats"]) && empty($_POST["transport_seats"])){
                     ?>
                        <p style="font-size: 24px; color: red;">Вкажіть кількість місць для сидіння</p>
                     <?php
                    }
                ?>
            </div>

            <div class="form-field">
                <?php 
                    if(!isset($_POST["transport_polis"])){
                ?>
                    <select name="transport_polis" class="input">
                        <option value="none">Оберіть доступний страховий поліс для транспортного засобу</option>
                <?php foreach($PDOconn->query($query1) as $object){  ?>
                        <option value=<?php echo $object["polis_tz_id"]?>><?php echo "(".$object["polis_tz_id"].") ".$object["polis_tz_termin"]." ".$object["company_name"]; ?></option>    
                <?php } ?>
                    </select>

                <?php 
                    }
                    if(isset($_POST["transport_polis"]) && $_POST["transport_polis"] == "none"){
                    ?>
                        <select name="transport_polis" class="input">
                            <option value="none">Оберіть доступний страховий поліс для транспортного засобу</option>
                    <?php foreach($PDOconn->query($query1) as $object){  ?>
                            <option value=<?php echo $object["polis_tz_id"]?>><?php echo "(".$object["polis_tz_id"].") ".$object["polis_tz_termin"]." ".$object["company_name"]; ?></option>    
                    <?php } ?>
                        </select>
                        <p style="font-size: 24px; color: red;">Оберіть доступний страховий поліс для транспортного засобу</p>
                    <?php
                   
                    }

                    else if(isset($_POST["transport_polis"]) && $_POST["transport_polis"]!="none"){
                        $transport_id = $_POST["transport_polis"];
                        ?>
                        <select name="transport_polis" class="input">
                            <option value="none">Оберіть доступний страховий поліс для транспортного засобу</option>
                    <?php foreach($PDOconn->query($query1) as $object){  ?>
                            <option value=<?php echo $object["polis_tz_id"]?> <?php if($object["polis_tz_id"]==$transport_id){ ?> selected <?php } ?> ><?php echo "(".$object["polis_tz_id"].") ".$object["polis_tz_termin"]." ".$object["company_name"]; ?></option>    
                    <?php } ?>
                        </select>
                    <?php
                    }
                ?>
            </div>


            <div class="form-field">
                <?php if(!isset($_POST["transport_pereviznyk"])){ ?>
                <select name="transport_pereviznyk" class="input">
                    <option value="none">Оберіть перевізника для транспортного засобу</option>
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                    <option value=<?php echo $object["pereviznyk_id"]?>><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
            </select>

            <?php }
                    if(isset($_POST["transport_pereviznyk"]) && $_POST["transport_pereviznyk"] == "none"){
                    ?>
                        <select name="transport_pereviznyk" class="input">
                            <option value="none">Оберіть перевізника для транспортного засобу</option>
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                            <option value=<?php echo $object["pereviznyk_id"]?>><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
                        </select>
                        <p style="font-size: 24px; color: red;">Оберіть перевізника для транспортного засобу</p>
                    <?php
                   
                    }

                    else if(isset($_POST["transport_pereviznyk"]) && $_POST["transport_pereviznyk"] != "none"){
                        $transport_pereviznyk = $_POST["transport_pereviznyk"];
                        ?>
                        <select name="transport_pereviznyk" class="input">
                            <option value="none">Оберіть перевізника для транспортного засобу</option>
                    <?php foreach($PDOconn->query($query2) as $object){  ?>
                            <option value=<?php echo $object["pereviznyk_id"]?> <?php if($object["pereviznyk_id"]==$transport_pereviznyk){ ?> selected <?php } ?> ><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                    <?php } ?>
                        </select>
                <?php
                    }
                    
                ?>
 
            </div>
        </div>
        <button class="button" type="submit">Додати транспортний засіб</div>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_transports">Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>
