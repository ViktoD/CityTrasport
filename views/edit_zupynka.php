<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["zupynka_id"])){
        $query1 = $PDOconn->prepare("SELECT zupynka_id, zupynka_name, zupynka_start, marshrut_id, marshrut_start, marshrut_end
        FROM (marshrut INNER JOIN zupynka_marshrut ON zupynka_marshrut_id=marshrut_id)
        INNER JOIN zupynka ON zupynka_id=marshrut_zupynka_id
        WHERE zupynka_id=?;");
        $query1->execute(array($_GET["zupynka_id"]));

        if(isset($_POST["zupynka_name"]) && !empty($_POST["zupynka_name"])){
            $query = $PDOconn->prepare("UPDATE zupynka 
            SET zupynka_name=? 
            WHERE zupynka_id=?");
            $query->execute(array($_POST["zupynka_name"],$_GET["zupynka_id"]));
            header("Location: index.php?action=edit_zupynka&zupynka_id=".$_GET["zupynka_id"]."&success_update=1");

            $query4 = $PDOconn->prepare("UPDATE zupynka_marshrut 
            SET zupynka_marshrut_id=?
            WHERE marshrut_zupynka_id=?;");
            $query4->execute(array($_POST["marshrut_id"],$_GET["zupynka_id"]));
            header("Location: index.php?action=edit_zupynka&zupynka_id=".$_GET["zupynka_id"]."&success_update=1");
        }
    
?>

<h1 class="form-title">
   Редагування інформація про зупинку
<?php 
   $zupynka = $query1->fetch(); 

   $query2 = "SELECT marshrut_id, marshrut_start, marshrut_end
   FROM marshrut;"; 
?>    
</h1>

<div class="register-form-container">
    
    <form action=<?php echo "index.php?action=edit_zupynka&zupynka_id=".$_GET["zupynka_id"];?> method="POST">
        <div class="form-fields">

            <div class="form-field">
                <h1 class="form-title">Номер зупинки</h1>
                <input class="input" value=<?php echo $_GET["zupynka_id"];?> disabled>
            </div>

            <div class="form-field">
                <h1 class="form-title">Дата відкриття зупинки</h1>
                <input class="input" value=<?php echo $zupynka["zupynka_start"];?> disabled>
            </div>

            <div class="form-field">
                <h1 class="form-title">Назва зупинки</h1>
                <input type="text" name="zupynka_name" class="input" value='<?php echo $zupynka["zupynka_name"];?>'>
                <?php
                    if(isset($_POST["zupynka_name"]) && empty($_POST["zupynka_name"])){
                        ?>
                         <p style="font-size: 24px; color: red;">Введіть назву зупинки</p>
                        <?php
                       
                    }
                ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Маршрут</h1>
                <select name="marshrut" class="input">
                <?php foreach($PDOconn->query($query2) as $object) {  ?>
                        <option value=<?php echo $object["marshrut_id"]?> <?php if($object["marshrut_id"] == $zupynka["marshrut_id"]){ ?> selected <?php } ?> ><?php echo "(".$object["marshrut_id"].") ".$object["marshrut_start"]."-".$object["marshrut_end"]; ?></option>    
                <?php } ?>
                </select>
            </div>
        </div>
        
        <button class="button" type="submit">Зберегти зміни</button>
        <br/>
        <button class="delete"><a href=<?php echo "index.php?action=marshrut&marshruts_id=".$zupynka["marshrut_id"]; ?>>Скасувати</a></button>
    </form>
</div>
<?php }

else{
    ?>
        <h3 style="color: red;">Вказаної зупинки не існує</h3>
        <button class="button"><a href="index.php?action=all_marshruts">Показати всі маршрути</a></button>
    <?php
 }
}
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може редагувати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php 
}?>