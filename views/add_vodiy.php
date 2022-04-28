<?php 
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $query1 = "SELECT polis_vodiy_id, polis_vodiy_termin, company_name
    FROM polis_vodiy INNER JOIN company ON polis_vodiy_company=company_id
    WHERE date_part('year',polis_vodiy_termin)-date_part('year',CURRENT_DATE) >= 1;"; 

    $query2 = "SELECT pereviznyk_id, pereviznyk_surname, pereviznyk_name
    FROM pereviznyk";
  
   
    $maxId = $PDOconn->query("SELECT MAX(vodiy_id) FROM vodiy")->fetch();

    if(isset($_POST["vodiy_surname"]) && !empty($_POST["vodiy_surname"]) && isset($_POST["vodiy_name"]) && !empty($_POST["vodiy_name"]) && 
    isset($_POST["vodiy_lastname"]) && !empty($_POST["vodiy_lastname"]) && isset($_POST["vodiy_birthday"]) && !empty($_POST["vodiy_birthday"])&&
    isset($_POST["is_married"]) && $_POST["is_married"] != "none" && isset($_POST["vodiy_address"]) && !empty($_POST["vodiy_address"])&&
    isset($_POST["vodiy_polis"]) && $_POST["vodiy_polis"]!="none"&&isset($_POST["vodiy_pereviznyk"]) && $_POST["vodiy_pereviznyk"] != "none"){
        
        $query = $PDOconn->prepare("INSERT INTO vodiy VALUES(?,?,?,?,?,?,?,?,?,?);");
        $query->execute(array($maxId["max"]+1,$_POST["vodiy_surname"],$_POST["vodiy_name"],$_POST["vodiy_lastname"],$_POST["vodiy_birthday"],$_POST["is_married"],$_POST["vodiy_address"],$_POST["vodiy_polis"],$_POST["vodiy_pereviznyk"],$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_vodiys&success_add=1");
    }
?>



<div class="register-form-container">

    <h1 class="form-title">
        Додавання нового водія
    </h1>
    
    <form action="index.php?action=add_vodiy" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <input type="text" class="input" name="vodiy_surname" placeholder="Введіть прізвище" value=<?php 
                if(isset($_POST["vodiy_surname"]) && !empty($_POST["vodiy_surname"])){
                    echo $_POST["vodiy_surname"];
                }
                ?>>
                <?php 
                    if(isset($_POST["vodiy_surname"]) && empty($_POST["vodiy_surname"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть прізвище водія</p>
                    
                    <?php
              
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="vodiy_name" placeholder="Введіть ім'я"  value=<?php 
                if(isset($_POST["vodiy_name"]) && !empty($_POST["vodiy_name"])){
                    echo $_POST["vodiy_name"];
                }
                ?>>
                <?php 
                    if(isset($_POST["vodiy_name"]) && empty($_POST["vodiy_name"])){
                    ?>
                    
                        <p style="font-size: 24px; color: red;">Введіть ім'я водія</p>
                        
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="vodiy_lastname" placeholder="Введіть ім'я по-батькові" value=<?php 
                if(isset($_POST["vodiy_lastname"]) && !empty($_POST["vodiy_lastname"])){
                    echo $_POST["vodiy_lastname"];
                }
                ?>>
                <?php 
                    if(isset($_POST["vodiy_lastname"]) && empty($_POST["vodiy_lastname"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть ім'я по-батькові водія</p>
                    
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Вкажіть дату народження</h1>
                <input type="date" max=<?php echo date('Y-m-d');?> class="input" name="vodiy_birthday" placeholder="Введіть дату народження" value=<?php 
                if(isset($_POST["vodiy_birthday"]) && !empty($_POST["vodiy_birthday"])){
                    echo $_POST["vodiy_birthday"];
                }
                ?>>
                <?php 
                    if(isset($_POST["vodiy_birthday"]) && empty($_POST["vodiy_birthday"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть дату народження водія</p>
                    <?php
                    
                    }
                ?>
            </div>

            <div class="form-field">
                <?php if(!isset($_POST["is_married"])){   ?>
                <select name="is_married" class="input">
                    <option value="none">Оберіть сімейний статус водія</option>
                    <option value="true">Одружений</option>
                    <option value="false">Не одружений</option>
                </select>
                <?php } ?>
                <?php 
                    if(isset($_POST["is_married"]) && $_POST["is_married"] == "none"){
                    ?>
                        <select name="is_married" class="input">
                            <option value="none">Оберіть сімейний статус водія</option>
                            <option value="true">Одружений</option>
                            <option value="false">Не одружений</option>
                        </select>

                        <p style="font-size: 24px; color: red;">Виберіть сімейний статус водія</p>
                    <?php
                    
                    }
                    
                    else if(isset($_POST["is_married"]) && $_POST["is_married"] != "none"){
                      
                        if($_POST["is_married"]=="true"){
                        ?>
                            <select name="is_married" class="input">
                                <option value="none">Оберіть сімейний статус водія</option>
                                <option value="true" selected>Одружений</option>
                                <option value="false">Не одружений</option>
                            </select>
                        <?php
                        }

                        else if($_POST["is_married"]=="false"){
                            ?>
                            <select name="is_married" class="input">
                                <option value="none">Оберіть сімейний статус водія</option>
                                <option value="true">Одружений</option>
                                <option value="false" selected>Не одружений</option>
                            </select>
                        <?php
                        }


                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="vodiy_address" placeholder="Введіть адресу водія" value=<?php 
                if(isset($_POST["vodiy_address"]) && !empty($_POST["vodiy_address"])){
                    echo $_POST["vodiy_address"];
                }
                ?>>
                <?php 
                    if(isset($_POST["vodiy_address"]) && empty($_POST["vodiy_address"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть адресу водія</p>
                    <?php
                
                    }
                ?>
            </div>

            <div class="form-field">
                <?php 
                    if(!isset($_POST["vodiy_polis"])){
                ?>
                    <select name="vodiy_polis" class="input">
                        <option value="none">Оберіть доступний страховий поліс для водія</option>
                <?php foreach($PDOconn->query($query1) as $object){  ?>
                        <option value=<?php echo $object["polis_vodiy_id"]?>><?php echo "(".$object["polis_vodiy_id"].") ".$object["polis_vodiy_termin"]." ".$object["company_name"]; ?></option>    
                <?php } ?>
                    </select>

                <?php 
                    }
                    if(isset($_POST["vodiy_polis"]) && $_POST["vodiy_polis"] == "none"){
                    ?>
                        <select name="vodiy_polis" class="input">
                            <option value="none">Оберіть доступний страховий поліс для водія</option>
                    <?php foreach($PDOconn->query($query1) as $object){  ?>
                            <option value=<?php echo $object["polis_vodiy_id"]?>><?php echo "(".$object["polis_vodiy_id"].") ".$object["polis_vodiy_termin"]." ".$object["company_name"]; ?></option>    
                    <?php } ?>
                        </select>
                        <p style="font-size: 24px; color: red;">Виберіть страховий поліс для водія</p>
                    <?php
                   
                    }

                    else if(isset($_POST["vodiy_polis"]) && $_POST["vodiy_polis"]!="none"){
                        $vodiy_id = $_POST["vodiy_polis"];
                        ?>
                        <select name="vodiy_polis" class="input">
                            <option value="none">Оберіть доступний страховий поліс для водія</option>
                    <?php foreach($PDOconn->query($query1) as $object){  ?>
                            <option value=<?php echo $object["polis_vodiy_id"]?> <?php if($object["polis_vodiy_id"]==$vodiy_id){ ?> selected <?php } ?> ><?php echo "(".$object["polis_vodiy_id"].") ".$object["polis_vodiy_termin"]." ".$object["company_name"]; ?></option>    
                    <?php } ?>
                        </select>
                    <?php
                    }
                ?>
            </div>


            <div class="form-field">
                <?php if(!isset($_POST["vodiy_pereviznyk"])){ ?>
                <select name="vodiy_pereviznyk" class="input">
                    <option value="none">Оберіть перевізника для водія</option>
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                    <option value=<?php echo $object["pereviznyk_id"]?>><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
            </select>

            <?php }
                    if(isset($_POST["vodiy_pereviznyk"]) && $_POST["vodiy_pereviznyk"] == "none"){
                    ?>
                        <select name="vodiy_pereviznyk" class="input">
                            <option value="none">Оберіть перевізника для водія</option>
                <?php foreach($PDOconn->query($query2) as $object){  ?>
                            <option value=<?php echo $object["pereviznyk_id"]?>><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                <?php } ?>
                        </select>
                        <p style="font-size: 24px; color: red;">Виберіть перевізника для водія</p>
                    <?php
                   
                    }

                    else if(isset($_POST["vodiy_pereviznyk"]) && $_POST["vodiy_pereviznyk"] != "none"){
                        $vodiy_pereviznyk = $_POST["vodiy_pereviznyk"];
                        ?>
                        <select name="vodiy_pereviznyk" class="input">
                            <option value="none">Оберіть перевізника для водія</option>
                    <?php foreach($PDOconn->query($query2) as $object){  ?>
                            <option value=<?php echo $object["pereviznyk_id"]?> <?php if($object["pereviznyk_id"]==$vodiy_pereviznyk){ ?> selected <?php } ?> ><?php echo "(".$object["pereviznyk_id"].") ".$object["pereviznyk_surname"]." ".$object["pereviznyk_name"]; ?></option>    
                    <?php } ?>
                        </select>
                <?php
                    }
                    
                ?>
 
            </div>
        </div>
        <button class="button" type="submit">Додати водія</div>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_vodiys">Скасувати</a></button>
</div>
<?php } 
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>
