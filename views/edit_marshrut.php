<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["marshruts_id"])){

    $query1 = $PDOconn->prepare("SELECT marshrut_id, marshrut_start, marshrut_end, marshrut_price, marshrut_pereviznyk
    FROM marshrut WHERE marshrut_id=?");
    $query1->execute(array($_GET["marshruts_id"]));
    
    $marshrut = $query1->fetch();
    $pereviznykId = $marshrut["marshrut_pereviznyk"];
    $query2 = "SELECT pereviznyk_id, pereviznyk_surname, pereviznyk_name
    FROM pereviznyk";
    
    if(isset($_POST["marshrut_start"]) && !empty($_POST["marshrut_start"]) && isset($_POST["marshrut_end"]) 
    && !empty($_POST["marshrut_end"]) && isset($_POST["marshrut_price"]) && !empty($_POST["marshrut_price"])){
        
        $query = $PDOconn->prepare("UPDATE marshrut SET
        marshrut_start=?, marshrut_end=?, marshrut_price=?, marshrut_pereviznyk=?, marshrut_user_id=?
        WHERE marshrut_id=?;");
        $query->execute(array($_POST["marshrut_start"],$_POST["marshrut_end"],$_POST["marshrut_price"],
        $_POST["marshrut_pereviznyk"], $_SESSION["user"]["user_id"],$_GET["marshruts_id"]));
        header("Location: index.php?action=all_marshruts&success_update=1");
    }

?>



<div class="register-form-container">

    <h1 class="form-title">
        Редагування інформації про маршрут
    </h1>
    
    <form action=<?php echo "index.php?action=edit_marshrut&marshruts_id=".$_GET["marshruts_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер маршруту</h1>
                <input type="text" class="input" disabled value=<?php 
                    echo $marshrut["marshrut_id"];
                ?>>
            </div>

            <div class="form-field">
                <h1 class="form-title">Початок маршруту</h1>
                <input type="text" class="input" name="marshrut_start" placeholder="Введіть початок маршруту" value='<?php 
                    echo $marshrut["marshrut_start"];
                ?>'>
                <?php 
                    if(isset($_POST["marshrut_start"]) && empty($_POST["marshrut_start"])){
                    ?>
                        <p style="font-size: 24px; color: red;">Введіть початок маршруту</p>
                    <?php
                    }
                ?>
            </div>
            
            <div class="form-field">
                <h1 class="form-title">Кінець маршруту</h1>
                <input type="text" class="input" name="marshrut_end" placeholder="Введіть кінець маршруту" value='<?php 
                   echo $marshrut["marshrut_end"];
                ?>'>
                <?php 
                    if(isset($_POST["marshrut_end"]) && empty($_POST["marshrut_end"])){
                    ?>
                        <p style="font-size: 24px; color: red;">Введіть кінець маршруту</p>
                    <?php
                    }
                ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Ціна проїзду</h1>
                <input type="number" min=1 class="input" name="marshrut_price" placeholder="Вкажіть ціну проїзду"  value=<?php 
                    echo $marshrut["marshrut_price"];
                ?>>
                <?php 
                    if(isset($_POST["marshrut_price"]) && empty($_POST["marshrut_price"])){
                    ?>
                        <p style="font-size: 24px; color: red;">Вкажіть ціну маршруту</p>
                    <?php
                    }
                ?>
            </div>

            <div class="form-field">
            <h1 class="form-title">Перевізник</h1>
                <select name="marshrut_pereviznyk" class="input">
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                        <option value=<?php echo $object["pereviznyk_id"]?> <?php if($object["pereviznyk_id"] == $pereviznykId){?> selected <?php } ?> ><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
                </select>
            </div>
            
        </div>
        <button class="button" type="submit">Зберегти зміни</div>
        <br/>
        <button class="delete"><a href="index.php?action=all_marshruts">Скасувати</a></button>
    </form>
</div>
<?php }

else{
    ?>
        <h3 style="color: red;">Вказаного маршруту не існує</h3>
        <button class="button"><a href="index.php?action=all_marshruts">Показати всі маршрути</a></button>
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