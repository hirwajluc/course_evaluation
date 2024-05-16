<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

/* Set Year Values -> By default we have first,second years */
//$yearArr = array(1, 2);
$yearArr = array(1, 2, 3);
$leveYyearArr = array(1 => 'L6 Year 1', 2 => 'L6 Year 2', 3 => 'L7 Year 3');
/* Set Semester Values -> By default we have First,Second */
$semesterArr = array(1, 2);
/* Set Group Values -> By default we have A,B,C, D */
$groupArr = array(1, 2, 3, 4);
/* Set Group Values -> By default we have A,B,C */
$groupArrValue = array("A", "B", "C", "D");
/* Set Semester Values -> By default we have First,Second */

$typeArr = array("Learning environment preparation", "Module/Course content organization", "Students contribution", "Teaching and learning", "Assessment", "Quality of Delivery", "Student Overall experience", "Teacher ratings");

/* Set Sponsor Values -> By default we have First,Second */
$spoArr = array("Government", "Private");
/* Set Gender Values -> By default we have First,Second */
$genderArr = array("Male", "Female");
/* Set Gender Values -> By default we have First,Second */
$userTpyeArr = array("admin", "coladmin", "quality");
$colUserTpyeArr = array("College Admin" => "coladmin", "Quality Assurence" => "quality");
/* Set Desability Values -> By default we have First,Second */
$desabArr = array("Yes", "No");
/* Set the default timezone as Africa/Kigali */
date_default_timezone_set('Africa/Kigali');
#echo 'Success';
?>
