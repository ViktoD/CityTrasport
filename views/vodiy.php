<?php  
   if(!empty($_SESSION["user"])){
    if(isset($_GET["vodiy_id"])&& !empty($_GET["vodiy_id"])){

        $query1 = $PDOconn->prepare("SELECT vodiy.vodiy_id, vodiy.vodiy_surname, vodiy.vodiy_name, vodiy.vodiy_lastname, 
        vodiy.vodiy_birthday, vodiy.vodiy_address, pereviznyk.pereviznyk_surname, pereviznyk.pereviznyk_name
        FROM vodiy INNER JOIN pereviznyk ON vodiy.vodiy_pereviznyk = pereviznyk.pereviznyk_id
        WHERE vodiy.vodiy_id=?");
        $query1->execute(array($_GET["vodiy_id"]));

        $query2 = $PDOconn->prepare("SELECT marshrut.marshrut_start, marshrut.marshrut_end, reys_id, reys.reys_transport, reys.reys_start, reys.reys_end, is_available
        FROM (reys INNER JOIN marshrut ON reys.reys_marshrut=marshrut.marshrut_id)
        INNER JOIN vodiy ON reys.reys_vodiy = vodiy.vodiy_id 
        WHERE vodiy.vodiy_id=?");
        $query2->execute(array($_GET["vodiy_id"]));
    
        $query3 = $PDOconn->prepare("SELECT medohlyad_vodiy,medohlyad_date, medohlyad_result 
        FROM medohlyad
        WHERE medohlyad_vodiy=?
        ORDER BY medohlyad_date DESC;");
        $query3->execute(array($_GET["vodiy_id"]));

        $query4 = $PDOconn->prepare("SELECT polis_vodiy.polis_vodiy_id, polis_vodiy.polis_vodiy_termin, company.company_name
        FROM (polis_vodiy INNER JOIN vodiy ON polis_vodiy.polis_vodiy_id=vodiy.vodiy_polis)
        INNER JOIN company ON polis_vodiy.polis_vodiy_company=company.company_id
        WHERE vodiy.vodiy_id=?");
        $query4->execute(array($_GET["vodiy_id"]));
    
        $query5 = $PDOconn->prepare("SELECT date_part('year',polis_vodiy_termin), date_part('year',CURRENT_DATE),
        date_part('month',polis_vodiy_termin), date_part('month',CURRENT_DATE), date_part('day',polis_vodiy_termin),
        date_part('day',CURRENT_DATE) FROM vodiy INNER JOIN polis_vodiy ON vodiy_polis=polis_vodiy_id
        WHERE vodiy_id=?");
        $query5->execute(array($_GET["vodiy_id"]));
        $date = $query5->fetch();
        
    

    ?>


    <h1 class="form-title">
        Детальна інформація про водія <?php $vodiy = $query1->fetch(); 
        if($vodiy){ ?>    
    </h1>

    <table class="content-table">
        <thead>
            <?php if($_SESSION["user"]["user_is_admin"]==true){?>
            <th>Код водія</th>
            <?php } ?>
            <th>Прізвище водія</th>
            <th>Ім'я водія</th>
            <th>Ім'я по-батькові водія</th>
            <th>Дата народження</th>
            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
            <th>Адреса</th>
            <?php } ?>
            <th>Прізвище перевізника</th>
            <th>Ім'я перевізника</th>
        </thead>

        <tbody>
            <tr>
                <?php if($_SESSION["user"]["user_is_admin"]==true){?>
                <td>
                    <?= $vodiy["vodiy_id"]; ?>
                </td>
                <?php } ?>
                <td>
                    <?= $vodiy["vodiy_surname"];?>
                </td>
                <td>
                    <?= $vodiy["vodiy_name"];?>
                </td>
                <td>
                    <?= $vodiy["vodiy_lastname"]; ?>
                </td>
            
                <td>
                    <?= $vodiy["vodiy_birthday"]; ?>
                </td>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <?= $vodiy["vodiy_address"]; ?>
                </td>
                <?php } ?>
                <td>
                    <?= $vodiy["pereviznyk_surname"]; ?>
                </td>
                <td>
                    <?= $vodiy["pereviznyk_name"]; ?>
                </td>
            </tr>
        </tbody>
    </table>
<?php if(!isset($_GET["vodiy_ohlyad"])){ ?>
    <h1 class="form-title">
        Список рейсів, які виконує водій    
    </h1>
    
    <?php $object=$query2->fetch();
    if($object){
    ?>
        <table class="content-table">
            <thead>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <th>Номер рейсу</th>
                <?php } ?>
                <th>Початок маршруту</th>
                <th>Кінець маршруту</th>
                <th>Номер транспорту</th>
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
                        <?php echo $object["reys_transport"] ?>
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
            <?php while($object = $query2->fetch()) {  ?>
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
                        <?php echo $object["reys_transport"] ?>
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
        <h1 style="color: red;">Цей водій не виконує жодного рейсу</h1>
    <?php
    }?>
    <br/>
    <br/>
<?php } 
    if($_SESSION["user"]["user_is_admin"]==true){
?>
    <h1 class="form-title">
        Медогляди водія    
    </h1>
    <?php $object=$query3->fetch();
    if($object){
        $medohlyadVodiy = $object["medohlyad_vodiy"];
    ?>
        <table class="content-table">
            <thead>
                <th>Дата медогляду</th>
                <th>Чи дозволено йому працювати</th>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <?= $object["medohlyad_date"];?>
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
                </tr>
            <?php while($object = $query3->fetch()) {  ?>
                <tr>
                    <td>
                        <?= $object["medohlyad_date"];?>
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
                </tr>
                <?php
    
            }?>
            </tbody>
        </table>
        <button class="delete"><a href=<?php echo "index.php?action=delete_medohlyad&medohlyad_id=".$medohlyadVodiy ?> onclick="return confirm('УВАГА! Якщо ви підтвердити видалення, то всі медогляди вказаного водія буде видалено. Ви впевнені, що хочете продовжити?')">Видалити історію медоглядів водія</a></button>
    <?php }
    else{
    ?>
        <h1 style="color: red;">Даний водій ще не проходив медогляди</h1>
    <?php
    }
   } ?>
    <br/>
    <br/>
    <?php if(!isset($_GET["vodiy_ohlyad"])){ ?>
    <h1 class="form-title">Дані про страхування водія</h1>
    <?php $object=$query4->fetch();
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
                        <?= $object["polis_vodiy_id"];?>
                    </td>
                    <?php } ?>
                    <td>
                        <?= $object["polis_vodiy_termin"];?>
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
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }


                            else if($date[0]-$date[1]<=0 && $date[2]-$date[3]<=2){
                                ?>
                                <h1 style="color: orange;">Термін дії полісу невдовзі закінчується</h1>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }
                        ?>
                    </td>
                    <?php } ?>
                </tr>
            <?php while($object = $query4->fetch()) {  ?>
                <tr>
                    <?php if($_SESSION["user"]["user_is_admin"]==true){?>
                    <td>
                        <?= $object["polis_vodiy_id"];?>
                    </td>
                    <?php } ?>
                    <td>
                        <?= $object["polis_vodiy_termin"];?>
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
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"]; ?>>Поновити поліс</a></button>
                                <?php
                                
                            }
                            else if($date[0]-$date[1]<=0 && $date[2]-$date[3]<=2){
                                ?>
                                <h1 style="color: orange;">Термін дії полісу невдовзі закінчується</h1>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_polis_vodiy&polis_vodiy_id=".$object["polis_vodiy_id"];?>>Поновити поліс</a></button>
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
    else {
    ?>
        <h1 style="color: red;">Даний водій ще не має страхового поліса</h1>
    <?php
    }?>

    <?php } }
    
    else{
        ?>
        <h1 style="color: red;">Вказаного водія не існує</h1>
        <?php
    }?>
    
<?php 
 }

    else{
    ?>
        <h1 class="form-title">Детальна інформація про водія</h1>
        <h1 style="color: red;">Вказаного водія не існує</h1>
    <?php
    }?> 
<br/>
<br/>
<?php if(!isset($_GET["vodiy_ohlyad"])){ ?>
<button class="button"><a href="index.php?action=all_vodiys">Переглянути всіх водіїв</button>
<?php } else{
    ?>
    <button class="button"><a href="index.php?action=all_medohlyads">Переглянути всі медогляди всіх водіїв</a></button>
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
