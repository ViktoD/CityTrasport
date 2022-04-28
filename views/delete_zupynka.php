<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["zupynka_id"])){
        $query = $PDOconn->prepare("DELETE FROM zupynka WHERE zupynka_id = ?;");
        $query->execute(array($_GET["zupynka_id"]));

        header("Location: index.php?action=marshrut&marshruts_id=".$_GET["marshruts_id"]."&success_delete=1");
    }
}
else{
    ?>
       <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може видаляти інформацію</h1>
       <button class="button"><a href="index.php?action=main">На головну</a></button>
   <?php
}
?>