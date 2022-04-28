<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $query1 = "SELECT vodiy_id, vodiy_surname, vodiy_name, vodiy_lastname
    FROM vodiy
    ORDER BY vodiy_surname;";

    if(isset($_POST["id_vodiy"]) && $_POST["id_vodiy"]!="none" && isset($_POST["date_medohlyad"]) 
    && !empty($_POST["date_medohlyad"]) && isset($_POST["result_medohlyad"])){

        $query2 = $PDOconn->prepare("INSERT INTO medohlyad VALUES(?,?,?,?);");
        $query2->execute(array($_POST["id_vodiy"], $_POST["date_medohlyad"], $_POST["result_medohlyad"], $_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_medohlyads&success_add=1");
    }
?>

<div class="register-form-container">

    <h1 class="form-title">
        Додавання інформації про новий медогляд водія
    </h1>
    
    <form action="index.php?action=add_medohlyad" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <?php if(!isset($_POST["id_vodiy"])){ ?>
                <select name="id_vodiy" class="input">
                    <option value="none">Оберіть водія</option>
                    <?php foreach($PDOconn->query($query1) as $object){ ?>
                        <option value=<?php echo $object["vodiy_id"] ?>><?php echo "(".$object["vodiy_id"].") ".$object["vodiy_surname"]." ".$object["vodiy_name"]." ".$object["vodiy_lastname"]; ?></option>
                    <?php } ?>
                </select>
                <?php } 

                    else if(isset($_POST["id_vodiy"]) && $_POST["id_vodiy"]=="none"){ ?>
                    <select name="id_vodiy" class="input">
                        <option value="none">Оберіть водія</option>
                    <?php foreach($PDOconn->query($query1) as $object){ ?>
                        <option value=<?php echo $object["vodiy_id"] ?>><?php echo "(".$object["vodiy_id"].") ".$object["vodiy_surname"]." ".$object["vodiy_name"]." ".$object["vodiy_lastname"]; ?></option>
                    <?php } ?>
                    </select>
                    <p style="font-size: 24px; color: red;">Оберіть водія</p>
                <?php } 
                    else if(isset($_POST["id_vodiy"]) && $_POST["id_vodiy"]!="none"){
                        $vodiyId = $_POST["id_vodiy"];
                        ?>
                        <select name="id_vodiy" class="input">
                            <option value="none">Оберіть водія</option>
                        <?php foreach($PDOconn->query($query1) as $object){ ?>
                            <option value=<?php echo $object["vodiy_id"] ?> <?php if($object["vodiy_id"]==$vodiyId) {?> selected <?php } ?>><?php echo "(".$object["vodiy_id"].") ".$object["vodiy_surname"]." ".$object["vodiy_name"]." ".$object["vodiy_lastname"]; ?></option>
                        <?php } ?>
                        </select>
                <?php } ?>    
            </div>

            <div class="form-field">
                <h1 class="form-title">Вкажіть дату медогляду</h1>
                <input type="date" max=<?php echo date('Y-m-d'); ?> name="date_medohlyad" class="input" value= <?php 
                if(isset($_POST["date_medohlyad"]) && !empty($_POST["date_medohlyad"])){ 
                    echo $_POST["date_medohlyad"];
                }?>>
                 
                <?php 
                    if(isset($_POST["date_medohlyad"]) && empty($_POST["date_medohlyad"])){ 
                ?>
                       <p style="font-size: 24px; color: red;">Вкажіть дату медогляду</p>
                <?php } ?>    
            </div>

            <div class="form-field">
                <h1 class="form-title">Який результат медогляду?</h1>
                    <?php if(!isset($_POST["result_medohlyad"]) || (isset($_POST["result_medohlyad"]) && $_POST["result_medohlyad"]=="true")){ ?>
                <select name="result_medohlyad" class="input">
                    <option value="true">Водій допущений до роботи</option>
                    <option value="false">Водія НЕ допущено до роботи</option>
                </select>
                <?php } ?>
                <?php if(isset($_POST["result_medohlyad"]) && $_POST["result_medohlyad"]=="false"){ ?>
                    <select name="result_medohlyad" class="input">
                        <option value="true">Водій допущений до роботи</option>
                        <option value="false" selected>Водія НЕ допущено до роботи</option>
                    </select>
                <?php } ?>
            </div>
        </div>
        <button class="button" type="submit">Додати інформацію про медогляд</div>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_medohlyads">Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>