<?php
ob_start();
session_start();
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/*Include the default function file*/
require_once("includes/functions.php");
/*This function will check the session*/
checkSession();
#echo "<pre>";print_r($_GET);die;
$year			=	$_GET['yr'];
$semester		=	$_GET['sem'];
$deptId			=	$_GET['deptId'];
$subjectId		=	$_GET['subId'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Current Free Answer Report</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../student/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--link rel="shortcut icon" href="/mail/images/favicon.ico" type="image/x-icon"-->
	<!--Link to AJAX source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/ajax.js'></script>
	<!-- Spry Stuff Starts here-->
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
	<style>
	h1,h2,h3,h4,h5,h6
	{
		color		:	#000;
		text-align	:	center;
	}
	h2
	{
		margin		:	30px;	
	}
	h3
	{
		color		:	#E76800;
	}
	</style>
</head>

<body>
<?php
	
	echo plotLogoWithAddress();
?>
<?php
	$currentYear	=	date('Y');
	
	/*Find the dept code From dept id*/
	$depNameQry		=	"SELECT `department_code`  FROM `tbl_department` WHERE `department_id` = {$deptId}";
	$depNameRes		=	mysql_query($depNameQry)or die(mysql_error());
	$depNameNumRows	=	mysql_num_rows($depNameRes);
	if($depNameNumRows)
	{
		$depNameArr	=	mysql_fetch_assoc($depNameRes);
		$deptCode	=	$depNameArr['department_code'];
	}
	/*Find the dept code From dept id*/
		
	/*Find the Subject Name From Subject ID*/
	$subNameQry		=	"SELECT `subject_name`  FROM `tbl_subject` WHERE `subject_id` = {$subjectId}";
	$subNameRes		=	mysql_query($subNameQry)or die(mysql_error());
	$subNameNumRows	=	mysql_num_rows($subNameRes);
	if($subNameNumRows)
	{
		$subNameArr	=	mysql_fetch_assoc($subNameRes);
		$subjName	=	$subNameArr['subject_name'];
	}
	/*Find the Subject Name From Subject ID*/
		
	/*List the Free Answer Starts here*/
	$freeAnswerQry		=	"SELECT comment_value 
							FROM  `tbl_comments` 
							WHERE  `academic_year` LIKE  '{$currentYear}'
							AND  `year` LIKE  '{$year}'
							AND  `semester` LIKE  '{$semester}'
							AND  `department` LIKE  '{$deptCode}'
							AND  `subject` LIKE  '{$subjName}'
							AND `comment_value`<>'' ";
	
	$freeAnswerRes		=	mysql_query($freeAnswerQry)or die(mysql_error());
	$freeAnswerNumRows	=	mysql_num_rows($freeAnswerRes);
	if($freeAnswerNumRows)
	{
		/*Print the Headind*/
			$headerStr	 =	"Acadamic Year-{$currentYear} {$year}-Year  
							{$semester}-Semester {$deptCode}-Department <br/>
							'{$subjName}' - Student's Comments";
			echo "<h3>{$headerStr}</h3>";
		/*Print the Headind*/		
		$commentDiv				=	"<div id='commentWrapper'>";
		while($freeAnswerArr	=	mysql_fetch_assoc($freeAnswerRes))
		{
			
			
			
			$freeAnswerValue	=   $freeAnswerArr['comment_value'];	
			$commentDiv			.=	"<div id='comment' class='shadowEffect'>{$freeAnswerValue}</div>";
		}
		$commentDiv			.= '</div>';
		echo $commentDiv;	
	}	
	else
	{
		
		echo "<p align='center'>";
		echo "<span class='error'>Comments Not Found!</span>"	;
		echo "</p>";
	}
	/*List the Free Answer Ends here*/
	
	#Print the document
			echo '<script>';
			echo 'window.print();';
			echo '</script>';
	#Print the document
?>
<?php
	ob_end_flush();
?>