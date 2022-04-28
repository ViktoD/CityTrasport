<?php
  if(!empty($_SESSION["user"])){
    if(isset($_GET["pereviznyk_id"])){
        $query = $PDOconn->prepare("SELECT * FROM pereviznyk
        WHERE pereviznyk_id=?;");
        $query->execute(array($_GET["pereviznyk_id"]));

        $pereviznyk = $query->fetch();

        $query1 = $PDOconn->prepare("SELECT marshrut_id, marshrut_start, marshrut_end, marshrut_price
        FROM marshrut INNER JOIN pereviznyk ON marshrut_pereviznyk=pereviznyk_id
        WHERE pereviznyk_id=?;");
        $query1->execute(array($_GET["pereviznyk_id"]));

        $query2 = $PDOconn->prepare("SELECT transport_vin, transport_model, transport_type, transport_seats
        FROM transport INNER JOIN pereviznyk ON transport_pereviznyk=pereviznyk_id
        WHERE pereviznyk_id=?;");
        $query2->execute(array($_GET["pereviznyk_id"]));

        $query3 = $PDOconn->prepare("SELECT vodiy_id, vodiy_surname, vodiy_name, vodiy_lastname, vodiy_address
        FROM vodiy INNER JOIN pereviznyk ON vodiy_pereviznyk=pereviznyk_id
        WHERE pereviznyk_id=?;");
        $query3->execute(array($_GET["pereviznyk_id"]));


    if($pereviznyk){
?>
        <p class="form-title">Детальна інформація про перевізника</p>
        <table class="content-table">
            <thead>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <th>Код перевізника</th>
                <?php } ?>
                <th>Прізвище перевізника</th>
                <th>Ім'я перевізника</th>
                <th>Ім'я по-батькові</th>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <th>Номер телефону</th>
                <th>Адреса перевізника</th>
                <?php } ?>
            </thead>

        <tbody>
            <tr>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <?= $pereviznyk["pereviznyk_id"]; ?>
                </td>
                <?php } ?>
                <td>
                    <?= $pereviznyk["pereviznyk_surname"];?>
                </td>
                <td>
                    <?= $pereviznyk["pereviznyk_name"];?>
                </td>
                <td>
                    <?= $pereviznyk["pereviznyk_lastname"];?>
                </td>
                <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <?= $pereviznyk["pereviznyk_phone"]; ?>
                </td>
                <td>
                    <?= $pereviznyk["pereviznyk_address"]; ?>
                </td>
                <?php } ?>
            </tr>
        </tbody>
    </table>

        <p class="form-title">Список маршрутів, які обслуговує даний перевізник</p>
        <?php $marshrut = $query1->fetch();
            if($marshrut){ ?>
                <table class="content-table">
                    <thead>
                        <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                        <th>Номер маршруту</th>
                        <?php } ?>
                        <th>Початок маршруту</th>
                        <th>Кінець маршруту</th>
                        <th>Ціна проїзду</th>
                    </thead>

                    <tbody>
                        <tr>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <?= $marshrut["marshrut_id"]; ?>
                            </td>
                            <?php } ?>
                            <td>
                                <?= $marshrut["marshrut_start"];?>
                            </td>
                            <td>
                                <?= $marshrut["marshrut_end"];?>
                            </td>
                            <td>
                                <?= $marshrut["marshrut_price"];?>
                            </td>
                            <td>
                                <button class="view"><a href=<?php echo "index.php?action=marshrut&marshruts_id=".$marshrut["marshrut_id"];?>>Переглянути</a></button>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_marshrut&marshruts_id=".$marshrut["marshrut_id"];?>>Редагувати</a></button>
                            </td>
                            <td>
                                <button class="delete"><a href=<?php echo "index.php?action=delete_marshrut&marshruts_id=".$marshrut["marshrut_id"] ?> onclick="return confirm('Ви справді хочете інформацію про маршрут?')">Видалити дані</a></button>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php while($marshrut= $query1->fetch()){ ?>
                        <tr>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <?= $marshrut["marshrut_id"]; ?>
                            </td>
                            <?php } ?>
                            <td>
                                <?= $marshrut["marshrut_start"];?>
                            </td>
                            <td>
                                <?= $marshrut["marshrut_end"];?>
                            </td>
                            <td>
                                <?= $marshrut["marshrut_price"];?>
                            </td>
                            <td>
                                <button class="view"><a href=<?php echo "index.php?action=marshrut&marshruts_id=".$marshrut["marshrut_id"];?>>Переглянути</a></button>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_marshrut&marshruts_id=".$marshrut["marshrut_id"];?>>Редагувати</a></button>
                            </td>
                            <td>
                                <button class="delete"><a href=<?php echo "index.php?action=delete_marshrut&marshruts_id=".$marshrut["marshrut_id"] ?> onclick="return confirm('Ви справді хочете інформацію про маршрут?')">Видалити дані</a></button>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } 
                else{
                    ?>
                        <h1 style="color: red;">Даний перевізник не обслуговує жоден маршрут</h1>
                    <?php 
                }
            ?>
            <p class="form-title">Список транспортів, які обслуговує даний перевізник</p>
            <?php $transport = $query2->fetch();
            if($transport){ ?>
                <table class="content-table">
                    <thead>
                        <th>Номер транспорту</th>
                        <th>Модель транспорту</th>
                        <th>Тип транспорту</th>
                        <th>Кількість місць для сидіння</th>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <?= $transport["transport_vin"]; ?>
                            </td>
                            <td>
                                <?= $transport["transport_model"];?>
                            </td>
                            <td>
                                <?= $transport["transport_type"];?>
                            </td>
                            <td>
                                <?= $transport["transport_seats"];?>
                            </td>
                            <td>
                                <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$transport["transport_vin"]?>>Переглянути</a></button>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$transport["transport_vin"];?>>Редагувати</a></button>
                            </td>
                            <td>
                                <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$transport["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити дані</a></button>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php while($transport = $query2->fetch()){ ?>
                        <tr>
                            <td>
                                <?= $transport["transport_vin"]; ?>
                            </td>
                            <td>
                                <?= $transport["transport_model"];?>
                            </td>
                            <td>
                                <?= $transport["transport_type"];?>
                            </td>
                            <td>
                                <?= $transport["transport_seats"];?>
                            </td>
                            <td>
                                <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$transport["transport_vin"]?>>Переглянути</a></button>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$transport["transport_vin"];?>>Редагувати</a></button>
                            </td>
                            <td>
                                <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$transport["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити дані</a></button>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } 
                else{
                    ?>
                        <h1 style="color: red;">Даний перевізник не обслуговує жоден транспорт</h1>
                    <?php 
                }
            ?>
            <p class="form-title">Список водіїв, які обслуговує даний перевізник</p>
            <?php $vodiy = $query3->fetch();
            if($vodiy){ ?>
                <table class="content-table">
                    <thead>
                        <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                        <th>Номер водія</th>
                        <?php } ?>
                        <th>Прізвище водія</th>
                        <th>Ім'я водія</th>
                        <th>Ім'я по-батькові</th>
                        <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                        <th>Адреса водія</th>
                        <?php } ?>
                    </thead>

                    <tbody>
                        <tr>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
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
                                <?= $vodiy["vodiy_lastname"];?>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <?= $vodiy["vodiy_address"];?>
                            </td>
                            <?php } ?>
                            <td>
                                <button class="view"><a href=<?php echo "index.php?action=vodiy&vodiy_id=".$vodiy["vodiy_id"]?>>Переглянути</a></button>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_vodiy&vodiy_id=".$vodiy["vodiy_id"];?>>Редагувати</a></button>
                            </td>
                            <td>
                                <button class="delete"><a href=<?php echo "index.php?action=delete_vodiy&vodiy_id=".$vodiy["vodiy_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про водія?')">Видалити дані</a></button>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php while($vodiy = $query3->fetch()){ ?>
                        <tr>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
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
                                <?= $vodiy["vodiy_lastname"];?>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <?= $vodiy["vodiy_address"];?>
                            </td>
                            <?php } ?>
                            <td>
                                <button class="view"><a href=<?php echo "index.php?action=vodiy&vodiy_id=".$vodiy["vodiy_id"]?>>Переглянути</a></button>
                            </td>
                            <?php if($_SESSION["user"]["user_is_admin"]==true){ ?>
                            <td>
                                <button class="edit"><a href=<?php echo "index.php?action=edit_vodiy&vodiy_id=".$vodiy["vodiy_id"];?>>Редагувати</a></button>
                            </td>
                            <td>
                                <button class="delete"><a href=<?php echo "index.php?action=delete_vodiy&vodiy_id=".$vodiy["vodiy_id"]; ?> onclick="return confirm('Ви справді хочете інформацію про водія?')">Видалити дані</a></button>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } 
                else{
                    ?>
                        <h1 style="color: red;">Даний перевізник не обслуговує жодного водія</h1>
                    <?php 
                }
            ?>
    <?php }
    else{
       ?>
        <h1 style="color: red;">Вказаного перевізника не існує</h1>
       <?php
   } 

}
else{
    ?>
     <p class="form-title">Детальна інформація про перевізника</p>
        <h1 style="color: red;">Вказаного перевізника не існує</h1>
    <?php
}

?>

<br/>
<br/>
<button class="button"><a href="index.php?action=all_pereviznyks">Переглянути всіх перевізників</a></button>
<?php } 
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач може переглядати цю інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>