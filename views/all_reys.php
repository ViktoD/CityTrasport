<?php

    if(!empty($_SESSION["user"])){
     $start=0;
     $page=1;
     $countReys = $PDOconn->query("SELECT COUNT(*) FROM reys;")->fetch();
     $numberPages = (int)$countReys["count"]/50;
     if($countReys["count"]%50 != 0){
         $numberPages++;
     }
 
     if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
         $page = $_GET["page"];
         $start = 50*($page-1);
         $query3 = "SELECT marshrut.marshrut_start, marshrut.marshrut_end, marshrut.marshrut_price, reys_id, reys.reys_transport, reys.reys_start, reys.reys_end, is_available, user_surname, user_name
         FROM (reys INNER JOIN marshrut ON reys.reys_marshrut=marshrut.marshrut_id)
         INNER JOIN users ON user_id=reys_user_id 
         ORDER BY reys.reys_start LIMIT 50 OFFSET {$start};";

     }
 
     else{
         $query3 = "SELECT marshrut.marshrut_start, marshrut.marshrut_end, marshrut.marshrut_price, reys_id, reys.reys_transport, reys.reys_start, reys.reys_end, is_available, user_surname, user_name
         FROM (reys INNER JOIN marshrut ON reys.reys_marshrut=marshrut.marshrut_id)
         INNER JOIN users ON user_id=reys_user_id 
         ORDER BY reys.reys_start LIMIT 50 OFFSET {$start};";
     }
    }
    else{
     $start=0;
     $page=1;
     $countReys = $PDOconn->query("SELECT COUNT(*) FROM reys 
     WHERE is_available=true;")->fetch();
     $numberPages = (int)$countReys["count"]/50;
     if($countReys["count"]%50 != 0){
         $numberPages++;
     }
 
     if(isset($_GET["page"]) && $_GET["page"] <= $numberPages && $_GET["page"] >= 1){
         $page = $_GET["page"];
         $start = 50*($page-1);
         $query3 = "SELECT marshrut.marshrut_start, marshrut.marshrut_end, marshrut.marshrut_price, reys_id, reys.reys_transport, reys.reys_start, reys.reys_end, is_available
         FROM reys INNER JOIN marshrut ON reys.reys_marshrut=marshrut.marshrut_id 
         ORDER BY reys.reys_start LIMIT 50 OFFSET {$start};";
     }
 
     else{
         $query3 = "SELECT marshrut.marshrut_start, marshrut.marshrut_end, marshrut.marshrut_price, reys_id, reys.reys_transport, reys.reys_start, reys.reys_end, is_available
         FROM reys INNER JOIN marshrut ON reys.reys_marshrut=marshrut.marshrut_id 
         ORDER BY reys.reys_start LIMIT 50 OFFSET {$start};";
     }
    }
if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
?>

<button class="add"><a href="index.php?action=add_reys">???????????? ?????????? ????????</a></button>
<?php if(isset($_GET["success_add"])) {
    ?>
        <h1 style="color: green;">?????????? ???????? ???????? ?????????????? ????????????</h1>
    <?php
    }
    if(isset($_GET["success_delete"])){
    ?>
        <h1 style="color: red;">???????????????????? ?????? ???????? ???????? ?????????????? ????????????????</h1>
    <?php
    }
    if(isset($_GET["success_update"])){
        ?>
        <h1 style="color: orange;">???????? ?????? ???????? ???????? ?????????????? ????????????????</h1>
        <?php
    } ?>  
<?php } ?>
<p class="form-title">???????????? ???????? ????????????</p>
<table class="content-table">
    <thead>
        <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
        <th>?????????? ??????????</th>
        <?php } ?>
        <th>?????????????? ????????????????</th>
        <th>???????????? ????????????????</th>
        <th>?????????????? ??????????????</th>
        <th>?????????? ????????????????????, ???????? ?????????????? ????????</th>
        <th>?????? ????????????????????????</th>
        <th>?????? ????????????????</th>
        <?php if(!empty($_SESSION["user"])){ ?>
        <th>???? ??????????????????</th>
        <th>????????????????????, ???????? ???????????????? ???????? ??????????</th>
        <?php } ?>
    </thead>

    <tbody>
    <?php foreach($PDOconn->query($query3) as $object) {?>
            <tr>
                <?php if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <?= $object["reys_id"];?>
                </td>
                <?php } ?>
                <td>
                    <?= $object["marshrut_start"];?>
                </td>
                <td>
                    <?= $object["marshrut_end"];?>
                </td>
                <td>
                    <?php echo $object["marshrut_price"]." ??????" ?>
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
                <?php if(!empty($_SESSION["user"])){ ?>
                <td>
                    <?php 
                        if($object["is_available"]){
                            ?>
                                <h2 style="color: green;">????????????????</h2>
                            <?php
                        }
                        else{
                            ?>
                                <h2 style="color: red;">??????????????????</h2>
                            <?php
                        }
                    ?>
                </td>
                <td>
                    <?php echo $object["user_surname"]." ".$object["user_name"]; ?>
                </td>
                <?php }
                if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){ ?>
                <td>
                    <button class="edit"><a href=<?php echo "index.php?action=edit_reys&reys_id=".$object["reys_id"];?>>????????????????????</a></button>
                </td>

                <td>
                    <button class="delete"><a href=<?php echo "index.php?action=delete_reys&reys_id=".$object["reys_id"] ?> onclick="return confirm('???? ?????????????? ???????????? ???????????????????? ?????? ?????????')">????????????????</a></button>
                </td>
                <?php } ?>
            </tr>
            <?php

            }?>
    </tbody>
</table>

<br/>
<br/>
<h1>???????????????? ??? <?php echo $page; ?></h1>
<?php for($i=1; $i<=$numberPages; $i++){
    ?>
        <button class="paginate"><a href=<?php echo "index.php?action=all_reys&page=".$i;?>><?php echo $i; ?></a></button>
    <?php
} ?>

<br/>
<br/>
<button class="button"><a href="index.php?action=find_information">?????????????????????? ???? ????????</a></button>