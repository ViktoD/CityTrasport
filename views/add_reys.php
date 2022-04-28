<?php 
 if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
    $maxId = $PDOconn->query("SELECT MAX(reys_id) FROM reys")->fetch();

    $query1 = "SELECT marshrut_id, marshrut_start, marshrut_end
    FROM marshrut;";

    if(isset($_GET["marshrut_id"])&&$_GET["marshrut_id"]!="none"){
         $query2 = $PDOconn->prepare("SELECT pereviznyk_id
         FROM pereviznyk INNER JOIN marshrut ON pereviznyk_id=marshrut_pereviznyk
         WHERE marshrut_id=?;");
         $query2->execute(array($_GET["marshrut_id"]));
    
        $pereviznykId = $query2->fetch();
        
        $query3 = $PDOconn->prepare("SELECT transport_vin, transport_model, transport_type
        FROM transport INNER JOIN pereviznyk ON transport_pereviznyk=pereviznyk_id
        WHERE pereviznyk_id=?;");
        $query3->execute(array($pereviznykId[0]));
        
        
        $query4 = $PDOconn->prepare("SELECT vodiy_id, vodiy_surname, vodiy_name
        FROM vodiy INNER JOIN pereviznyk ON vodiy_pereviznyk=pereviznyk_id
        WHERE pereviznyk_id=?;");
        $query4->execute(array($pereviznykId[0]));

        

    }

    if(isset($_GET["submitted"])){
        
        if($_POST["reys_start_minute"]<10){

        $reys_start = $_POST["reys_start_hour"].":0".$_POST["reys_start_minute"];
        }
        else{
            $reys_start = $_POST["reys_start_hour"].":".$_POST["reys_start_minute"];
        }

        if($_POST["reys_end_minute"]<10){
        $reys_end = $_POST["reys_end_hour"].":0".$_POST["reys_end_minute"];
        }
        else{
            $reys_end = $_POST["reys_end_hour"].":".$_POST["reys_end_minute"];
        }
        $query = $PDOconn->prepare("INSERT INTO reys VALUES(?,?,?,?,?,?,?,?);");
        $query->execute(array($maxId["max"]+1,$_POST["transport_vin"],$_POST["marshrut_reys"],$_POST["vodiy_id"],$reys_start,
        $reys_end,$_POST["is_available"],$_SESSION["user"]["user_id"]));
        header("Location: index.php?action=all_reys&success_add=1");
    }

    if(!isset($_GET["marshrut_id"])){
?>

<h1 class="form-title">
        Додавання нового рейсу
</h1>

<div class="register-form-container">

 
    
    <div class="form-fields">
        <div class="form-field">        
            <select name="reys_marshrut" class="input" id="marshrut">
                <option value="none">Оберіть маршрут</option>
                <?php foreach($PDOconn->query($query1) as $object){  ?>
                <option value=<?php echo $object["marshrut_id"]?>><?php echo "(".$object["marshrut_id"].") ".$object["marshrut_start"]."-".$object["marshrut_end"]; ?></option>    
                <?php } ?>
            </select>
        </div>  
    </div>


</div>

<?php }
?>
<p id="choose"></p>
<?php
if(isset($_GET["marshrut_id"])){
    ?>

<div class="register-form-container">
    
    <form action="index.php?action=add_reys&submitted=1" method="POST">
        <div class="form-fields">
                <?php
                if(isset($_GET["marshrut_id"]) && $_GET["marshrut_id"]=="none"){
                    ?>
                    <h2 style="color: red;">Оберіть маршрут</h2>
                    <?php 
                }


                else{
                    
                    ?>
                    <input type="hidden" name="marshrut_reys" value=<?php echo $_GET["marshrut_id"]; ?>>
                    <div class="form-field">
                        <h1 class="form-title">Транспортний засіб</h1>
                        <select name="transport_vin" class="input">
                        <?php while($object = $query3->fetch()){  ?>
                            <option value=<?php echo $object["transport_vin"]?>><?php echo "(".$object["transport_vin"].") ".$object["transport_model"]." ".$object["transport_type"]; ?></option>    
                        <?php } ?>
                        </select>
                    </div>

                <div class="form-field">
                    <h1 class="form-title">Водій</h1>
                    <select name="vodiy_id" class="input">
                    <?php while($object = $query4->fetch()){  ?>
                        <option value=<?php echo $object["vodiy_id"]?>><?php echo "(".$object["vodiy_id"].") ".$object["vodiy_surname"]." ".$object["vodiy_name"]; ?></option>    
                    <?php } ?>
                    </select>
                </div>

                <div class="form-field">
                    <h1 class="form-title">Початок рейсу</h1>
                    <h3>Годин</h3>
                    <input type="number" name="reys_start_hour" class="input" min=7 max=22 value="7">

                    <h3>Хвилин</h3>
                    <input type="number" name="reys_start_minute" class="input" min=0 max=59 value="0">
                </div>

                <div class="form-field">
                    <h1 class="form-title">Кінець рейсу</h1>
                    <h3>Годин</h3>
                    <input type="number" name="reys_end_hour" class="input"  min=7 max=22 value="7" >

                    <h3>Хвилин</h3>
                    <input type="number" name="reys_end_minute" class="input" min=0 max=59 value="0">
                </div>

                <div class="form-field">
                    <h1 class="form-title">Чи доступний</h1>
                    <select name="is_available" class="input">
                        <option value="true">Доступний</option>
                        <option value="false">Не доступний</option>
                    </select>
                </div>
                <?php
                }
                ?>
        </div>
        <?php if(isset($_GET["marshrut_id"]) && $_GET["marshrut_id"]!="none"){ ?>
        <button class="button" type="submit">Додати рейс</div>
        <?php } ?>
    </form>
    <br/>
    <button class="delete"><a href="index.php?action=all_reys">Скасувати</a></button>
</div>

    <?php } ?> 
<script>
    $(document).ready(function() {
        $("#marshrut").on('change',function(){
            var marshrutId = $("#marshrut").val();
            $.ajax({
                url:"index.php?action=add_reys",
                type:'GET',
                data:"&marshrut_id="+marshrutId,
                success: function(html){
                    $("#choose").html(html);
                }
            })
        });
    });
</script>

<?php }
else{
    ?>
        <h1 style="color: red;">Тільки авторизований користувач, який має права адміністратора може додавати інформацію</h1>
        <button class="button"><a href="index.php?action=main">На головну</a></button>
    <?php
}
?>