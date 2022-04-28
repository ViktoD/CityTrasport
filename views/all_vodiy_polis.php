<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ 
$start=0;
$page=1;
$countVodiyPolis = $PDOconn->query("SELECT COUNT(*) FROM polis_vodiy;")->fetch();
$numberPages = (int)$countVodiyPolis["count"]/20;
if($countVodiyPolis["count"]%20 != 0){
    $numberPages++;
}

if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
    $page = $_GET["page"];
    $start = 20*($page-1);
    $query1 = "SELECT polis_vodiy_id, polis_vodiy_termin, company_name, user_surname, user_name
    FROM (polis_vodiy INNER JOIN company ON polis_vodiy_company=company_id)
    INNER JOIN users ON polis_vodiy_user_id=user_id
    ORDER BY polis_vodiy_termin DESC
    LIMIT 20 OFFSET {$start};";
}

else{
    $query1 = "SELECT polis_vodiy_id, polis_vodiy_termin, company_name, user_surname, user_name
    FROM (polis_vodiy INNER JOIN company ON polis_vodiy_company=company_id)
    INNER JOIN users ON polis_vodiy_user_id=user_id
    ORDER BY polis_vodiy_termin DESC 
    LIMIT 20 OFFSET {$start};";
}
?>
<button class="add"><a href="index.php?action=add_polis_vodiy">Додати новий страховий поліс для водія</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Дані про страховий поліс було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про страховий поліс було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про страховий поліс було успішно оновлено</h1>
        <?php
    } ?>  
<p class="form-title">Список всіх страхових полісів для транспорту</p>
<table class="content-table">
    <thead>
        <th>Номер полісу</th>
        <th>Компанія, яка видала поліс</th>
        <th>Термін дії полісу</th>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
        <?php foreach($PDOconn->query($query1) as $object) { 
        ?>
            <tr>
                
                <td>
                    <?= $object["polis_vodiy_id"];?>
                </td>
                <td>
                    <?= $object["company_name"] ?>
                </td>
                <td>
                    <?= $object["polis_vodiy_termin"] ?>
                </td>
                <td>
                    <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                </td>
                <td>
                    <button class="view"><a href=<?php echo "index.php?action=polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"];?>>Переглянути</a></button>
                </td>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"];?>>Редагувати</a></button>
                </td>
                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"];?> onclick="return confirm('Ви справді хочете інформацію про страховий поліс?')">Видалити</a></button>
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
        <button class="paginate"><a href=<?php echo "index.php?action=all_vodiy_polis&page=".$i;?>><?php echo $i; ?></a></button>
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