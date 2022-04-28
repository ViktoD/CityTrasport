<?php 
 if(!empty($_SESSION["user"])&&$_SESSION["user"]["user_id"]==1){
    if(isset($_GET["user_id"])){
        $query = $PDOconn->prepare("DELETE FROM users WHERE user_id = ?;");
        $query->execute(array($_GET["user_id"]));

        header("Location: index.php?action=all_users&success_delete=1");
    }
}
else{
    ?>
      <h1 style="color: red;">Тільки головний адміністратор може видаляти інформацію про користувачів</h1>
      <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>