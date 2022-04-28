<?php 
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ 
$start=0;
$page=1;
$countMedohlyads = $PDOconn->query("SELECT COUNT(*) FROM medohlyad;")->fetch();
$numberPages = (int)$countMedohlyads["count"]/20;
if($countMedohlyads["count"]%20 != 0){
    $numberPages++;
}

if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
    $page = $_GET["page"];
    $start = 20*($page-1);
    $query1 = "SELECT medohlyad_vodiy, medohlyad_date, medohlyad_result, vodiy_surname, vodiy_name, user_surname, user_name
    FROM (medohlyad INNER JOIN vodiy ON medohlyad_vodiy=vodiy_id)
    INNER JOIN users ON medohlyad_user_id=user_id
    ORDER BY medohlyad_date DESC
    LIMIT 20 OFFSET {$start};";
}

else{
    $query1 = "SELECT medohlyad_vodiy, medohlyad_date, medohlyad_result, vodiy_surname, vodiy_name, user_surname, user_name
    FROM (medohlyad INNER JOIN vodiy ON medohlyad_vodiy=vodiy_id)
    INNER JOIN users ON medohlyad_user_id=user_id
    ORDER BY medohlyad_date DESC 
    LIMIT 20 OFFSET {$start};";
}
?>
<button class="add"><a href="index.php?action=add_medohlyad">Додати новий запис про медогляд водія</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Дані про медогляд було успішно додано</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">Інформацію про всі медогляди вказаного водія було успішно видалено</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">Дані про медогляд було успішно оновлено</h1>
        <?php
    } ?>  
<p class="form-title">Список всіх медоглядів</p>
<table class="content-table">
    <thead>
        <th>Прізвище водія</th>
        <th>Ім'я водія</th>
        <th>Дата медогляду</th>
        <th>Результат медогляду</th>
        <th>Користувач, який востаннє вніс зміни</th>
    </thead>

    <tbody>
        <?php foreach($PDOconn->query($query1) as $object) { 
            
           ?>
                <tr>
                    <td>
                        <?= $object["vodiy_surname"];?>
                    </td>
                    <td>
                        <?= $object["vodiy_name"] ?>
                    </td>

                    <td>
                        <?= $object["medohlyad_date"] ?>
                    </td>

                    <td>
                        <?php if($object["medohlyad_result"]){
                            echo "Допускається до роботи";
                        }
                        else{
                            echo "Не допущено до роботи";
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                    </td>
                    <td>
                        <button class="view"><a href=<?php echo "index.php?action=vodiy&vodiy_ohlyad=1&vodiy_id=".$object["medohlyad_vodiy"];?>>Переглянути</a></button>
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
        <button class="paginate"><a href=<?php echo "index.php?action=all_medohlyads&page=".$i;?>><?php echo $i; ?></a></button>
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