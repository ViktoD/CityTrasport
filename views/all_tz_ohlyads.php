<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ 
$start=0;
$page=1;
$countTzOhlyads = $PDOconn->query("SELECT COUNT(*) FROM transport_ohlyad;")->fetch();
$numberPages = (int)$countTzOhlyads["count"]/20;
if($countTzOhlyads["count"]%20 != 0){
    $numberPages++;
}

if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
    $page = $_GET["page"];
    $start = 20*($page-1);
    $query1 = "SELECT transport_ohlyad_vin, transport_ohlyad_date, transport_ohlyad_result, transport_model, transport_type, user_surname, user_name
    FROM (transport_ohlyad INNER JOIN transport ON transport_ohlyad_vin=transport_vin)
    INNER JOIN users ON user_id=transport_ohlyad_user_id
    ORDER BY transport_ohlyad_date DESC
    LIMIT 20 OFFSET {$start};";
}

else{
    $query1 = "SELECT transport_ohlyad_vin, transport_ohlyad_date, transport_ohlyad_result, transport_model, transport_type, user_surname, user_name
    FROM (transport_ohlyad INNER JOIN transport ON transport_ohlyad_vin=transport_vin)
    INNER JOIN users ON user_id=transport_ohlyad_user_id
    ORDER BY transport_ohlyad_date DESC 
    LIMIT 20 OFFSET {$start};";
}
?>
<button class="add"><a href="index.php?action=add_tz_ohlyad">Додати новий запис про техогляд транспорту</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Дані про техогляд було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про всі техогляди вказаного транспорту було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про техогляд було успішно оновлено</h1>
        <?php
    } ?>  
<p class="form-title">Список всіх техоглядів</p>
<table class="content-table">
    <thead>
        <th>Модель транспорту</th>
        <th>Тип транспорту</th>
        <th>Дата техогляду</th>
        <th>Результат техогляду</th>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
        <?php foreach($PDOconn->query($query1) as $object) { 
            
           ?>
                <tr>
                    <td>
                        <?= $object["transport_model"];?>
                    </td>
                    <td>
                        <?= $object["transport_type"] ?>
                    </td>

                    <td>
                        <?= $object["transport_ohlyad_date"] ?>
                    </td>

                    <td>
                        <?php if($object["transport_ohlyad_result"]){
                            echo "Дозволено для використання";
                        }
                        else{
                            echo "Заборонено для використання";
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                    </td>
                    <td>
                        <button class="view"><a href=<?php echo "index.php?action=transport&transport_ohlyad=1&transport_id=".$object["transport_ohlyad_vin"];?>>Переглянути</a></button>
                    </td>
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
        <button class="paginate"><a href=<?php echo "index.php?action=all_tz_ohlyads&page=".$i;?>><?php echo $i; ?></a></button>
    <?php
} ?>

<br/>
<br/>
<button class="button"><a href="index.php?action=find_information">Повернутися до меню</a></button>
<?php } 
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може переглядати цю інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>