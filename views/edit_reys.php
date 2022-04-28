<?php 
 if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    if(isset($_GET["reys_id"])){

    $query1 = $PDOconn->prepare("SELECT *
    FROM reys WHERE reys_id=?;");
    $query1->execute(array($_GET["reys_id"]));
    $reys = $query1->fetch();

    $query2 = $PDOconn->prepare("SELECT pereviznyk_id
    FROM pereviznyk INNER JOIN marshrut ON pereviznyk_id=marshrut_pereviznyk
    WHERE marshrut_id=?;");
    $query2->execute(array($reys["reys_marshrut"]));
    $pereviznykId = $query2->fetch();

    $query3 = $PDOconn->prepare("SELECT transport_vin, transport_model, transport_type
    FROM transport INNER JOIN pereviznyk ON transport_pereviznyk=pereviznyk_id
    WHERE pereviznyk_id=?;");
    $query3->execute(array($pereviznykId[0]));

    $query4 = $PDOconn->prepare("SELECT marshrut_id, marshrut_start, marshrut_end 
    FROM marshrut WHERE marshrut_id=?;");
    $query4->execute(array($reys["reys_marshrut"]));
    $marshrut = $query4->fetch();

    $query5 = $PDOconn->prepare("SELECT vodiy_id, vodiy_surname, vodiy_name
    FROM vodiy INNER JOIN pereviznyk ON vodiy_pereviznyk=pereviznyk_id
    WHERE pereviznyk_id=?;");
    $query5->execute(array($pereviznykId[0]));
    
    
    if(isset($_GET["submitted"])){
        
        $query = $PDOconn->prepare("UPDATE reys SET
        reys_transport=?, reys_vodiy=?, reys_start=?, reys_end=?, is_available=?, reys_user_id=?
        WHERE reys_id=?;");
        $reysStart = $_POST["reys_start_hour"].":".$_POST["reys_start_minute"];
        $reysEnd = $_POST["reys_end_hour"].":".$_POST["reys_end_minute"];
        $query->execute(array($_POST["transport_vin"],$_POST["vodiy_id"],$reysStart,$reysEnd,$_POST["is_available"],$_SESSION["user"]["user_id"],$_GET["reys_id"]));
        header("Location: index.php?action=all_reys&success_update=1");
    }

?>



<div class="register-form-container">

    <h1 class="form-title">
        Редагування інформації про рейс
    </h1>
    
    <form action=<?php echo "index.php?action=edit_reys&submitted=1&reys_id=".$_GET["reys_id"];?> method="POST">
        <div class="form-fields">
            <div class="form-field">
                <h1 class="form-title">Номер рейсу</h1>
                <input type="text" class="input" disabled value=<?php 
                    echo $_GET["reys_id"];
                ?>>
            </div>

            <div class="form-field">
                <h1 class="form-title">Маршрут</h1>
                <input type="text" class="input" disabled value='<?php 
                    echo "(".$marshrut["marshrut_id"].") ".$marshrut["marshrut_start"]."-".$marshrut["marshrut_end"];
                ?>'>
            </div>

            <div class="form-field">
                <h1 class="form-title">Транспортний засіб</h1>
                    <select name="transport_vin" class="input">
                        <?php while($object = $query3->fetch()){  ?>
                            <option value=<?php echo $object["transport_vin"];?> <?php if($object["transport_vin"]==$reys["reys_transport"]){ ?> selected <?php } ?> ><?php echo "(".$object["transport_vin"].") ".$object["transport_model"]." ".$object["transport_type"]; ?></option>    
                        <?php } ?>
                        </select>
            </div>

                <div class="form-field">
                    <h1 class="form-title">Водій</h1>
                    <select name="vodiy_id" class="input">
                    <?php while($object = $query5->fetch()){  ?>
                        <option value=<?php echo $object["vodiy_id"]?> <?php if($object["vodiy_id"]==$reys["reys_vodiy"]){ ?> selected <?php } ?>><?php echo "(".$object["vodiy_id"].") ".$object["vodiy_surname"]." ".$object["vodiy_name"]; ?></option>    
                    <?php } ?>
                    </select>
                </div>

                <div class="form-field">
                    <h1 class="form-title">Початок рейсу</h1>
                    <h3>Годин</h3>
                    <input type="number" name="reys_start_hour" class="input" min=7 max=22 value=<?php echo substr($reys["reys_start"],0,2); ?>>

                    <h3>Хвилин</h3>
                    <input type="number" name="reys_start_minute" class="input" min=0 max=59 value=<?php echo substr($reys["reys_start"],3,2); ?>>
                </div>

                <div class="form-field">
                    <h1 class="form-title">Кінець рейсу</h1>
                    <h3>Годин</h3>
                    <input type="number" name="reys_end_hour" class="input"  min=7 max=22 value=<?php echo substr($reys["reys_end"],0,2); ?> >

                    <h3>Хвилин</h3>
                    <input type="number" name="reys_end_minute" class="input" min=0 max=59 value=<?php echo substr($reys["reys_end"],3,2); ?>>
                </div>

                <div class="form-field">
                    <h1 class="form-title">Чи доступний</h1>
                    <select name="is_available" class="input">
                        <?php if($reys["is_available"]){ ?>
                        <option value="true">Доступний</option>
                        <option value="false">Не доступний</option>
                        <?php } 
                        
                        else{
                            ?>
                            <option value="true">Доступний</option>
                            <option value="false" selected>Не доступний</option>
                        <?php
                        }?>
                    </select>
                </div>
        </div>
        <button class="button" type="submit">Зберегти зміни</div>
        <br/>
        <button class="delete"><a href="index.php?action=all_reys">Скасувати</a></button>
    </form>
</div>
<?php }

else{
    ?>
        <h3 style="color: red;">Вказаного рейсу не існує</h3>
        <button class="button"><a href="index.php?action=all_reys">Показати всі рейси</a></button>
    <?php
 }
}
else{
    ?>
    <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може редагувати інформацію</h1>
    <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>