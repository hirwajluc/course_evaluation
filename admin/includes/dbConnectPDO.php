<?php
$dbname="course_evaluation";
$username="root";
$password="";
$host="localhost";
try{
    $connPDO = new PDO("mysql: host=$host;dbname=$dbname",$username,$password);
    $connPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo $e->getMessage().$e->getCode();
    echo "Error occured!";
}
?>
