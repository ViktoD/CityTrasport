<?php
 if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
     if(isset($_GET["marshruts_id"])){
        $query = $PDOconn->prepare("DELETE FROM marshrut WHERE marshrut_id = ?;");
        $query->execute(array($_GET["marshruts_id"]));

        header("Location: index.php?action=all_marshruts&success_delete=1");
    }
}
else{
    ?>
    <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може видаляти інформацію</h1>
    <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>