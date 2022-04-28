<?php 

    try{

        $PDOconn = new PDO("pgsql:host=ec2-63-35-156-160.eu-west-1.compute.amazonaws.com;port=5432;dbname=d6mtmp12tef8k5;","wymdkhigtzozej","151e0be2a4d9bebc89c55517142bc9003db6a5e48ec45c01cfef0a49485d4098");
    }catch(Exception $e){
        echo $e;
    }

?>
