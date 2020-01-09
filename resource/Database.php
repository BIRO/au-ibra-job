<?php
// LOCAL !!!!!!!
$username = 'root';
$dsn = 'mysql:host=localhost; dbname=register';
$password = '';// LOCAL !!!!!!!

// REMOTE!!!
// $username = 'tBAVdT15DA';
// $dsn = 'mysql:host=remotemysql.com:3306; dbname=tBAVdT15DA';
// $password = 'P6sSFzPxIX';

try{

    $db = new PDO($dsn, $username, $password);


    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $ex){

    echo "Connection failed ".$ex->getMessage();
}
