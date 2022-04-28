<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["polis_vodiy_id"])){
        
        $query1 = $PDOconn->prepare("SELECT polis_vodiy_id, polis_vodiy_termin, company_name
        FROM polis_vodiy INNER JOIN company ON polis_vodiy_company=company_id
        WHERE polis_vodiy_id=?;");
        $query1->execute(array($_GET["polis_vodiy_id"]));

        $polis_tz = $query1->fetch();
        if($polis_tz){

            $query2 = $PDOconn->prepare("SELECT vodiy_id, vodiy_surname, vodiy_name, vodiy_address
            FROM vodiy
            WHERE vodiy_polis=?;");
            $query2->execute(array($_GET["polis_vodiy_id"]));
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
                <?= $polis_tz["polis_vodiy_id"];?>
            </td>
            <td>
                <?= $polis_tz["company_name"] ?>
            </td>
            <td>
                <?= $polis_tz["polis_vodiy_termin"] ?>
            </td>
        </tr>
    </tbody>
</table>
<p class="form-title">Список водіїв, які мають даний страховий поліс</p>
<?php $vodiy = $query2->fetch();
    if($vodiy){
    ?>

<table class="content-table">
    <thead>
        <th>Код водія</th>
        <th>Прізвище водія</th>
        <th>Ім'я водія</th>
        <th>Адреса водія</th>
    </thead>

    <tbody>
        <tr>
            <td>
                <?= $vodiy["vodiy_id"];?>
            </td>
            <td>
                <?= $vodiy["vodiy_surname"]; ?>
            </td>
            <td>
                <?= $vodiy["vodiy_name"]; ?>
            </td>
            <td>
                <?= $vodiy["vodiy_address"]; ?>
            </td>
            <td>
                <button class="view"><a href=<?php echo "index.php?action=vodiy&vodiy_id=".$vodiy["vodiy_id"];?>>Переглянути</a></button>
            </td>
            <td>
                <button class="edit"><a href=<?php echo "index.php?action=edit_vodiy&vodiy_id=".$vodiy["vodiy_id"];?>>Редагувати</a></button>
            </td>
            <td>
                <button class="delete"><a href=<?php echo "index.php?action=delete_vodiy&vodiy_id=".$vodiy["vodiy_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити</a></button>
            </td>
        </tr>
    <?php
    while($vodiy = $query2->fetch()){
        ?>
        <tr>
            <td>
                <?= $vodiy["vodiy_id"];?>
            </td>
            <td>
                <?= $vodiy["vodiy_surname"]; ?>
            </td>
            <td>
                <?= $vodiy["vodiy_name"]; ?>
            </td>
            <td>
                <?= $vodiy["vodiy_address"]; ?>
            </td>
            <td>
                <button class="view"><a href=<?php echo "index.php?action=vodiy&vodiy_id=".$vodiy["vodiy_id"];?>>Переглянути</a></button>
            </td>
            <td>
                <button class="edit"><a href=<?php echo "index.php?action=edit_vodiy&vodiy_id=".$vodiy["vodiy_id"];?>>Редагувати</a></button>
            </td>
            <td>
                <button class="delete"><a href=<?php echo "index.php?action=delete_vodiy&vodiy_id=".$vodiy["vodiy_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити</a></button>
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
}?>?>