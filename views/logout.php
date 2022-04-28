<?php
unset($_SESSION["user"]);
header("Location: index.php?action=main");
?>