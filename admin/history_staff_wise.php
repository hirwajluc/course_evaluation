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
	if(isset($_POST['submit']))
	{
		//echo "<pre>";print_r($_POST);die;
		$academicYear		=	$_POST['academic_year'];
		$deptCode			=	$_POST['dept_code'];
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
	<!-- Spry Stuff Starts here-->
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
</head>
<body>
	<div style = 'float:left'>
		<p class='welcome' align='right'>Welcome 
			<strong><?php echo $_SESSION['u_fname']." ".$_SESSION['u_lname']?></strong><br/>
			<a href='change_password.php'>Change Password</a> | <a href='logout.php'>Logout</a>
		</p>
	</div>
	<?php
		echo plotLogoWithAddress();
	?>
	<div>
		<table id='listEntries' width="50%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" bordercolor="#CCCCCC">
			<tr bgcolor="#FFFFCC">
				<th colspan='2' align='center'>Current</th>
				<th colspan='2' align='center'>History</th>
			</tr>
			<tr>	
				<td align="center"><a href="current_class_wise.php">Class Wise</a></td>
				<td align="center"><a href="current_staff_wise.php">Staff Wise</a></td>
				<td align="center"><a href="history_class_wise.php">Class Wise</a></td>
				<td align="center"><a  href="history_staff_wise.php" class='activelink'>Staff Wise</a></td>
			</tr>
		</table>
	</div><!--Report Menu Ends Here-->
<br/>
<form method = 'post' action="<?php echo $_SERVER['PHP_SELF'];?>">
	<table id = "listEntries" width = "100%" border="1">
	<tr>
			<td>
				<strong>Academic Year</strong>
				<span class = 'mandatory'>*</span>
			</td>
			<td height = '30'>
					<?php
						$academicYear	=	isset($academicYear)?$academicYear:'';
						echo plotAcademicYear(2009,2030,$academicYear);
					?>
			</td>
			<td>
				<strong>Department</strong>
				<span class = 'mandatory'>*</span>
			</td>
			<td height = '30'>
					<?php
						$deptCode	=	isset($deptCode)?$deptCode:'';
						echo plotDropdownFromHistoryTable("SELECT `dept_code` FROM `classwise_history` GROUP BY `dept_code`" , 'dept_code',$deptCode);
					?>
			</td>
			<td height = '30'>
						<input type = 'submit' name = 'submit' class = 'button' value = 'Search Feedback' />
			</td>
	</tr>
	</table>
</form>
<?php
	if(isset($_POST['submit']))
	{
		//echo "<pre>";print_r($_POST);die;
		$academicYear		=	$_POST['academic_year'];
		$deptCode			=	$_POST['dept_code'];
		
		
		$staffWiseHistorySelQry 	=	"SELECT `staff_name`,`academic_year`,`dept_code`,`quality`
										FROM `staffwise_history` WHERE 
										`academic_year` = '{$academicYear}' AND
										`dept_code` = '{$deptCode}'
										 GROUP BY `staff_name`,`academic_year`,`dept_code`,`quality`";
									
		$staffWiseHistorySelRes		=	mysql_query($staffWiseHistorySelQry)or die(mysql_error());								
		$staffWiseHistorySelRows	=	mysql_num_rows($staffWiseHistorySelRes);
		
		if($staffWiseHistorySelRows)
		{
			/*PDF LINK*/
				echo "<div style = 'float:right;padding:10px;'>
					<a href='print_history_staffwise.php?acaYear=$academicYear&deptCode=$deptCode'
					target = '_blank'>Print As PDF</a>
				</div>";
			/*PDF LINK*/	
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
?>
<script type="text/javascript">
<!--
	var academic_year    	 = new Spry.Widget.ValidationSelect("academic_year", {validateOn:["change"]});						
	var dept_code    		 = new Spry.Widget.ValidationSelect("dept_code", {validateOn:["change"]});	
		
-->	
</script>	
</body>
</html>
<?php
	ob_end_flush();
?>
