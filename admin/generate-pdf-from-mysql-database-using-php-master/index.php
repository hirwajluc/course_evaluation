<?php
//Starting by Blaise
include '../includes/functions.php';
include '../includes/config.php';
include '../includes/dbConnectPDO.php';
global $connPDO;
$dept = $_GET['dep'];
$year = $_GET['level'];
$group = $_GET['group'];
$groupArray = array(1 => "A", 2 => "B", 3 => "C", 4 => "D");

$listLevelQuery = "SELECT * FROM `tbl_levels` WHERE level_id = '{$year}'";
$listLevelRes = $connPDO->query($listLevelQuery);
$listLevelArr = $listLevelRes->fetch(PDO::FETCH_ASSOC);
$level = $listLevelArr['level_year'];

$listDeptQuery = "SELECT * FROM `tbl_department` WHERE department_id = '{$dept}'";
$listDeptRes = $connPDO->query($listDeptQuery);
$listDeptArr = $listDeptRes->fetch(PDO::FETCH_ASSOC);
$dcode = $listDeptArr['department_code'];

$fname = strtolower($dcode).$level.strtolower($groupArray[$group]).".pdf";
$head =$dcode." - ".$level." - ".$groupArray[$group];
include('pdf.php');
?>