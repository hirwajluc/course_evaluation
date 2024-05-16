<?php 
ob_start();
session_start();
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;

if (isset($_POST['sem'])) {
	$sem = $_POST['sem'];
  $yr = strval($_POST['yr']);
	?>
	<center>
      <table class="table table-striped">
        <tr>
          <th>Class</th>
          <th>N<sup>o</sup> Of Evaluated Students</th>
        </tr>
        <?php
          $sum = 0;
          $listDeptQuery = "SELECT * FROM `tbl_department` ORDER BY department_id";
          $listDeptRes = $connPDO->query($listDeptQuery);
          $listDeptNumRows = $listDeptRes->rowCount();
          // $dept = array('Renewable Energy (REn)', 'Electronics and Telecommunication Technology (ETT)', 'Information Technology (IT)', 'Mechatronics Technology (MEC)');
          $year = array('1','2','3');
          while ($listDeptArr = $listDeptRes -> fetch(PDO::FETCH_ASSOC)) {
            for ($year=1; $year <= 3; $year++) { 
              $groupArr = array('A','B','C','D');
              for($num = 1; $num <= sizeof($groupArr); $num++) {
                $classe = $listDeptArr['department_name']." Year ".$year." ".$groupArr[$num-1];
                $sub_code = $listDeptArr['department_id'].$year.$sem.$num;
                $select = $connPDO->query("SELECT * FROM tbl_codes WHERE academic_year = '{$yr}' AND code LIKE '{$sub_code}%' AND used = '1'");
                $number = $select->rowCount();
                if ($number > 0) {
                  ?>
                  <tr>
                    <td><?php echo $classe;?></td>
                    <td><?php echo $number;?></td>
                  </tr>
                  <?php
                  $sum += $number;
                }else{

                }
              }
            }
          }
        ?>
      </table>
      <strong style="font-style: italic; font-size: 1.5em;">Total Evaluated students are: </strong>
      <strong style="font-style: algeria; font-weight: bold; font-size: 1.6em; color: #080808;"><?php echo $sum;?></strong>
    </center>
				

<?php
}
?>