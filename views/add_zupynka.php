<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["marshruts_id"]) && !empty($_GET["marshruts_id"])){
        $query1 = "SELECT * FROM zupynka ORDER BY zupynka_name;";

    if(isset($_GET["autoadd"])){
        if(isset($_POST["choose_zupynka"]) && $_POST["choose_zupynka"]!="none"){
        $query2 = $PDOconn->prepare("INSERT INTO zupynka_marshrut VALUES(?,?);");
        $query2->execute(array($_GET["marshruts_id"],$_POST["choose_zupynka"]));
        header("Location: index.php?action=marshrut&success_add=1&marshruts_id=".$_GET["marshruts_id"]);
        }
    }

    else if(isset($_GET["manualadd"])){
        $maxId = $PDOconn->query("SELECT MAX(zupynka_id) FROM zupynka")->fetch();
        if(isset($_POST["zupynka_name"]) && !empty($_POST["zupynka_name"])&&
        isset($_POST["zupynka_date"]) && !empty($_POST["zupynka_date"])){
            $query5 = $PDOconn->prepare("SELECT zupynka_name FROM zupynka
            WHERE zupynka_name=?;");
            $query5->execute(array($_POST["zupynka_name"]));
            if($query5->fetch()){
                ?>
                    <h2 style="color: red;">Зупинка з такою назвою вже існує</h2>
                <?php
            }
            else{
                $query3 = $PDOconn->prepare("INSERT INTO zupynka VALUES(?,?,?,?);");
                $query3->execute(array($maxId["max"]+1,$_POST["zupynka_name"],$_POST["zupynka_date"],$_SESSION["user"]["user_id"]));

            
                $query4 = $PDOconn->prepare("INSERT INTO zupynka_marshrut VALUES(?,?);");
                $query4->execute(array($_GET["marshruts_id"],$maxId["max"]+1));
                header("Location: index.php?action=marshrut&success_add=1&marshruts_id=".$_GET["marshruts_id"]);
            }
        }
    }
?>

<h1 class="form-title">
        Створіть нову зупинку або оберіть із запронованих
</h1>

<div class="register-form-container">
    <form action=<?php echo "index.php?action=add_zupynka&autoadd&marshruts_id=".$_GET["marshruts_id"]; ?> method="POST">
        <div class="form-fields">
            <div class="form-field">
            <h1 class="form-title">Обрати зупинку</h1>
            <select name="choose_zupynka" class="input">
                    <option value="none">Оберіть зупинку</option>
                    <?php 
                        foreach($PDOconn->query($query1) as $zupynka){
                    ?>
                        <option value=<?php echo $zupynka["zupynka_id"]; ?>><?php echo $zupynka["zupynka_name"]; ?></option>
                    <?php
                        }
                    ?>
                </select>
                <?php 
                    if(isset($_POST["choose_zupynka"]) && $_POST["choose_zupynka"]=="none"){
                        ?>
                            <p style="color: red;">Оберіть зупинку</p>
                        <?php
                    }
                ?>
            </div>
        </div>
        <button class="button" type="input">Додати зупинку</button>
    </form>
</div>
<br/>
<br/>
<div class="register-form-container"> 
    <form action=<?php echo "index.php?action=add_zupynka&manualadd&marshruts_id=".$_GET["marshruts_id"]; ?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Назва зупинки</h1>
                <input type="text" class="input" name="zupynka_name" placeholder="Введіть назву зупинки" value='<?php 
                if(isset($_POST["zupynka_name"]) && !empty($_POST["zupynka_name"])){
                    echo $_POST["zupynka_name"];
                }
                ?>'>
                <?php 
                    if(isset($_POST["zupynka_name"]) && empty($_POST["zupynka_name"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Вкажіть назву зупинки</p>
                    
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Вкажіть дату появи цієї зупинки</h1>
                <input type="date" max=<?php echo date('Y-m-d');?> class="input" name="zupynka_date" placeholder="Вкажіть дату появи цієї зупинки" value=<?php 
                if(isset($_POST["zupynka_date"]) && !empty($_POST["zupynka_date"])){
                    echo $_POST["zupynka_date"];
                }
                ?>>
                <?php 
                    if(isset($_POST["zupynka_date"]) && empty($_POST["zupynka_date"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Вкажіть дату появи цієї зупинки</p>
                    <?php
                    
                    }
                ?>
            </div>
        </div>
        <button class="button" type="submit">Додати зупинку до маршруту</div>
    </form>
    <br/>
    <button class="delete"><a href=<?php echo "index.php?action=marshrut&marshruts_id=".$_GET["marshruts_id"]; ?>>Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Вказаного маршруту до якого ви хочете додати зупинку не існує</h1>
    <?php
}
?>
<?php } 
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>

