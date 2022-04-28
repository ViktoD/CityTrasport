<?php
 if(!empty($_SESSION["user"])){
    $start=0;
    $page=1;
    $countVodiys = $PDOconn->query("SELECT COUNT(*) FROM vodiy;")->fetch();
    $numberPages = (int)$countVodiys["count"]/20;
    if($countVodiys["count"]%20 != 0){
        $numberPages++;
    }

    if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
        $page = $_GET["page"];
        $start = 20*($page-1);
        $query = "SELECT vodiy.vodiy_id, vodiy.vodiy_surname, vodiy.vodiy_name, vodiy.vodiy_lastname, 
              vodiy.vodiy_birthday, vodiy.vodiy_address, user_surname, user_name 
              FROM vodiy INNER JOIN users ON vodiy_user_id=user_id
              ORDER BY vodiy_surname
              LIMIT 20 OFFSET {$start};";
    }

    else{
        $query = "SELECT vodiy.vodiy_id, vodiy.vodiy_surname, vodiy.vodiy_name, vodiy.vodiy_lastname, 
              vodiy.vodiy_birthday, vodiy.vodiy_address, user_surname, user_name 
              FROM vodiy INNER JOIN users ON vodiy_user_id=user_id
              ORDER BY vodiy_surname
              LIMIT 20 OFFSET {$start};";
    }
 
?>
<?php if($_SESSION["user"]["user_is_admin"] ==true){ ?>
<button class="add"><a href="index.php?action=add_vodiy">Додати нового водія</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Нового водія було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про водія було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про водія було успішно оновлено</h1>
        <?php
    } 
}?>
<p class="form-title">Список всіх водіїв</p>

<table class="content-table">
    <thead>
        <th>Прізвище водія</th>
        <th>Ім'я водія</th>
        <th>Ім'я по-батькові водія</th>       
        <th>Дата народження</th>
        <?php if($_SESSION["user"]["user_is_admin"]==true){?>
        <th>Адреса</th>
        <?php } ?>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
    <?php foreach($PDOconn->query($query) as $object) {?>
            <tr>
                <td>
                    <?= $object["vodiy_surname"];?>
                </td>
                <td>
                    <?= $object["vodiy_name"];?>
                </td>
                <td>
                    <?= $object["vodiy_lastname"]; ?>
                </td>
               
                <td>
                    <?= $object["vodiy_birthday"]; ?>
                </td>
                <?php if($_SESSION["user"]["user_is_admin"]==true){?>
                <td>
                    <?= $object["vodiy_address"]; ?>
                </td>
                <?php } ?>
                <td>
                    <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                </td>
                <td>
                    <button class="view"><a href=<?php echo "index.php?action=vodiy&vodiy_id=".$object["vodiy_id"]?>>Переглянути</a></button>
                </td>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_vodiy&vodiy_id=".$object["vodiy_id"];?>>Редагувати</a></button>
                </td>
                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_vodiy&vodiy_id=".$object["vodiy_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про водія?')">Видалити</a></button>
                </td>
                <?php }?>
            </tr>
            <?php
            }?>
    </tbody>
</table>
<h1>Сторінка № <?php echo $page; ?></h1>
<?php for($i=1; $i<=$numberPages; $i++){
    ?>
        <button class="paginate"><a href=<?php echo "index.php?action=all_vodiys&page=".$i;?>><?php echo $i; ?></a></button>
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