<?php 
   if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $maxId = $PDOconn->query("SELECT MAX(pereviznyk_id) FROM pereviznyk")->fetch();

    if(isset($_POST["pereviznyk_surname"]) && !empty($_POST["pereviznyk_surname"]) && isset($_POST["pereviznyk_name"]) && !empty($_POST["pereviznyk_name"]) && 
    isset($_POST["pereviznyk_lastname"]) && !empty($_POST["pereviznyk_lastname"]) && isset($_POST["pereviznyk_phone"]) && !empty($_POST["pereviznyk_phone"])&&
    isset($_POST["pereviznyk_address"]) && !empty($_POST["pereviznyk_address"])){
        
        $query = $PDOconn->prepare("INSERT INTO pereviznyk VALUES(?,?,?,?,?,?,?);");
        $query->execute(array($maxId["max"]+1,$_POST["pereviznyk_surname"],$_POST["pereviznyk_name"],$_POST["pereviznyk_lastname"],$_POST["pereviznyk_phone"],$_POST["pereviznyk_address"],$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_pereviznyks&success_add=1");
    }
?>



<div class="register-form-container">

    <h1 class="form-title">
        Додавання нового перевізника
    </h1>
    
    <form action="index.php?action=add_pereviznyk" method="POST">
        <div class="form-fields">
            <div class="form-field">
                <input type="text" class="input" name="pereviznyk_surname" placeholder="Введіть прізвище" value=<?php 
                if(isset($_POST["pereviznyk_surname"]) && !empty($_POST["pereviznyk_surname"])){
                    echo $_POST["pereviznyk_surname"];
                }
                ?>>
                <?php 
                    if(isset($_POST["pereviznyk_surname"]) && empty($_POST["pereviznyk_surname"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть прізвище перевізника</p>
                    
                    <?php
              
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="pereviznyk_name" placeholder="Введіть ім'я"  value=<?php 
                if(isset($_POST["pereviznyk_name"]) && !empty($_POST["pereviznyk_name"])){
                    echo $_POST["pereviznyk_name"];
                }
                ?>>
                <?php 
                    if(isset($_POST["pereviznyk_name"]) && empty($_POST["pereviznyk_name"])){
                    ?>
                    
                        <p style="font-size: 24px; color: red;">Введіть ім'я перевізника</p>
                        
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="pereviznyk_lastname" placeholder="Введіть ім'я по-батькові" value=<?php 
                if(isset($_POST["pereviznyk_lastname"]) && !empty($_POST["pereviznyk_lastname"])){
                    echo $_POST["pereviznyk_lastname"];
                }
                ?>>
                <?php 
                    if(isset($_POST["pereviznyk_lastname"]) && empty($_POST["pereviznyk_lastname"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть ім'я по-батькові перевізника</p>
                    
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="pereviznyk_phone" placeholder="Введіть номер телефону" value=<?php 
                if(isset($_POST["pereviznyk_phone"]) && !empty($_POST["pereviznyk_phone"])){
                    echo $_POST["pereviznyk_phone"];
                }
                ?>>
                <?php 
                    if(isset($_POST["pereviznyk_phone"]) && empty($_POST["pereviznyk_phone"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть номер телефону перевізника</p>
                    
                    <?php
                   
                    }
                ?>
            </div>

            <div class="form-field">
                <input type="text" class="input" name="pereviznyk_address" placeholder="Введіть адресу" value=<?php 
                if(isset($_POST["pereviznyk_address"]) && !empty($_POST["pereviznyk_address"])){
                    echo $_POST["pereviznyk_address"];
                }
                ?>>
                <?php 
                    if(isset($_POST["pereviznyk_address"]) && empty($_POST["pereviznyk_address"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть адресу перевізника</p>
                    <?php
                
                    }
                ?>
            </div>
        </div>
        <button class="button" type="submit">Додати перевізника</div>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_pereviznyks">Скасувати</a></button>
</div>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>