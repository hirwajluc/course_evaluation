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
//echo '<pre>';print_r($_GET);die;
if(isset($_GET['ac_year']) && isset($_GET['year']) && isset($_GET['sem']) && isset($_GET['deptCode']))
{
	$academicYear		=	$_GET['ac_year'];
	$year				=	$_GET['year'];
	$semester			=	$_GET['sem'];
	$deptCode			=	$_GET['deptCode'];
	$subjectId			=	$_GET['subId'];
}
$subjectId  = (isset($subjectId))?$subjectId:NULL;	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>History wise current Report</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../student/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="icon" href="images/favi_logo.gif"/>
	<!-- Spry Stuff Ends Here-->
	<style>
	h1,h2,h3,h4,h5,h6
	{
		color		:	#000;
		text-align	:	center;
	}
	h3
	{
		color		:	#E76800;
	}
	table
	{
		page-break-after:always
	}
	</style>
</head>
<body>
<?php		
		if($subjectId)
		{
			$classWiseHistorySelQry 	=	"SELECT `subject_name`,`academic_year`,`dept_code`,`year`,`semester`,`quality`
											FROM `classwise_history` WHERE 
											`academic_year` = '{$academicYear}' AND
											`subject_name` = '{$subjectId}' AND
											`year` = '{$year}' AND
											`semester` = '{$semester}' AND 
											`dept_code` = '{$deptCode}'
											GROUP BY `subject_name`,`academic_year`,`dept_code`,
											`year`,`semester`,`quality`";
		}
		else
		{
			$classWiseHistorySelQry 	=	"SELECT `subject_name`,`academic_year`,`dept_code`,`year`,`semester`,`quality`
											FROM `classwise_history` WHERE 
											`academic_year` = '{$academicYear}' AND
											`year` = '{$year}' AND
											`semester` = '{$semester}' AND 
											`dept_code` = '{$deptCode}'
											GROUP BY `subject_name`,`academic_year`,`dept_code`,
											`year`,`semester`,`quality`";
		}									
		$classWiseHistorySelRes		=	mysql_query($classWiseHistorySelQry)or die(mysql_error());								
		$classWiseHistorySelRows	=	mysql_num_rows($classWiseHistorySelRes);
		
		if($classWiseHistorySelRows)
		{
			/*Print the Headind*/
				$headerStr	 =	"Acadamic Year-{$academicYear} {$year}-Year 
								{$semester}-Semester {$deptCode}-Department <br/>
								{$subjectId}  Report";
				echo "<h2>{$headerStr}</h2>";
			/*Print the Headind*/
			
			
			$tempSubject = '';
			$i=1;
			while($classWiseHistorySelAssoc	=	mysql_fetch_assoc($classWiseHistorySelRes))
			{
				if($tempSubject !== $classWiseHistorySelAssoc['subject_name'])
				{
					echo "<h2>{$classWiseHistorySelAssoc['subject_name']}</h2>";
				}
				
				$tempSubject	=	$classWiseHistorySelAssoc['subject_name'];
				echo "<h3>{$classWiseHistorySelAssoc['quality']}</h3>";
				
				//echo '<pre>';print_r($classWiseHistorySelAssoc);			
				$subjectName 		=	$classWiseHistorySelAssoc['subject_name'];	 
				$academicYear	 	=	$classWiseHistorySelAssoc['academic_year'];	 
				$deptCode		 	=	$classWiseHistorySelAssoc['dept_code'];	 
				$year 				=	$classWiseHistorySelAssoc['year'];	 
				$semester 			=	$classWiseHistorySelAssoc['semester'];	 
				$quality			=	$classWiseHistorySelAssoc['quality'];	 
				$answerAmountQry 	=   "SELECT `answer`,`amount` FROM classwise_history 
										WHERE `subject_name`='{$subjectName}' AND`academic_year`='{$academicYear}' AND`dept_code`='{$deptCode}'
										AND `year`='{$year}' AND `semester`='{$semester}' AND `quality`='{$quality}'";
										
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
					echo "<div class= 'printChartContainer'><!--Chart Conatainer Starts Here-->";
						/*Div that will hold the pie chart starts Here*/
						echo "<!--Div that will hold the  chart starts Here-->";
						echo "<div id='chart_div{$barChartDiv}' style='width:400; height:300'></div>";
						echo '<br/>';
						echo "<div id='chart_div{$i}' style='width:400; height:300'></div>";
						echo "<!--Div that will hold the chart ends Here-->";
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
			#Print the document
			echo '<script>';
			echo 'window.print();';
			echo '</script>';
			#Print the document
		}
		else
		{
			echo "<p align='center'>";
			echo "<span class='error'>Feedback Not Exist!</span>"	;
			echo "</p>";
		}
?>
</body>
</html>
<?php
	ob_end_flush();
?>