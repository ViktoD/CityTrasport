<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $query2 = "SELECT pereviznyk_id, pereviznyk_surname, pereviznyk_name
    FROM pereviznyk";
  
   
    $maxId = $PDOconn->query("SELECT MAX(marshrut_id) FROM marshrut")->fetch();

    if(isset($_POST["marshrut_start"]) && !empty($_POST["marshrut_start"]) && isset($_POST["marshrut_end"]) 
    && !empty($_POST["marshrut_end"]) && isset($_POST["marshrut_price"]) && !empty($_POST["marshrut_price"])
    && isset($_POST["marshrut_pereviznyk"]) && $_POST["marshrut_pereviznyk"] != "none"){
        
        $query = $PDOconn->prepare("INSERT INTO marshrut VALUES(?,?,?,?,?,?);");
        $query->execute(array($maxId["max"]+1,$_POST["marshrut_start"],$_POST["marshrut_end"],$_POST["marshrut_price"],
        $_POST["marshrut_pereviznyk"],$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_marshruts&success_add=1");
    }
?>



<div class="register-form-container">

    <h1 class="form-title">
        Додавання нового маршруту
    </h1>
    
    <form action="index.php?action=add_marshrut" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <input type="text" class="input" name="marshrut_start" placeholder="Введіть початок маршруту" value=<?php 
                if(isset($_POST["marshrut_start"]) && !empty($_POST["marshrut_start"])){
                    echo $_POST["marshrut_start"];
                }
                ?>>
                <?php 
                    if(isset($_POST["marshrut_start"]) && empty($_POST["marshrut_start"])){
                    ?>
                        <p style="font-size: 24px; color: red;">Введіть початок маршруту</p>
                    <?php
                    }
                ?>
            </div>
            
            <div class="form-field">
                <input type="text" class="input" name="marshrut_end" placeholder="Введіть кінець маршруту" value=<?php 
                if(isset($_POST["marshrut_end"]) && !empty($_POST["marshrut_end"])){
                    echo $_POST["marshrut_end"];
                }
                ?>>
                <?php 
                    if(isset($_POST["marshrut_end"]) && empty($_POST["marshrut_end"])){
                    ?>
                        <p style="font-size: 24px; color: red;">Введіть кінець маршруту</p>
                    <?php
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="number" min=1 class="input" name="marshrut_price" placeholder="Вкажіть ціну проїзду"  value=<?php 
                if(isset($_POST["marshrut_price"]) && !empty($_POST["marshrut_price"])){
                    echo $_POST["marshrut_price"];
                }
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
                <?php if(!isset($_POST["marshrut_pereviznyk"])){ ?>
                <select name="marshrut_pereviznyk" class="input">
                    <option value="none">Оберіть перевізника для маршруту</option>
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                    <option value=<?php echo $object["pereviznyk_id"]?>><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
            </select>

            <?php }
                    if(isset($_POST["marshrut_pereviznyk"]) && $_POST["marshrut_pereviznyk"] == "none"){
                    ?>
                        <select name="marshrut_pereviznyk" class="input">
                            <option value="none">Оберіть перевізника для маршруту</option>
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                            <option value=<?php echo $object["pereviznyk_id"]?>><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
                        </select>
                        <p style="font-size: 24px; color: red;">Оберіть перевізника для маршруту</p>
                    <?php
                   
                    }

                    else if(isset($_POST["marshrut_pereviznyk"]) && $_POST["marshrut_pereviznyk"] != "none"){
                        $transport_pereviznyk = $_POST["marshrut_pereviznyk"];
                        ?>
                        <select name="marshrut_pereviznyk" class="input">
                            <option value="none">Оберіть перевізника для маршруту</option>
                    <?php foreach($PDOconn->query($query2) as $object){  ?>
                            <option value=<?php echo $object["pereviznyk_id"]?> <?php if($object["pereviznyk_id"]==$transport_pereviznyk){ ?> selected <?php } ?> ><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                    <?php } ?>
                        </select>
                <?php
                    }
                    
                ?>
 
            </div>
        </div>
        <button class="button" type="submit">Додати маршрут</div>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_marshruts">Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>