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
$academicYear		=	$_GET['aca_yr'];
$year				=	$_GET['yr'];
$semester			=	$_GET['sem'];
$deptCode			=	$_GET['deptId'];
$subjName			=	$_GET['subId'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>History Free Answer</title>
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
		/*List the Free Answer Starts here*/
		$freeAnswerQry		=	"SELECT comment_value 
								FROM  `tbl_comments` 
								WHERE  `academic_year` LIKE  '{$academicYear}'
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
				$headerStr	 =	"Acadamic Year-{$academicYear} {$year}-Year  
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