<?php  

    if(isset($_GET["marshruts_id"]) && !empty($_GET["marshruts_id"])){
        $query = $PDOconn->prepare("SELECT zupynka.zupynka_name,marshrut.marshrut_start,marshrut.marshrut_end,zupynka_id
        FROM (zupynka INNER JOIN zupynka_marshrut ON zupynka_marshrut.marshrut_zupynka_id=zupynka.zupynka_id)
        INNER JOIN marshrut ON zupynka_marshrut.zupynka_marshrut_id=marshrut.marshrut_id 
        WHERE marshrut.marshrut_id=?
        ORDER BY zupynka_name;");
        $query->execute(array($_GET["marshruts_id"]));

        $query2 = $PDOconn->prepare("SELECT transport.transport_vin, transport.transport_model, transport.transport_type 
        FROM (transport INNER JOIN pereviznyk ON transport.transport_pereviznyk=pereviznyk.pereviznyk_id)
        INNER JOIN marshrut ON marshrut.marshrut_pereviznyk = pereviznyk.pereviznyk_id
        WHERE marshrut.marshrut_id =?;");
        $query2->execute(array($_GET["marshruts_id"]));

        if(!empty($_SESSION["user"])){
        $query3 = $PDOconn->prepare("SELECT reys_id, reys_start, reys_end, is_available
        FROM reys WHERE reys_marshrut=?;");
        $query3->execute(array($_GET["marshruts_id"]));
        }

        else{
            $query3 = $PDOconn->prepare("SELECT reys_id, reys_start, reys_end, is_available
            FROM reys WHERE reys_marshrut=? AND is_available=true;");
            $query3->execute(array($_GET["marshruts_id"]));
        }

        $query1 = $PDOconn->prepare("SELECT marshrut_id, marshrut_start, marshrut_end, marshrut_price, pereviznyk_surname, pereviznyk_name
        FROM marshrut INNER JOIN pereviznyk ON marshrut_pereviznyk=pereviznyk_id
        WHERE marshrut_id=?;");
        $query1->execute(array($_GET["marshruts_id"]));
    
?>
<p class="form-title">Детальна інформація про маршрут</p>
<?php $object=$query1->fetch();
if($object){?>
<table class="content-table">
    <thead>
        <th>Номер маршруту</th>
        <th>Початок маршруту</th>
        <th>Кінець маршруту</th>
        <th>Ціна проїзду</th>
        <?php if(!empty($_SESSION["user"])){ ?>
        <th>Прізвище перевізника</th>
        <th>Ім'я перевізника
        <?php } ?>
    </thead>

    <tbody>
                <tr>
                    <td>
                        <?= $object["marshrut_id"];?>
                    </td>
                    <td>
                        <?= $object["marshrut_start"];?>
                    </td>
                    <td>
                        <?= $object["marshrut_end"]; ?>
                    </td>
                    <td>
                        <?= $object["marshrut_price"]; ?>
                    </td>
                    <?php if(!empty($_SESSION["user"])){ ?>
                    <td>
                        <?= $object["pereviznyk_surname"] ?>
                    </td>
                    <td>
                        <?= $object["pereviznyk_name"]; ?>
                    </td>
                    <?php } ?>
                </tr>
        </tbody>
</table>
<br/>

<?php
if(!empty($_SESSION["user"])&& $_SESSION["user"]["user_is_admin"]==true) {
    if(isset($_GET["success_delete"])){
        ?>
        <h1 style="color: red;">Дані про зупинку було успішно видалено</h1>
        <?php
    } ?> 
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">Зупинку було успішно додано</h1>
    <?php
    }
}
?>
    
<p class="form-title">Список зупинок маршруту <?php $object = $query->fetch(); if($object){ echo $object["marshrut_start"].'-'.$object["marshrut_end"]; } else{ echo "";} ?> 
    <?php if($object){ ?>
<table class="content-table">
    <thead>
        <th>Зупинка</th>
    </thead>

    <tbody>
        <tr>
            <td>
                <?= $object["marshrut_start"]?>
            </td>
            <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
            <td>
                <button class="delete"><a href=<?php echo "index.php?action=delete_zupynka&zupynka_id=".$object["zupynka_id"]."&marshruts_id=".$_GET["marshruts_id"];?> onclick="return confirm('Ви справді хочете інформацію про зупинку?')">Видалити</a></button>
            </td>
            <?php } ?>
        </tr>
        <?php while($object = $query->fetch()) { ?>
            <tr>
                <td>
                    <?= $object["zupynka_name"];?>
                </td>
                <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_zupynka&zupynka_id=".$object["zupynka_id"]."&marshruts_id=".$_GET["marshruts_id"];?> onclick="return confirm('Ви справді хочете інформацію про зупинку?')">Видалити</a></button>
                </td>
                <?php } ?>
            </tr>
                <?php
    
                }?>
        </tbody>
    </table>
    <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
    <button class="add"><a href=<?php echo "index.php?action=add_zupynka&marshruts_id=".$_GET["marshruts_id"]; ?>>Додати нову зупинку до маршруту</a></button>
    <?php } ?>
    <br/>
    <br/>
    <?php }
    else{
        ?>
        <h1 style="color: red;">Даний маршрут ще не має зупинок</h1>
    <?php   
    }
    ?>

    <p class="form-title">Список транспортних засобів, які рухаються за вказаним маршрутом
    <?php $object=$query2->fetch();
    if($object){ ?>
    <table class="content-table">
        <thead>
            <th>Номер транспортного засобу</th>
            <?php if(!empty($_SESSION["user"])){ ?>
            <th>Модель транспортного засобу</th>
            <th>Вид транспортного засобу</th>
            <?php } ?>
        </thead>

        <tbody>
            <tr>
                <td>
                    <?= $object["transport_vin"];?>
                </td>
                <?php if(!empty($_SESSION["user"])){ ?>
                <td>
                    <?= $object["transport_model"];?>
                </td>
                <td>
                    <?= $object["transport_type"]; ?>
                </td>
                <td>
                    <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$object["transport_vin"]?>>Переглянути</a></button>
                </td>
                <?php } 
                 if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
                ?>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$object["transport_vin"];?>>Редагувати</a></button>
                </td>

                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$object["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити дані</a></button>
                </td>
                <?php } ?>
            </tr>
        <?php while($object = $query2->fetch()) { ?>
                <tr>
                    <td>
                        <?= $object["transport_vin"];?>
                    </td>
                    <?php if(!empty($_SESSION["user"])){ ?>
                    <td>
                        <?= $object["transport_model"];?>
                    </td>
                    <td>
                        <?= $object["transport_type"]; ?>
                    </td>
                    <td>
                        <button class="view"><a href=<?php echo "index.php?action=transport&transport_id=".$object["transport_vin"]?>>Переглянути</a></button>
                    </td>
                    <?php } 
                    if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
                    ?>
                    <td>
                        <button class="edit"><a href=<?php echo "index.php?action=edit_transport&transport_id=".$object["transport_vin"];?>>Редагувати</a></button>
                    </td>

                    <td>
                        <button class="delete"><a href=<?php echo "index.php?action=delete_transport&transport_id=".$object["transport_vin"]; ?> onclick="return confirm('Ви справді хочете інформацію про транспорт?')">Видалити дані</a></button>
                    </td>
                    <?php } ?>
                </tr>
                <?php
    
                }?>
        </tbody>
    </table>
   <br/>
   <br/>
   <?php } 
   else{
    ?>
        <h1 style="color: red;">За вказаним маршрутом не рухається жоден транспортний засіб</h1>
    <?php 
   }
   ?>
   <h1 class="form-title">Список рейсів, які проходять через цей маршрут</h1>
   <?php $object = $query3->fetch();
   if($object){ ?>
   <table class="content-table">
        <thead>
            <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){?>
            <th>Номер рейсу</th>
            <?php } ?>
            <th>Час початку рейсу</th>
            <th>Час прибуття</th>
            <th>Чи доступний</th>
        </thead>

        <tbody>
            <tr>
                <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){?>
                <td>
                    <?= $object["reys_id"];?>
                </td>
                <?php } ?>
                <td>
                    <?= $object["reys_start"];?>
                </td>
                <td>
                    <?= $object["reys_end"]; ?>
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
                <?php if(!empty($_SESSION) && $_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_reys&reys_id=".$object["reys_id"];?>>Редагувати</a></button>
                </td>

                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_reys&reys_id=".$object["reys_id"] ?> onclick="return confirm('Ви справді хочете інформацію про рейс?')">Видалити</a></button>
                </td>
                <?php } ?>
            </tr>
        <?php while($object = $query3->fetch()) { ?>
                <tr>
                    <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){?>
                    <td>
                        <?= $object["reys_id"];?>
                    </td>
                    <?php } ?>
                    <td>
                        <?= $object["reys_start"];?>
                    </td>
                    <td>
                        <?= $object["reys_end"]; ?>
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
                    <?php if(!empty($_SESSION) && $_SESSION["user"]["user_is_admin"]==true){ ?>
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
   <br/>
   <br/>
    <?php }
    else{
        ?>
        <h1 style="color: red;">Через вказаний маршрут не проходить жоден рейс</h1>
    <?php
    } ?>
<?php } 
else{
    ?>
    <h1 style="color: red;">Вказаного маршруту не існує</h1>
    <?php }
    ?>
<br/>

<?php }

else{
    ?>
        <p class="form-title">Детальна інформація про маршрут</p>
        <h1 style="color: red;">Вказаного маршруту не існує</h1>
    <?php
}?>
<button class="button"><a href="index.php?action=all_marshruts">Показати всі маршрути</a></button>