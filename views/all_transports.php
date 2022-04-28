<?php
if(!empty($_SESSION["user"])){
    $start=0;
    $page=1;
    $countTransports = $PDOconn->query("SELECT COUNT(*) FROM transport;")->fetch();
    $numberPages = (int)$countTransports["count"]/20;
    if($countTransports["count"]%20 != 0){
        $numberPages++;
    }

    if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
        $page = $_GET["page"];
        $start = 20*($page-1);
        $query = "SELECT transport_vin, transport_model, transport_type, transport_year, transport_seats, user_surname, user_name
        FROM transport INNER JOIN users ON transport_user_id=user_id
        ORDER BY transport_vin LIMIT 20 OFFSET {$start}";
    }

    else{
        $query = "SELECT transport_vin, transport_model, transport_type, transport_year, transport_seats, user_surname, user_name
        FROM transport INNER JOIN users ON transport_user_id=user_id 
        ORDER BY transport_vin LIMIT 20 OFFSET {$start}";
    }

    if($_SESSION["user"]["user_is_admin"]==true){
    ?>
        <button class="add"><a href="index.php?action=add_transport">Додати новий транспортний засіб</a></button>
        <?php if(isset($_GET["success_add"])) {
        ?>
            <h1 style="color: green;">Новий транспортний засіб було успішно додано</h1>
        <?php
        }
        if(isset($_GET["success_delete"])){
        ?>
            <h1 style="color: red;">Інформацію про транспортний засіб було успішно видалено</h1>
        <?php
        }
        if(isset($_GET["success_update"])){
        ?>
            <h1 style="color: orange;">Дані про транспортний засіб було успішно оновлено</h1>
        <?php
        } 
    }?>

<p class="form-title">Список всіх транспортних засобів</p>

<table class="content-table">
    <thead>
        <th>Номер транспорту</th>
        <th>Модель транспорту</th>
        <th>Тип транспорту</th>
        <th>Дата випуску</th>
        <th>Кількість місць для сидіння</th>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
    <?php foreach($PDOconn->query($query) as $object) {?>
            <tr>
                <td>
                    <?= $object["transport_vin"];?>
                </td>
                <td>
                    <?= $object["transport_model"];?>
                </td>
                <td>
                    <?= $object["transport_type"]; ?>
                </td>
                <td>
                    <?= $object["transport_year"]; ?>
                </td>
                <td>
                    <?= $object["transport_seats"]; ?>
                </td>
                <td>
                    <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                </td>
                <td>
                    <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$object["transport_vin"]?>>Переглянути</a></button>
                </td>

                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$object["transport_vin"];?>>Редагувати</a></button>
                </td>

                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$object["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити</a></button>
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
        <button class="paginate"><a href=<?php echo "index.php?action=all_transports&page=".$i;?>><?php echo $i; ?></a></button>
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