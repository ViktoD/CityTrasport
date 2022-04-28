<?php
 if(!empty($_SESSION["user"])){
    $start=0;
    $page=1;
    $countPereviznyks = $PDOconn->query("SELECT COUNT(*) FROM pereviznyk;")->fetch();
    $numberPages = (int)$countPereviznyks["count"]/20;
    if($countPereviznyks["count"]%20 != 0){
        $numberPages++;
    }

    if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
        $page = $_GET["page"];
        $start = 20*($page-1);
        $query = "SELECT *
              FROM pereviznyk INNER JOIN users ON pereviznyk_user_id=user_id
              ORDER BY pereviznyk_surname
              LIMIT 20 OFFSET {$start};";
    }

    else{
        $query = "SELECT *
                FROM pereviznyk INNER JOIN users ON pereviznyk_user_id=user_id
                ORDER BY pereviznyk_surname
                LIMIT 20 OFFSET {$start};";
    }
  if($_SESSION["user"]["user_is_admin"]==true){ 
?>

<button class="add"><a href="index.php?action=add_pereviznyk">Додати нового перевізника</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Нового перевізника було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про перевізника було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про перевізника було успішно оновлено</h1>
        <?php
    }
 } ?>
<p class="form-title">Список всіх перевізників</p>

<table class="content-table">
    <thead>
        <th>Прізвище перевізника</th>
        <th>Ім'я перевізника</th>
        <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
        <th>Номер телефону</th>
        <?php } ?>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
    <?php foreach($PDOconn->query($query) as $object) {?>
            <tr>
                <td>
                    <?= $object["pereviznyk_surname"];?>
                </td>
                <td>
                    <?= $object["pereviznyk_name"];?>
                </td>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <?= $object["pereviznyk_phone"]; ?>
                </td>
                <?php } ?>
                <td>
                    <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                </td>
                <td>
                    <button class="view"><a href=<?php echo "index.php?action=pereviznyk&pereviznyk_id=".$object["pereviznyk_id"]?>>Переглянути</a></button>
                </td>
                <?php if($_SESSION["user"]["user_is_admin"]==true){?>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_pereviznyk&pereviznyk_id=".$object["pereviznyk_id"];?>>Редагувати</a></button>
                </td>

                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_pereviznyk&pereviznyk_id=".$object["pereviznyk_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про перевізника?')">Видалити</a></button>
                </td>
                <?php } ?>
            </tr>
            <?php
            }?>
    </tbody>
</table>
<h1>Сторінка № <?php echo $page; ?></h1>
<?php for($i=1; $i<=$numberPages; $i++){
    ?>
        <button class="paginate"><a href=<?php echo "index.php?action=all_pereviznyks&page=".$i;?>><?php echo $i; ?></a></button>
    <?php
} ?>

<br/>
<br/>
<button class="button"><a href="index.php?action=find_information">Повернутися до меню</a></button>
<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач може переглядати цю інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>