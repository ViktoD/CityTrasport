<h1>Виберіть, що Вас цікавить</h1>


  <button class="button"><a href="index.php?action=all_reys">Рейси</a></button>
  <br/>
  <button class="button"><a href="index.php?action=all_marshruts">Маршрути</a></button>
  <br/>
  <!-- Для зареєстрованих користувачів -->
  <?php if(!empty($_SESSION["user"])){ ?>
    <button class="button"><a href="index.php?action=all_vodiys">Водії</a></button>
    <br/>
    <button class="button"><a href="index.php?action=all_transports">Транспорти</a></button>
    <br/>
    <button class="button"><a href="index.php?action=all_pereviznyks">Перевізники</a></button>
    <br/>
  <?php } 
   if(!empty($_SESSION["user"]) && $_SESSION["user"]["user_is_admin"]==true){
   ?>
    <button class="button"><a href="index.php?action=all_tz_ohlyads">Техогляди транспортних засобів</a></button>
    <br/>
    <button class="button"><a href="index.php?action=all_medohlyads">Медичний огляд водіїв</a></button>
    <br/>
    <button class="button"><a href="index.php?action=all_companies">Страхові компанії</a></button>
    <br/>
    <button class="button"><a href="index.php?action=all_tz_polis">Страхові поліси для транспортів</a></button>
    <br/>
    <button class="button"><a href="index.php?action=all_vodiy_polis">Страхові поліси для водіїв</a></button>
    <br/>
  <?php } ?>

