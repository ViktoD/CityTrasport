<?php
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ 
$start=0;
$page=1;
$countCompanies = $PDOconn->query("SELECT COUNT(*) FROM company;")->fetch();
$numberPages = (int)$countCompanies["count"]/20;
if($countCompanies["count"]%20 != 0){
    $numberPages++;
}

if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
    $page = $_GET["page"];
    $start = 20*($page-1);
    $query1 = "SELECT *
    FROM company INNER JOIN users ON company_user_id=user_id
    LIMIT 20 OFFSET {$start};";
}

else{
    $query1 = "SELECT *
    FROM company INNER JOIN users ON company_user_id=user_id 
    LIMIT 20 OFFSET {$start};";
}
?>
<button class="add"><a href="index.php?action=add_company">Додати нову страхову компанію</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Нову компанію було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про компанію було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про компанію було успішно оновлено</h1>
        <?php
    } ?>  
<p class="form-title">Список всіх страхових компаній</p>
<table class="content-table">
    <thead>
        <th>Номер компанії</th>
        <th>Назва компанії</th>
        <th>Адреса компанії</th>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
        <?php foreach($PDOconn->query($query1) as $object) { 
            
           ?>
                <tr>
                    <td>
                        <?= $object["company_id"];?>
                    </td>
                    <td>
                        <?= $object["company_name"];?>
                    </td>
                    <td>
                        <?= $object["company_address"] ?>
                    </td>
                    <td>
                        <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                    </td>
                    <td>
                        <button class="edit"><a href=<?php echo "index.php?action=edit_company&company_id=".$object["company_id"];?>>Редагувати</a></button>
                    </td>
                    <td>
                        <button class="delete"><a href=<?php echo "index.php?action=delete_company&company_id=".$object["company_id"] ?> onclick="return confirm('Ви справді хочете інформацію про страхову компанію?')">Видалити</a></button>
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
        <button class="paginate" id=<?php echo $i; ?>><a href=<?php echo "index.php?action=all_companies&page=".$i;?>><?php echo $i; ?></a></button>
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