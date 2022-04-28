<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $query1 = "SELECT transport_vin, transport_model, transport_type
    FROM transport;";

    if(isset($_POST["vin_transport"]) && $_POST["vin_transport"]!="none" 
    && isset($_POST["date_tz_ohlyad"]) && !empty($_POST["date_tz_ohlyad"]) 
    && isset($_POST["result_tz_ohlyad"])){

        $query2 = $PDOconn->prepare("INSERT INTO transport_ohlyad VALUES(?,?,?,?);");
        $query2->execute(array($_POST["vin_transport"], $_POST["date_tz_ohlyad"], $_POST["result_tz_ohlyad"], $_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_tz_ohlyads&success_add=1");
    }
?>

<div class="register-form-container">

    <h1 class="form-title">
        Додавання інформації про новий техогляд транспорту
    </h1>
    
    <form action="index.php?action=add_tz_ohlyad" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <?php if(!isset($_POST["vin_transport"])){ ?>
                <select name="vin_transport" class="input">
                    <option value="none">Оберіть транспорт</option>
                    <?php foreach($PDOconn->query($query1) as $object){ ?>
                        <option value=<?php echo $object["transport_vin"] ?>><?php echo "(".$object["transport_vin"].") ".$object["transport_model"]." ".$object["transport_type"]; ?></option>
                    <?php } ?>
                </select>
                <?php } 

                    else if(isset($_POST["vin_transport"]) && $_POST["vin_transport"]=="none"){ ?>
                    <select name="vin_transport" class="input">
                        <option value="none">Оберіть транспорт</option>
                    <?php foreach($PDOconn->query($query1) as $object){ ?>
                        <option value=<?php echo $object["transport_vin"] ?>><?php echo "(".$object["transport_vin"].") ".$object["transport_model"]." ".$object["transport_type"]; ?></option>
                    <?php } ?>
                    </select>
                    <p style="font-size: 24px; color: red;">Оберіть транспорт</p>
                <?php } 
                    else if(isset($_POST["vin_transport"]) && $_POST["vin_transport"]!="none"){
                        $transportId = $_POST["vin_transport"];
                        ?>
                        <select name="vin_transport" class="input">
                            <option value="none">Оберіть транспорт</option>
                        <?php foreach($PDOconn->query($query1) as $object){ ?>
                            <option value=<?php echo $object["transport_vin"] ?> <?php if($object["transport_vin"]==$transportId) {?> selected <?php } ?>><?php echo "(".$object["transport_vin"].") ".$object["transport_model"]." ".$object["transport_type"]; ?></option>
                        <?php } ?>
                        </select>
                <?php } ?>    
            </div>

            <div class="form-field">
                <h1 class="form-title">Вкажіть дату техогляду</h1>
                <input type="date" max=<?php echo date('Y-m-d'); ?> name="date_tz_ohlyad" class="input" value= <?php 
                if(isset($_POST["date_tz_ohlyad"]) && !empty($_POST["date_tz_ohlyad"])){ 
                    echo $_POST["date_tz_ohlyad"];
                }?>>
                 
                <?php 
                    if(isset($_POST["date_tz_ohlyad"]) && empty($_POST["date_tz_ohlyad"])){ 
                ?>
                       <p style="font-size: 24px; color: red;">Вкажіть дату техогляду</p>
                <?php } ?>    
            </div>

            <div class="form-field">
                <h1 class="form-title">Який результат техогляду?</h1>
                    <?php if(!isset($_POST["result_tz_ohlyad"]) || (isset($_POST["result_tz_ohlyad"]) && $_POST["result_tz_ohlyad"]=="true")){ ?>
                <select name="result_tz_ohlyad" class="input">
                    <option value="true">Транспорт дозволено використовувати</option>
                    <option value="false">Транспорт заборонено використовувати</option>
                </select>
                <?php } ?>
                <?php if(isset($_POST["result_tz_ohlyad"]) && $_POST["result_tz_ohlyad"]=="false"){ ?>
                    <select name="result_tz_ohlyad" class="input">
                        <option value="true">Транспорт дозволено використовувати</option>
                        <option value="false" selected>Транспорт заборонено використовувати</option>
                    </select>
                <?php } ?>
            </div>
        </div>
        <button class="button" type="submit">Додати інформацію про техогляд</div>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_tz_ohlyads">Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>