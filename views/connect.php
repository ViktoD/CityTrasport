<?php 

    try{

        $PDOconn = new PDO("pgsql:host=localhost;port=5432;dbname=CityTransport;","postgres","83159607");
    }catch(Exception $e){
        echo $e;
    }

?>