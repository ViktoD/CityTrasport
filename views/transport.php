<?php
  if(!empty($_SESSION["user"])){
    if(isset($_GET["transport_id"]) && !empty($_GET["transport_id"])){
        $query = $PDOconn->prepare("SELECT transport_vin, transport_model, transport_type, transport_year, transport_seats,pereviznyk_surname, pereviznyk_name
        FROM transport INNER JOIN pereviznyk ON transport_pereviznyk=pereviznyk_id
        WHERE transport_vin=?");
        $query->execute(array($_GET["transport_id"]));

        $query1 = $PDOconn->prepare("SELECT transport_ohlyad_vin,transport_ohlyad_date, transport_ohlyad_result
        FROM transport_ohlyad WHERE transport_ohlyad_vin = ?
        ORDER BY transport_ohlyad_date DESC");
        $query1->execute(array($_GET["transport_id"]));

        $query2 = $PDOconn->prepare("SELECT polis_tz_id, polis_tz_termin, company_name
        FROM (transport INNER JOIN polis_tz ON transport_polis = polis_tz_id)
        INNER JOIN company ON company_id = polis_tz_company
        WHERE transport_vin=?;");
        $query2->execute(array($_GET["transport_id"]));

        $query3 = $PDOconn->prepare("SELECT reys_id, marshrut_start, marshrut_end, reys_start, reys_end, is_available
        FROM reys INNER JOIN marshrut ON reys_marshrut=marshrut_id
        WHERE reys_transport=?");
        $query3->execute(array($_GET["transport_id"]));

        $query5 = $PDOconn->prepare("SELECT date_part('year',polis_tz_termin), date_part('year',CURRENT_DATE),
        date_part('month',polis_tz_termin), date_part('month',CURRENT_DATE), date_part('day',polis_tz_termin),
        date_part('day',CURRENT_DATE) FROM transport INNER JOIN polis_tz ON transport_polis=polis_tz_id
        WHERE transport_vin=?");
        $query5->execute(array($_GET["transport_id"]));
        $date = $query5->fetch();
    
    
?>
<p class="form-title">Детальна інформація про транспорт</p>
<?php $object = $query->fetch();
if($object){?>
<table class="content-table">
    <thead>
        <th>Номер транспорту</th>
        <th>Модель транспорту</th>
        <th>Тип транспорту</th>
        <th>Дата випуску</th>
        <th>Кількість місць для сидіння</th>
        <th>Прізвище перевізника</th>
        <th>Ім'я перевізника</th>
    </thead>

    <tbody>
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
                    <?= $object["pereviznyk_surname"]; ?>
                </td>
                <td>
                    <?= $object["pereviznyk_name"]; ?>
                </td>
            </tr>
    </tbody>
</table>

<?php if(!isset($_GET["transport_ohlyad"])){ ?>
<h1 class="form-title">
    Список рейсів, які виконує транспорт
</h1>
<?php $object=$query3->fetch();
if($object){
?>
<table class="content-table">
        <thead>
            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
            <th>Номер рейсу</th>
            <?php } ?>
            <th>Початок маршруту</th>
            <th>Кінець маршруту</th>
            <th>Час відправлення</th>
            <th>Час прибуття</th>
            <th>Чи доступний</th>
        </thead>

        <tbody>
            <tr>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <?= $object["reys_id"]; ?>
                    </td>
                <?php } ?>
                    <td>
                        <?= $object["marshrut_start"];?>
                    </td>
                    <td>
                        <?= $object["marshrut_end"];?>
                    </td>
                    <td>
                        <?= $object["reys_start"] ?>
                    </td>
                    <td>
                        <?= $object["reys_end"] ?>
                    </td>
                    <td>
                    <?php 
                        if($object["is_available"]){
                            ?>
                                <h2 style="color: green;">ДОСТУПНО</h2>
                            <?php
                        }
                        else{
                            ?>
                                <h2 style="color: red;">СКАСОВАНО</h2>
                            <?php
                        }
                    ?>
                    </td>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <button class="edit"><a href=<?php echo "index.php?action=edit_reys&reys_id=".$object["reys_id"];?>>Редагувати</a></button>
                    </td>

                    <td>
                        <button class="delete"><a href=<?php echo "index.php?action=delete_reys&reys_id=".$object["reys_id"] ?> onclick="return confirm('Ви справді хочете інформацію про рейс?')">Видалити</a></button>
                    </td>
                    <?php } ?>
                </tr>
        <?php while($object = $query3->fetch()) {  ?>
                <tr>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <?= $object["reys_id"]; ?>
                    </td>
                    <?php } ?>
                    <td>
                        <?= $object["marshrut_start"];?>
                    </td>
                    <td>
                        <?= $object["marshrut_end"];?>
                    </td>
                    <td>
                        <?= $object["reys_start"] ?>
                    </td>
                    <td>
                        <?= $object["reys_end"] ?>
                    </td>
                    <td>
                    <?php 
                        if($object["is_available"]){
                            ?>
                                <h2 style="color: green;">ДОСТУПНО</h2>
                            <?php
                        }
                        else{
                            ?>
                                <h2 style="color: red;">СКАСОВАНО</h2>
                            <?php
                        }
                    ?>
                    </td>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <button class="edit"><a href=<?php echo "index.php?action=edit_reys&reys_id=".$object["reys_id"];?>>Редагувати</a></button>
                    </td>

                    <td>
                        <button class="delete"><a href=<?php echo "index.php?action=delete_reys&reys_id=".$object["reys_id"] ?> onclick="return confirm('Ви справді хочете інформацію про рейс?')">Видалити</a></button>
                    </td>
                    <?php } ?>
                </tr>
                <?php
    
                }?>
        </tbody>
    </table>
<?php } 
else{
?>
<h1 style="color: red;">Цей транспорт не виконує жодного рейсу</h1>
<?php
}?>
<br/>
<br/>
<?php } 
if($_SESSION["user"]["user_is_admin"]==true){ ?>
<h1 class="form-title">
   Технічний огляд транспорта   
</h1>
<?php 
$object = $query1->fetch();
if($object){
    $transportVin = $object["transport_ohlyad_vin"];
?>
<table class="content-table">
        <thead>
            <th>Дата техогляду</th>
            <th>Чи дозволено його використовувати</th>
        </thead>

        <tbody>
                <tr>
                    <td>
                        <?= $object["transport_ohlyad_date"];?>
                    </td>
                    <td>
                        <?php if($object["transport_ohlyad_result"]){
                            echo "Дозволяється для використання ";
                        }
                        else{
                            echo "Використовувати заборонено";
                        }
                        ?>
                    </td>
                </tr>
        <?php while($object = $query1->fetch()) {  ?>
                <tr>
                    <td>
                        <?= $object["transport_ohlyad_date"];?>
                    </td>
                    <td>
                        <?php if($object["transport_ohlyad_result"]){
                            echo "Дозволяється для використання ";
                        }
                        else{
                            echo "Використовувати заборонено";
                        }
                        ?>
                    </td>
                </tr>
                <?php
    
                }?>
        </tbody>
</table>
<br/>
<br/>
<button class="delete"><a href=<?php echo "index.php?action=delete_tz_ohlyad&transport_id=".$transportVin ?> onclick="return confirm('УВАГА! Якщо ви підтвердити видалення, то всі техогляди вказаного транспорту буде видалено. Ви впевнені, що хочете продовжити?')">Видалити історію техоглядів</a></button>        
<?php } 
else{
    ?>
        <h1 style="color: red;">Даний транспортний засіб ще не проходив техогляд</h1>
    <?php
}
?>
<br/>
<br/>
<?php } if(!isset($_GET["transport_ohlyad"])){ ?>
<h1 class="form-title">Дані про страхування транспортного засобу</h1>
<?php $object = $query2->fetch();
if($object){
?>
<table class="content-table">

        <thead>
            <?php if($_SESSION["user"]["user_is_admin"]==true){?>
            <th>Номер страхового полісу</th>
            <?php } ?>
            <th>Термін дії полісу</th>
            <th>Компанія, яка видала поліс</th>
        </thead>

        <tbody>
                <tr>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){?>
                    <td>
                        <?= $object["polis_tz_id"];?>
                    </td>
                    <?php } ?>
                    <td>
                        <?= $object["polis_tz_termin"];?>
                    </td>
                    <td>
                        <?= $object["company_name"]; ?>
                    </td>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <?php 
                            if($date[0]-$date[1]<=0 && $date[2]-$date[3]<=0 && $date[4]-$date[5]<=0){
                                ?>
                                <h1 style="color: red;">Термін дії полісу закінчився</h1>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_tz&polis_tz_id=".$object["polis_tz_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }


                            else if($date[0]-$date[1]<=0 && $date[2]-$date[3]<=2){
                                ?>
                                <h1 style="color: orange;">Термін дії полісу невдовзі закінчується</h1>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_tz&polis_tz_id=".$object["polis_tz_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }
                        ?>
                    </td>
                    <?php } ?>
                </tr>

        <?php while($object = $query2->fetch()) {  ?>
                <tr>
                    <td>
                        <?= $object["polis_tz_id"];?>
                    </td>
                    <td>
                        <?= $object["polis_tz_termin"];?>
                    </td>
                    <td>
                        <?= $object["company_name"]; ?>
                    </td>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                    <td>
                        <?php 
                            if($date[0]-$date[1]<=0 && $date[2]-$date[3]<=0 && $date[4]-$date[5]<=0){
                                ?>
                                <h1 style="color: red;">Термін дії полісу закінчився</h1>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_tz&polis_tz_id=".$object["polis_tz_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }


                            else if($date[0]-$date[1]<=0 && $date[2]-$date[3]<=2){
                                ?>
                                <h1 style="color: orange;">Термін дії полісу невдовзі закінчується</h1>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_tz&polis_tz_id=".$object["polis_tz_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }
                        ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php
    
                }?>
        </tbody>
</table>
<?php } 
else{
 ?>
 <h1 style="color: red;">Даний транспортний засіб ще не має страхового поліса</h1>
 <?php 
  }
} 

else if(!isset($_GET["transport_ohlyad"])){
    ?>
        <h1 style="color: red;">Вказаного транспорту не існує</h1>
    <?php
  }
 }
}
else{
    ?>
    <p class="form-title">Детальна інформація про транспорт</p>
    <h1 style="color: red;">Вказаного транспорту не існує</h1>
    <?php
}?>
<br/>
<br/>
<?php if(!isset($_GET["transport_ohlyad"])){ ?>
<button class="button"><a href="index.php?action=all_transports">Переглянути всі транспорти</button>
<?php }
else{
    ?> 
    <button class="button"><a href="index.php?action=all_tz_ohlyads">Техогляди транспортних засобів</a></button>
    <?php
 }
}
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач може переглядати цю інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>