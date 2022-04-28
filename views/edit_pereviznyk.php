<?php
  if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["pereviznyk_id"])){

        $query1 = $PDOconn->prepare("SELECT * 
        FROM pereviznyk 
        WHERE pereviznyk_id=?;");
        $query1->execute(array($_GET["pereviznyk_id"]));

        $pereviznyk = $query1->fetch();

        if(isset($_POST["pereviznyk_surname"]) && !empty($_POST["pereviznyk_surname"]) && isset($_POST["pereviznyk_name"]) && !empty($_POST["pereviznyk_name"])
        && isset($_POST["pereviznyk_phone"]) && !empty($_POST["pereviznyk_phone"])&& isset($_POST["pereviznyk_address"]) && !empty($_POST["pereviznyk_address"])){
        
        $query = $PDOconn->prepare("UPDATE pereviznyk SET pereviznyk_surname=?, pereviznyk_name=?,
        pereviznyk_phone=?, pereviznyk_address=?, pereviznyk_user_id=? 
        WHERE pereviznyk_id=?;");
        
        $query->execute(array($_POST["pereviznyk_surname"],$_POST["pereviznyk_name"],$_POST["pereviznyk_phone"],$_POST["pereviznyk_address"],$_SESSION["user"]["user_id"],$_GET["pereviznyk_id"]));
        header("Location: index.php?action=all_pereviznyks&success_update=1");
        }
?>



<div class="register-form-container">

    <h1 class="form-title">
        Редагування інформації про перевізника
    </h1>
    
    <form action=<?php echo "index.php?action=edit_pereviznyk&pereviznyk_id=".$_GET["pereviznyk_id"]; ?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Код перевізника</h1>
                <input type="text" class="input" value=<?php echo $_GET["pereviznyk_id"] ?> disabled >
            </div>
            <div class="form-field">
                <h1 class="form-title">Прізвище перевізника</h1>
                <input type="text" class="input" name="pereviznyk_surname" placeholder="Введіть прізвище" value=<?php 
                    echo $pereviznyk["pereviznyk_surname"];
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
                <h1 class="form-title">Ім'я перевізника</h1>
                <input type="text" class="input" name="pereviznyk_name" placeholder="Введіть ім'я"  value=<?php 
                    echo $pereviznyk["pereviznyk_name"];
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
                <h1 class="form-title">Ім'я по-батькові перевізника</h1>
                <input type="text" class="input" name="pereviznyk_lastname" placeholder="Введіть ім'я по-батькові" value=<?php 
                    echo $pereviznyk["pereviznyk_lastname"];
                ?> disabled>
            </div>

            <div class="form-field">
                <h1 class="form-title">Номер телефону</h1>
                <input type="text" class="input" name="pereviznyk_phone" placeholder="Введіть номер телефону" value='<?php 
                    echo $pereviznyk["pereviznyk_phone"];
                ?>'>
                <?php 
                    if(isset($_POST["pereviznyk_phone"]) && empty($_POST["pereviznyk_phone"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть номер телефону перевізника</p>
                    
                    <?php
                    }
                ?>
            </div>

            <div class="form-field">
                <h1 class="form-title">Адреса перевізника</h1>
                <input type="text" class="input" name="pereviznyk_address" placeholder="Введіть адресу" value='<?php 
                    echo $pereviznyk["pereviznyk_address"];
                ?>'>
                <?php 
                    if(isset($_POST["pereviznyk_address"]) && empty($_POST["pereviznyk_address"])){
                    ?>
                        
                        <p style="font-size: 24px; color: red;">Введіть адресу перевізника</p>
                    <?php
                
                    }
                ?>
            </div>
            <button class="button" type="submit">Зберегти зміни</div>
        </div>
        <br/>
        <button class="delete"><a href="index.php?action=all_pereviznyks">Скасувати</a></button>
    </form>
</div>
<?php }
    else{
        ?>
            <h1 style="color: red;">Вказаного перевізника не існує</h1>
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