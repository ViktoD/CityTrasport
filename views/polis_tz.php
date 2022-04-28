<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["polis_tz_id"])){
        
        $query1 = $PDOconn->prepare("SELECT polis_tz_id, polis_tz_termin, company_name
        FROM polis_tz INNER JOIN company ON polis_tz_company=company_id
        WHERE polis_tz_id=?;");
        $query1->execute(array($_GET["polis_tz_id"]));

        $polis_tz = $query1->fetch();
        if($polis_tz){

            $query2 = $PDOconn->prepare("SELECT transport_vin, transport_type, transport_model
            FROM transport
            WHERE transport_polis=?;");
            $query2->execute(array($_GET["polis_tz_id"]));
?>



    <h1 class="form-title">
         Інформаця про страховий поліс
    </h1>
<table class="content-table">
    <thead>
        <th>Номер полісу</th>
        <th>Компанія, яка видала поліс</th>
        <th>Термін дії полісу</th>
    </thead>

    <tbody>
        <tr>
            <td>
                <?= $polis_tz["polis_tz_id"];?>
            </td>
            <td>
                <?= $polis_tz["company_name"] ?>
            </td>
            <td>
                <?= $polis_tz["polis_tz_termin"] ?>
            </td>
        </tr>
    </tbody>
</table>
<p class="form-title">Список транспортних засобів, які мають даний страховий поліс</p>
<?php $transport = $query2->fetch();
    if($transport){
    ?>

<table class="content-table">
    <thead>
        <th>Номер транспорту</th>
        <th>Модель транспорту</th>
        <th>Вид транспорту</th>
    </thead>

    <tbody>
        <tr>
            <td>
                <?= $transport["transport_vin"];?>
            </td>
            <td>
                <?= $transport["transport_model"] ?>
            </td>
            <td>
                <?= $transport["transport_type"] ?>
            </td>
            <td>
                <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$transport["transport_vin"]?>>Переглянути</a></button>
            </td>
            <td>
                <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$transport["transport_vin"];?>>Редагувати</a></button>
            </td>
            <td>
                <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$transport["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити</a></button>
            </td>
        </tr>
    <?php
    while($transport = $query2->fetch()){
        ?>
        <tr>
            <td>
                <?= $transport["transport_vin"];?>
            </td>
            <td>
                <?= $transport["transport_model"] ?>
            </td>
            <td>
                <?= $transport["transport_type"] ?>
            </td>
            <td>
                <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$transport["transport_vin"]?>>Переглянути</a></button>
            </td>
            <td>
                <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$transport["transport_vin"];?>>Редагувати</a></button>
            </td>
            <td>
                <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$transport["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити</a></button>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
<?php 
}
else{
    ?>
    <h1 style="color: red;">Даний страховий поліс не страхує жодний транспорт</h1>
    <?php
}
?>
<?php }
else{
    ?>
        <h1 style="color: red;">Вказаного страхового поліса не існує</h1>
    <?php
} 
}
else{
    ?>
        <h1 style="color: red;">Вказаного страхового полісу не існує</h1>
    <?php
 } 
}
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може переглядати цю інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php 
}?>