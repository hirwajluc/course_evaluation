<?php
ob_start();
session_start();
/* Include the database configuration file */
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;
global $semesterArr;
/* This function will check the session */
checkSession();
if (isset($_GET['msg']) && ($_GET['msg'] == 'deleted')) {
    $successMsg = "Subject Information Successfully Deleted!";
}
if (isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted')) {
    $errorMsg = "Error! Unable to Delete Subject Information!";
}
/* To display the dependency message - Delete */
if (isset($_GET['depend'])) {
    $dependMsg = trim($_GET['depend']);
    $errorMsg = "Error! Dependency exists in {$dependMsg}!";
}

//2016/11/16
$acc_year = "";
$acc_sem = "";
if (isset($_POST['go'])) {
	$acc_year = $_POST['s_year'];
	$acc_sem = $_POST['semester'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Reports</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!--Link to the template css file-->
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <!--Link to Favicon -->
        <link rel="icon" href="images/favi_logo.gif"/>

		<script type="text/javascript">
			function onSbmtClick(){
			    window.open("current_class_wise_pdf.php?accyear=<?php print $acc_year ?>", "_newtab");
				return true;
			}

		</script>
    </head>
    <body>
        <div class="main">
            <?php
//To Plot Menus in this Page
            echo plotHeaderMenuInfo("current_class_wise.php");
            //2016/11/11
            print "<form name=\"frm\" action=\"".$_SERVER['PHP_SELF']."\"  method=\"POST\" >";
            ?>
            <div class="body">
                <div class="main_body">
                    <h2>Evaluation Reports
					
					<select title="Select year" name="s_year" required>
						<option disabled selected>Select Year</option>
							<?php
								for($i=date("Y"); $i >= 2020; $i--){
									//echo "<option>".$i."</option>";
									if ($acc_year == $i){
                                        ?>
                                        <option selected value="<?php echo $i.'-'.($i+1);?>"><?php echo $i.'-'.($i+1);?></option>
                                        <?php
									}else{
										?>
                                        <option value="<?php echo $i.'-'.($i+1);?>"><?php echo $i.'-'.($i+1);?></option>
                                        <?php
									}
								}
							?>
					</select>
                    <select title="Semester" name="semester" required>
						<option disabled selected>Select Semester</option>
                        <?php foreach ($semesterArr as $sm):
                            if ($sm == $acc_sem):?>
                                <option value="<?=$sm;?>" selected>Sem <?=$sm;?></option>
                            <?php else:?>
                                <option value="<?=$sm;?>">Sem <?=$sm;?></option>
                            <?php endif;
                        endforeach;    
                        ?>
					</select>
                    <input type="hidden" name="selectYear" value="<?php echo $_POST['s_year']; ?>">					
                    <input type="hidden" name="selectSem" value="<?php echo $_POST['semester']; ?>">					
					
						<button type="submit" name="go">Go</button>
						</form>
                <?php
				
					$go= $_POST['go'];
					if ($acc_year){
						print "<button name=\"pdf_print\" onClick=\"onSbmtClick();\" >Generate PDF doc to Print</button>";
					//}
					print "</h2>";
					

                    /* Display the Messages */
                    if (isset($errorMsg)) {
                        echo "<p><span class = 'error'>{$errorMsg}</span></p>";
                    } elseif (isset($successMsg)) {
                        echo "<p><span class = 'success'>{$successMsg}</span></p>";
                    }
                    ?>
                    <table id='listEntries' width="550" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse;">
                        <?php
                        $keyword = trim($_POST['s_year']);
                        $sem = trim($_POST['semester']);
						
                        $searchQry = "SELECT *
								FROM tbl_subject
								INNER JOIN tbl_department ON tbl_subject.`subject_department_id` = tbl_department.department_id
								INNER JOIN tbl_teacher ON tbl_subject.`subject_teacher_id` = tbl_teacher.`teacher_id` WHERE
								tbl_subject.`subject_ac_year` = '{$keyword}' AND tbl_subject.`subject_semester` = '{$sem}'							
								ORDER BY subject_name asc";
								//tbl_subject.`subject_semester`= 2
						//print "searchQry = ".$searchQry."<br>";
                        $searchRes = $connPDO->query($searchQry);
                        $searchNumRows = $searchRes->rowCount();
                        if (!$searchNumRows) {
                            echo '<tr>
                <td height="30" colspan="3" align="center"><strong style="color:red;">Search Not Found</strong></td>
              </tr>';
                        } else {
                        ?>
                            <tr>
                      <!--<th height="30" align="center"><strong>Sub Code</strong></td>-->
                                <th height="30" align="center"><strong>Sub Name</strong></td>
                                    <th height="30" align="center"><strong>Dept Code</strong></td>
                                        <th height="30" align="center"><strong>Year</strong></td>
                                            <th height="30" align="center"><strong>Semester</strong></td>
                                                <th height="30" align="center"><strong>Teacher Name</strong></td>
                                                    <th align="center"><strong>Evaluations</strong></td>
                                                        </tr>

                                                    <?php
													//$year=	date("Y");
                                                    //$acc_year = $year; // ?????????muganga
                                                    while ($searchArr = $searchRes->fetch(PDO::FETCH_ASSOC)) {
                                                        //echo "<pre>";print_r($searchArr);die;
                                                    ?>
                                                        <tr>
                                                                <!--<td height="30">&nbsp;
                                                        <?php
                                                        echo $searchArr["subject_code"];
                                                        ?>
						</td>-->
                                                        <td height="30">&nbsp;
                                                            <?php
                                                            echo $searchArr["subject_name"];
                                                            ?>
                                                        </td>
                                                        <td height="30">&nbsp;
                                                            <?php
                                                            echo $searchArr["department_code"];
                                                            ?>
                                                        </td>
                                                        <td height="30">&nbsp;
                                                            <?php
															/*Updated by Nepo San
															Start
															*/
															if($searchArr["subject_group"]==1)
																{																
																$searchArr["subject_group"]='A';
																}else
																if($searchArr["subject_group"]==2)
																{
																$searchArr["subject_group"]='B';
																}else
																if($searchArr["subject_group"]==3)
																{
																$searchArr["subject_group"]='C';
																}else
																	if($searchArr["subject_group"]==4)
																	{
																	$searchArr["subject_group"]='D';	
																	}
                                                            echo $searchArr["subject_year"]."".$searchArr["subject_group"];
															/*Updated by Nepo San
															End
															*/
															//echo $searchArr["subject_year"]; by Dieudonne San
                                                            ?>
                                                        </td>
                                                        <td height="30">&nbsp;
                                                            <?php
                                                            echo "Semester ".$searchArr["subject_semester"];
                                                            ?>
                                                        </td>
                                                        <td height="30">&nbsp;
                                                            <?php
                                                            echo $searchArr["teacher_first_name"]."\n".$searchArr["teacher_last_name"];
                                                            ?>
                                                        </td>


                                                        <td align="center">
                                                            <?php
                                                            $link = hasEvaluationData($searchArr["subject_id"], $searchArr["subject_ac_year"]);

                                                            if ($link) {
                                                            ?>
                                                                <a href="feedback.php?id=<?php echo $searchArr["subject_id"]; ?>&acc_year=<?php echo $acc_year; ?>" target="__blank">View<?php //echo $acc_year; ?></a>
                                                             <!--   <a href="#?id=<?php echo $searchArr["subject_id"] ?>">
                                                                <?php echo $acc_year - 1; ?>
                                                            </a> | 
                                                            <a href="#?id=<?php echo $searchArr["subject_id"] ?>">
                                                                <?php echo $acc_year - 2; ?>
                                                            </a> -->
                                                            <?php
                                                            } else {
                                                            ?>
                                                                No evaluation
                                                            <?php
                                                            }
													}
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    </table>
                                                    <p>&nbsp;</p>

                                                    </div>
                                                    <?php
                                                    /* This function will return the logo div string to the sidebody */
                                                    echo plotLogoDiv();
                                                   // echo plotSearchDiv('current_class_wise.php');
                                                    ?><!-- End of Search Div-->
                                                    </div>
                                                    <div class="clr"></div>
                                                    <br/><br/>
                                                    </div><!-- End of Body div-->
                                                    </div><!--End of Main Div-->
                                                    <?php
                                                    /* This function will return the footer div information */
                                                    echo plotFooterDiv();
                                                    ?>
                                                    </body>
                                                    </html>
                                                    <?php
                                                    ob_end_flush();
                                                    ?>