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
if(isset($_GET['acaYear']) && isset($_GET['deptCode']))
{
	//echo "<pre>";print_r($_POST);die;
	$academicYear		=	$_GET['acaYear'];
	$deptCode			=	$_GET['deptCode'];
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>History wise current Report</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../student/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--link rel="shortcut icon" href="/mail/images/favicon.ico" type="image/x-icon"-->
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
	if(isset($_GET['acaYear']) && isset($_GET['deptCode']))
	{
		//echo "<pre>";print_r($_POST);die;
		$academicYear		=	$_GET['acaYear'];
		$deptCode			=	$_GET['deptCode'];

		$staffWiseHistorySelQry 	=	"SELECT `staff_name`,`academic_year`,`dept_code`,`quality`
										FROM `staffwise_history` WHERE 
										`academic_year` = '{$academicYear}' AND
										`dept_code` = '{$deptCode}'
										 GROUP BY `staff_name`,`academic_year`,`dept_code`,`quality`";
									
		$staffWiseHistorySelRes		=	mysql_query($staffWiseHistorySelQry)or die(mysql_error());								
		$staffWiseHistorySelRows	=	mysql_num_rows($staffWiseHistorySelRes);
		
		if($staffWiseHistorySelRows)
		{
			/*Print the Headind*/
			$headerStr	 =	"Acadamic Year-{$academicYear},{$deptCode}-Department Report";
			echo "<h2>{$headerStr}</h2>";
			/*Print the Headind*/
			$tempSubject = '';
			$i=1;
			while($staffWiseHistorySelAssoc	=	mysql_fetch_assoc($staffWiseHistorySelRes))
			{
				if($tempSubject !== $staffWiseHistorySelAssoc['staff_name'])
				{
					echo "<h2>{$staffWiseHistorySelAssoc['staff_name']}</h2>";
				}
				
				$tempSubject	=	$staffWiseHistorySelAssoc['staff_name'];
				
				echo "<h3>{$staffWiseHistorySelAssoc['quality']}</h3>";
				//echo '<pre>';print_r($staffWiseHistorySelAssoc);			
				$staffName 			=	$staffWiseHistorySelAssoc['staff_name'];	 
				$academicYear	 	=	$staffWiseHistorySelAssoc['academic_year'];	 
				$deptCode		 	=	$staffWiseHistorySelAssoc['dept_code'];	 
				$quality			=	$staffWiseHistorySelAssoc['quality'];	 
				
				$answerAmountQry 	=   "SELECT `answer`,`amount` FROM staffwise_history WHERE 
										`staff_name`='{$staffName}' AND
										`academic_year`='{$academicYear}' AND
										`dept_code`='{$deptCode}' AND
										 `quality`='{$quality}'";
										
				$answerAmountQryRes			=	mysql_query($answerAmountQry)or die(mysql_error());								
				$answerAmountQryResRows		=	mysql_num_rows($answerAmountQryRes);
				if($answerAmountQryResRows)
				{
					while($answerAmountAssoc	=	mysql_fetch_assoc($answerAmountQryRes))
					{
						$arrForChart[$answerAmountAssoc['answer']] = $answerAmountAssoc['amount'];
					}
					//echo "<pre>";print_r($arrForChart);
					/*Chart Functionality starts here*/
					$titleArr				=	 array('title'=>'FeedBack','width'=>400,'height'=>300);
					$jsonTitleForMap		=	 json_encode($titleArr);
					$jsonContentForMap  	= 	 setJsonStrForChart($arrForChart);
					$barChartDiv			=	 $i*100;
					plotChart($jsonTitleForMap,$jsonContentForMap,'BarChart',$barChartDiv);//Plot Bar Chart
					plotChart($jsonTitleForMap,$jsonContentForMap,'PieChart',$divId = $i);//Plot Pie Chart
					/*Main Chart Container Div starts here*/
					echo "<div class= 'chartContainer'><!--Chart Conatainer Starts Here-->";
						/*Div that will hold the pie chart starts Here*/
						echo "<!--Div that will hold the  chart starts Here-->";
						echo "<div id='chart_div{$barChartDiv}' style='width:400; height:300'></div>";
						echo '<br/>';
						echo "<div id='chart_div{$i}' style='width:400; height:300'></div>";
						echo "<!--Div that will hold the chart ends Here-->";
						echo '<br/>';
						/*Div that will hold the pie chart ends Here*/
						/*Display Table results starts here*/
						echo "<table id='listEntries' width='20%'  border='1'>
							 <!--Display Table results starts here -->";
						foreach($arrForChart as $answer => $value)
						{	
							echo "<tr>";
							echo "<td>{$answer}</td>";
							echo "<td>{$value}</td>";
							echo "</tr>";
						}
						echo "</table><!--Display Table results starts here-->";
						/*Display Table results starts here*/
					echo "</div><!--Chart Conatainer Ends Here-->";
					/*Main Chart Container Div ends here*/
				}
				$i++;
			}	
		}
		else
		{
			echo "<p align='center'>";
			echo "<span class='error'>Feedback Not Exist!</span>"	;
			echo "</p>";
		}
	}
	else
	{
		echo "<p align='center'>";
		echo "<span class='error'>Search Not Found!</span>"	;
		echo "</p>";
	}
?>