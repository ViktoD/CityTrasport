<?php 
$start=0;
$page=1;
$countMarshruts = $PDOconn->query("SELECT COUNT(*) FROM marshrut;")->fetch();
$numberPages = (int)$countMarshruts["count"]/20;
if($countMarshruts["count"]%20 != 0){
    $numberPages++;
}

if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
    $page = $_GET["page"];
    $start = 20*($page-1);
    $query1 = "SELECT marshrut_id, marshrut_start, marshrut_end, user_surname, user_name
    FROM marshrut INNER JOIN users ON marshrut_user_id=user_id 
    LIMIT 20 OFFSET {$start};";
}

else{
    $query1 = "SELECT marshrut_id, marshrut_start, marshrut_end, user_surname, user_name
    FROM marshrut INNER JOIN users ON marshrut_user_id=user_id 
    LIMIT 20 OFFSET {$start};";
}

if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
?>

<button class="add"><a href="index.php?action=add_marshrut">Додати новий маршрут</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Новий маршрут було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про маршрут було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про маршрут було успішно оновлено</h1>
        <?php
    } ?> 
<?php } ?> 
<p class="form-title">Список всіх маршрутів</p>
<table class="content-table">
    <thead>
        <th>Номер маршруту</th>
        <th>Початок маршруту</th>
        <th>Кінець маршруту</th>
        <?php if(!empty($_SESSION["user"])){ ?>
        <th>Користувач, який востаннє вніс зміни</th>
        <?php } ?>
    </thead>

    <tbody>
        <?php foreach($PDOconn->query($query1) as $object) { 
            
           ?>
                <tr>
                    <td>
                        <?= $object["marshrut_id"];?>
                    </td>
                    <td>
                        <?= $object["marshrut_start"];?>
                    </td>
                    <td>
                        <?= $object["marshrut_end"] ?>
                    </td>
                    <?php if(!empty($_SESSION["user"])){ ?>
                    <td>
                        <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                    </td>
                    <?php } ?>
                    <td>
                        <button class="view"><a href=<?php echo "index.php?action=marshrut&marshruts_id=".$object["marshrut_id"];?>>Переглянути</a></button>
                    </td>
                    <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <button class="edit"><a href=<?php echo "index.php?action=edit_marshrut&marshruts_id=".$object["marshrut_id"];?>>Редагувати</a></button>
                    </td>

                    <td>
                        <button class="delete"><a href=<?php echo "index.php?action=delete_marshrut&marshruts_id=".$object["marshrut_id"] ?> onclick="return confirm('Ви справді хочете інформацію про маршрут?')">Видалити</a></button>
                    </td>
                    <?php } ?>
                </tr>
                <?php
    
                }?>
        </tbody>
</table>
<br/>
<br/>
<h1>Сторінка № <?php echo $page; ?></h1>
<?php for($i=1; $i<=$numberPages; $i++){
    ?>
        <button class="paginate" id=<?php echo $i; ?>><a href=<?php echo "index.php?action=all_marshruts&page=".$i;?>><?php echo $i; ?></a></button>
    <?php
} ?>

<br/>
<br/>
<button class="button"><a href="index.php?action=find_information">Повернутися до меню</a></button>