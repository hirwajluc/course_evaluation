<?php

/* Include the database configuration file */
require_once("config.php");

/* This function will return the header string with menu information */

function plotHeaderMenuInfo($activemenuName) {
    $menuNamesArr = array(
        "Departments" => "department_new.php",
        "Teachers" => "teachers_new.php", "Subjects" => "subjects_new.php",
        "Qualities" => "qualities_new.php",
        "Students" => "student_new.php",
        "Report" => "current_class_wise.php",
		 "Answers" => "answers_new.php"
    );
    echo "<div class='header'>
			<div class='header_text'>
			  <p align='right'>Welcome 
				<strong>{$_SESSION['u_utype']}</strong><br/>
				<a href='change_password.php'>Change Password</a> | <a href='logout.php'>Logout</a>
			  </p>
			</div>";
    echo '<div class="menu">';
    echo '<ul>';
    foreach ($menuNamesArr as $menuName => $menuUrl) {
        if ($menuUrl == $activemenuName) {
            echo "<li><a href = '{$menuUrl}' class='active'>{$menuName}</a></li>";
        } else {
            echo "<li><a href = '{$menuUrl}'>{$menuName}</a></li>";
        }
    }
    echo '</ul>';
    echo '</div><!--End of menu div-->';
    echo '</div><!--End of header div-->';
}

function plotHeaderStudentInfo($activemenuName) {
    $menuNamesArr = array(
        "Feedback" => "feedback.php",
        "MyInfo" => "#",
    );
    echo "<div class='header'>
			<div class='header_text'>
			  <p align='right'>Welcome
				<strong>{$_SESSION['u_utype']}</strong><br/>
				<a href='change_password.php'>Change Password</a> | <a href='logout.php'>Logout</a>
			  </p>
			</div>";
    echo '<div class="menu">';
    echo '<ul>';
    foreach ($menuNamesArr as $menuName => $menuUrl) {
        if ($menuUrl == $activemenuName) {
            echo "<li><a href = '{$menuUrl}' class='active'>{$menuName}</a></li>";
        } else {
            echo "<li><a href = '{$menuUrl}'>{$menuName}</a></li>";
        }
    }
    echo "</ul></div>";
}

/* This function will return the logo div string to the sidebody */

function plotLogoDiv($imgPath = "../admin/images/logo.png") {
    $logoImageDivStr = <<<ABC
	<div class="logo shadowEffect">
		<img src="{$imgPath}" alt="logo" /> 
	</div><!--end of Logo div-->
ABC;
    return $logoImageDivStr;
}

/* This function will check the record already there inthe table(beore inserting new rec) */

function recordAlreadyExist($selectQuery) {
    $selectResource = mysql_query($selectQuery);
    $selNumRows = mysql_num_rows($selectResource);
    if ($selNumRows > 0) {
        return true;
    } else {
        return false;
    }
}

/* General Function to Insert Record into the table */

function insertOrUpdateRecord($query, $redirectFileName, $id = '') {
    $resResource = mysql_query($query);
    $affectedRows = mysql_affected_rows();
    if ($affectedRows !== -1) {
        if ($id == '') {
            header("Location:{$redirectFileName}?msg=success");
            exit;
        } else {
            header("Location:{$redirectFileName}?id={$id}&msg=success");
            exit;
        }
    } else {
        return false;
    }
}

/* Latest List Function Starts Here */

function getInfoFromCode($code) {
    $dep_filter = array(1, 2, 3);
    //$ac_filter = array(1, 2); // By Muganga
    $ac_filter = array(1, 2, 3); // By Dieudonne
    $sem_filter = array(1, 2); // By Serge
    $gr_filter = array(1, 2, 3, 4); // By Serge
    $dep = $code{0};
    $year = $code{1};
    $sem = $code {2};
    $group= $code {3};
    // if (in_array($dep, $dep_filter) && in_array($year, $ac_filter) && in_array($sem, $ac_filter) && in_array($group, $ac_filter)) { // By Muganga
    if (in_array($dep, $dep_filter) && in_array($year, $ac_filter) && in_array($sem, $sem_filter) && in_array($group, $gr_filter)) { // By Serge
        return array("department" => $dep, "year" => $year, "semester" => $sem, "group" => $group);
    }
    return array();
}

/* This function is used to list the department names */

function listDepartment() {
    $listDeptQuery = "SELECT * FROM `tbl_department` ORDER BY department_id DESC LIMIT 0,3";
    $listDeptRes = mysql_query($listDeptQuery) OR die(mysql_error());
    $listDeptNumRows = mysql_num_rows($listDeptRes);
    $listDeptStr = "<div class='side_body shadowEffect'>
							 <h2>Latest Entries...</h2>";
    if ($listDeptNumRows) {
        while ($listDeptArr = mysql_fetch_assoc($listDeptRes)) {
            $listDeptStr .= <<<ABC
				<div class='title'>
					<a href = "departments_edit.php?id={$listDeptArr['department_id']}">
					{$listDeptArr['department_name']}
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>
ABC;
        }
    } else {
        $listDeptStr .= "<p><span class= 'error'>No Department(s)</span></p>";
    }
    $listDeptStr .= '</div><!--End Of side body Div-->';
    return $listDeptStr;
}

/* This function is used to list the Teachers */

function listTeacher() {

    $listTeacherQuery = "SELECT * FROM tbl_teacher INNER JOIN tbl_department
									ON tbl_teacher.teacher_department_id = tbl_department.department_id 
									ORDER BY tbl_teacher.teacher_id DESC LIMIT 0,3";
    $listTeacherRes = mysql_query($listTeacherQuery) OR die(mysql_error());
    $listTeacherNumRows = mysql_num_rows($listTeacherRes);

    $listTeacherStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listTeacherNumRows) {
        while ($listTeacherArr = mysql_fetch_assoc($listTeacherRes)) {

            $listTeacherStr .= <<<ABC
				<div class='title'>
					<a href = "teachers_edit.php?id={$listTeacherArr['teacher_id']}">
					{$listTeacherArr['teacher_first_name']} \t {$listTeacherArr['teacher_last_name']} 
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>
ABC;
        }
    } else {
        $listTeacherStr .= "<p><span class= 'error'>No Teacher(s)</span></p>";
    }
    $listTeacherStr .= '</div><!--End Of side body Div-->';
    return $listTeacherStr;
}

/* This function is used to list the Subjects */

function listSubjects() {

    $listSubjectQuery = "SELECT * FROM tbl_subject INNER JOIN tbl_department
								ON tbl_subject.subject_department_id = tbl_department.department_id
								ORDER BY subject_id  DESC LIMIT 0,3";
    $listSubjectRes = mysql_query($listSubjectQuery) OR die(mysql_error());
    $listSubjectNumRows = mysql_num_rows($listSubjectRes);

    $listSubjectStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listSubjectNumRows) {
        while ($listSubjectArr = mysql_fetch_assoc($listSubjectRes)) {

            $listSubjectStr .= <<<ABC
				<div class='title'>
					<a href = "subjects_edit.php?id={$listSubjectArr['subject_id']}">
						{$listSubjectArr['subject_name']}
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>
ABC;
        }
    } else {
        $listSubjectStr .= "<p><span class= 'error'>No Subject(s)</span></p>";
    }
    $listSubjectStr .= '</div><!--End Of side body Div-->';
    return $listSubjectStr;
}

function firstOnThePage() {

    return $_SESSION['hassavedsomething'] == "yes" ? false : true;
}

function listSubject($id) {

    $listSubjectQuery = "SELECT * FROM tbl_subject INNER JOIN tbl_department
								ON tbl_subject.subject_department_id = tbl_department.department_id
                                                             WHERE tbl_subject.subject_id = $id AND tbl_subject.subject_department_id = {$_SESSION['stud_dept']} AND tbl_subject.subject_year={$_SESSION['stud_year']} AND tbl_subject.subject_semester={$_SESSION['stud_sem']}";
    $listSubjectRes = mysql_query($listSubjectQuery) OR die(mysql_error());
    $listSubjectNumRows = mysql_num_rows($listSubjectRes);

    if ($listSubjectNumRows) {
        while ($listSubjectArr = mysql_fetch_assoc($listSubjectRes)) {
            return $listSubjectArr['subject_name'];
        }
    }
    return "";
}

function getSubjectDetails($id) {

    $getSubjectQuery = "SELECT s.subject_id, s.subject_name,s.subject_code, s.subject_year, s.subject_teacher_id, s.subject_semester, s.subject_group, d.department_name, t.teacher_first_name, t.teacher_last_name FROM tbl_subject s LEFT OUTER JOIN tbl_department d ON s.subject_department_id = d.department_id LEFT OUTER JOIN tbl_teacher t ON s.subject_teacher_id = t.teacher_id  WHERE s.subject_id = $id ";
    $getSubjectRes = mysql_query($getSubjectQuery) OR die(mysql_error());
    $nRows = mysql_num_rows($getSubjectRes);


    if ($nRows) {
        while ($subjectArr = mysql_fetch_assoc($getSubjectRes)) {
            $sDetail['subject_id'] = $subjectArr['subject_id'];
            $sDetail['subject_name'] = $subjectArr['subject_name'];
            $sDetail['subject_code'] = $subjectArr['subject_code'];
            $sDetail['subject_year'] = $subjectArr['subject_year'];
            $sDetail['subject_semester'] = $subjectArr['subject_semester'];
            $sDetail['subject_group'] = $subjectArr['subject_group'];
            $sDetail['subject_teacher_id'] = $subjectArr['subject_teacher_id'];
            $sDetail['department_name'] = $subjectArr['department_name'];
            $sDetail['teacher_first_name'] = $subjectArr['teacher_first_name'];
            $sDetail['teacher_last_name'] = $subjectArr['teacher_last_name'];

            return $sDetail;
        }
    }
    return array();
}

function alreadyEvaluated($sid) {
    $listSubjectQuery = "SELECT feedback_id FROM tbl_feedback WHERE  feedback_stud_id = {$_SESSION['u_id']} AND feedback_sub_id = {$sid} AND feedback_dept_id = {$_SESSION['stud_dept']} AND feedback_year ={$_SESSION['stud_year']} AND feedback_semester={$_SESSION['stud_sem']}";

    $listSubjectRes = mysql_query($listSubjectQuery) OR die(mysql_error());
    $listSubjectNumRows = mysql_num_rows($listSubjectRes);

    if ($listSubjectNumRows > 0)
        return true;
    return false;
}

function listStudentSubjects($code) {
    $info = getInfoFromCode($code);
    $department = $info['department'];
    $year = $info['year'];
    $semester = $info['semester'];
    $group = $info['group'];

    $listSubjectQuery = "SELECT * FROM (SELECT s.subject_id, s.subject_name, s.subject_group, t.teacher_first_name, t.teacher_last_name FROM tbl_subject s INNER JOIN tbl_department d
								ON s.subject_department_id = d.department_id LEFT OUTER JOIN tbl_teacher t ON s.subject_teacher_id = t.teacher_id 
						WHERE s.subject_department_id = {$department} AND s.subject_year={$year} AND s.subject_semester = {$semester}
                                                AND s.subject_group & {$group} > 0) AS s WHERE s.subject_group = {$group}
                                                                ORDER BY s.subject_id  DESC";

    $listSubjectRes = mysql_query($listSubjectQuery) OR die(mysql_error());
    $listSubjectNumRows = mysql_num_rows($listSubjectRes);

    $listSubjectStr = "";
    if ($listSubjectNumRows) {
        while ($listSubjectArr = mysql_fetch_assoc($listSubjectRes)) {
            if (alreadyEvaluated($listSubjectArr['subject_id']))
                continue;
            $_SESSION['has_made_fb'] = 'OK';
            $listSubjectStr .= <<<ABC
				<div class='title'>
					<a href = "{$_SERVER[PHP_SELF]}?id={$listSubjectArr['subject_id']}" title ="{$listSubjectArr['teacher_first_name']} {$listSubjectArr['teacher_last_name']} ">
						{$listSubjectArr['subject_name']}  
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>
ABC;
        }
    }
    return $listSubjectStr;
}

/* This function is used to list the Qualities */

function listQualities() {
    $listQualQuery = "SELECT * FROM `tbl_quality` ORDER BY `tbl_quality`.`quality_id`  DESC LIMIT 0,3";
    $listQualRes = mysql_query($listQualQuery) OR die(mysql_error());
    $listQualNumRows = mysql_num_rows($listQualRes);
    $listQualStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listQualNumRows) {
        while ($listQualArr = mysql_fetch_assoc($listQualRes)) {
            $listQualStr .= <<<ABC
				<div class='title'>	
					<a href = "qualities_edit.php?id={$listQualArr['quality_id']}">
					{$listQualArr['quality']}
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>	
ABC;
        }
    } else {
        $listQualStr .= "<p><span class= 'error'>No Quality(s)</span></p>";
    }
    $listQualStr .= '</div><!--End Of side body Div-->';
    return $listQualStr;
}

/* This function is used to list the Answers */

function listAnswer() {

    $listAnsQuery = "SELECT * FROM `tbl_answer` ORDER BY `answer_id` DESC LIMIT 0,3";
    $listAnsRes = mysql_query($listAnsQuery) OR die(mysql_error());
    $listAnsNumRows = mysql_num_rows($listAnsRes);
    $listAnsStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listAnsNumRows) {
        while ($listAnswerArr = mysql_fetch_assoc($listAnsRes)) {
            $listAnsStr .= <<<ABC
				<div class='title'>
					<a href = "answers_edit.php?id={$listAnswerArr['answer_id']}">
						{$listAnswerArr['answer_type']}
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>	
ABC;
        }
    } else {
        $listAnsStr .= "<p><span class= 'error'>No Answer(s)</span></p>";
    }
    $listAnsStr .= '</div><!--End Of side body Div-->';
    return $listAnsStr;
}

function listStudents() {
    $listStudentQuery = "SELECT *  FROM `tbl_users` WHERE `u_utype` LIKE 'student' ORDER BY u_id DESC LIMIT 0,3";
    $listStudentRes = mysql_query($listStudentQuery) OR die(mysql_error());
    $listStudentNumRows = mysql_num_rows($listStudentRes);
    $listStudentStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listStudentNumRows) {
        while ($listStudentArr = mysql_fetch_assoc($listStudentRes)) {
            $listStudentStr .= <<<ABC
				<div class='title'>
					<a href = "student_edit.php?id={$listStudentArr['u_id']}">
						{$listStudentArr['stud_regno']} | {$listStudentArr['u_fname']}
					</a>
				</div>
				<div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
				</div>
				<br/>	
				
ABC;
        }
    } else {
        $listStudentStr .= "<p><span class= 'error'>No Student(s)</span></p>";
    }
    $listStudentStr .= '</div><!--End Of side body Div-->';
    return $listStudentStr;
}

/* Latest List Function ends here */


/* This function will return the footer div information */

function plotFooterDiv() {
    $footerDivStr = <<<ABC
	<br/><!--This will give space b/w main div and footer if the latest entry block is empty-->
	<div class="footer">
		Copyright &copy Tumba College of Technology - Quality Assurance Office. <br />
		<a href="http://www.tct.ac.rw/index.php">Home</a> | <a href="http://www.tct.ac.rw/contactus.php">Contact</a> 
	</div><!--End of Footer div-->
	<div class="clr"></div>
ABC;
    return $footerDivStr;
}

/* This function will plot the department dropdown */

function plotDepartmentDropdown($deptSelVal = '', $ajaxEnabled='no', $subjectAjaxEnabled='no') {
    $deptDropdownStr = '';
    $deptQry = "SELECT * FROM `tbl_department` ORDER BY `department_name`";
    $deptRes = mysql_query($deptQry) or die(mysql_error());
    $deptNumRows = mysql_num_rows($deptRes);
    if ($deptNumRows) {
        if ($subjectAjaxEnabled == 'yes') {
            $deptDropdownStr = "<select name='department' id='department' class='typeproforms'
								onchange='javascript:plotSubjectByDept(\"\")'>";
        } elseif ($ajaxEnabled == 'yes') {
            $deptDropdownStr = "<select name='department' id='department' class='typeproforms'
								onchange='javascript:plotTeacherByDept(this.value)'>";
        } else {
            $deptDropdownStr = "<select name='department' id='department' class='typeproforms'>";
        }
        $deptDropdownStr .= "<option value=''>--select--</option>";
        while ($deptArr = mysql_fetch_assoc($deptRes)) {

            if ($deptArr['department_id'] == $deptSelVal) {
                $deptDropdownStr .= "<option value={$deptArr['department_id']} selected='selected'>{$deptArr['department_name']}</option>";
            } else {
                $deptDropdownStr .= "<option value={$deptArr['department_id']}>{$deptArr['department_name']}</option>";
            }
        }
        $deptDropdownStr .='</select>';
    } else {
        $deptDropdownStr = "<select name='department' id='department' class='typeproforms'>
								<option value = ''>--select--</option>
								</select>";
    }
    return $deptDropdownStr;
}

/* Subject Module Functions Starts Here */

/* This function will plot year dropdown */


/* This function will plot year dropdown */

function plotYearDropdown($yearSelValue = '') {
    global $yearArr; /* year array from config.php file */
    if (is_array($yearArr)) {
        $yearStr = "<select name='year' id='year' class='typeproforms'>";
        $yearStr .= "<option value=''>--select--</option>";
        foreach ($yearArr as $year) {
            if ($yearSelValue == $year) {
                $yearStr .= "<option value='{$yearSelValue}' selected='selected'>{$year}</option>";
            } else {
                $yearStr .= "<option value='{$year}'>{$year}</option>";
            }
        }
        $yearStr .= "</select>";
        return $yearStr;
    }
}

/* This function will plot Semester dropdown */

function plotSemesterDropdown($semSelValue = '') {
    global $semesterArr; /* year array from config.php file */
    if (is_array($semesterArr)) {
        $semesterStr = "<select name='semester' id='semester' class='typeproforms'>";
        $semesterStr .= "<option value=''>--select--</option>";
        foreach ($semesterArr as $semester) {
            if ($semSelValue == $semester) {
                $semesterStr .= "<option value='{$semSelValue}' selected='selected'>{$semester}</option>";
            } else {
                $semesterStr .= "<option value='{$semester}'>{$semester}</option>";
            }
        }
        $semesterStr .= "</select>";
        return $semesterStr;
    }
}

/* This function will plot Class Group dropdown */

function plotGroupDropdown($groupSelValue = '') {
    global $groupArr; /* year array from config.php file */
    if (is_array($groupArr)) {
        $groupStr = "<select name='group' id='group' class='typeproforms'>";
        $groupStr .= "<option value=''>--select--</option>";
        foreach ($groupArr as $group) {
            if ($groupSelValue == $group) {
                $groupStr .= "<option value='{$groupSelValue}' selected='selected'>{$group}</option>";
            } else {
                $groupStr .= "<option value='{$group}'>{$group}</option>";
            }
        }
        $groupStr .= "</select>";
        return $groupStr;
    }
}


/* This function will plot Sponsorship dropdown */

function plotSpoDropdown($spoSelValue = '') {
    global $spoArr; /* year array from config.php file */
    if (is_array($spoArr)) {
        $spoSpo = "<select name='sponsor' id='sponsor' class='typeproforms'>";
        $spoSpo .= "<option value=''>--select--</option>";
        foreach ($spoArr as $sponsor) {
            if ($spoSelValue == $sponsor) {
                $spoSpo .= "<option value='{$spoSelValue}' selected='selected'>{$sponsor}</option>";
            } else {
                $spoSpo .= "<option value='{$sponsor}'>{$sponsor}</option>";
            }
        }
        $spoSpo .= "</select>";
        return $spoSpo;
    }
}

/* This function will plot Sponsorship dropdown */

function plotGenderDropdown($genderSelValue = '') {
    global $genderArr; /* year array from config.php file */
    if (is_array($genderArr)) {
        $genderG = "<select name='gender' id='gender' class='typeproforms'>";
        $genderG .= "<option value=''>--select--</option>";
        foreach ($genderArr as $gender) {
            if ($genderSelValue == $gender) {
                $genderG .= "<option value='{$genderSelValue}' selected='selected'>{$gender}</option>";
            } else {
                $genderG .= "<option value='{$gender}'>{$gender}</option>";
            }
        }
        $genderG .= "</select>";
        return $genderG;
    }
}

/* This function will plot Sponsorship dropdown */

function plotTypeDropdown($typeSelValue = '') {
    global $typeArr; /* year array from config.php file */
    if (is_array($typeArr)) {
        $typeG = "<select name='type' id='type' class='typeproforms'>";
        $typeG .= "<option value=''>--select--</option>";
        foreach ($typeArr as $type) {
            if ($typeSelValue == $type) {
                $typeG .= "<option value='{$typeSelValue}' selected='selected'>{$type}</option>";
            } else {
                $typeG .= "<option value='{$type}'>{$type}</option>";
            }
        }
        $typeG .= "</select>";
        return $typeG;
    }
}

/* This function will plot Sponsorship dropdown */

function plotDesabDropdown($desabSelValue = '') {
    global $desabArr; /* year array from config.php file */
    if (is_array($desabArr)) {
        $desability = "<select name='desab' id='desab' class='typeproforms'>";
        $desability .= "<option value=''>--select--</option>";
        foreach ($desabArr as $desab) {
            if ($desabSelValue == $desab) {
                $desability .= "<option value='{$desabSelValue}' selected='selected'>{$desab}</option>";
            } else {
                $desability .= "<option value='{$desab}'>{$desab}</option>";
            }
        }
        $desability .= "</select>";
        return $desability;
    }
}

function checkSession() {
    if (!isset($_SESSION['u_id'])) {
        header('Location:index.php?msg=sesexpired');
        exit;
    } elseif (!(isset($_SESSION['u_uname']) && isset($_SESSION['u_pass']) && isset($_SESSION['u_utype']) && $_SESSION['u_utype'] == 'admin')) {
        header('Location:index.php?msg=invalid');
        exit;
    }
}

/* This function will check the dependency with the tables before delete the record */

function checkDependency($qryArr) {
    if (is_array($qryArr)) {
        foreach ($qryArr as $name => $query) {
            $dependentSearchRes = mysql_query($query) or die(mysql_error());
            $dependentSearchNumRows = mysql_num_rows($dependentSearchRes);
            if ($dependentSearchNumRows) {
                $dependentArr = mysql_fetch_array($dependentSearchRes);
                $dependentField = $dependentArr[0];
                if ($name == 'Feedback') {
                    return "Feedback";
                }
                return $name . " " . $dependentField;
            }
        }
    }
    return false;
}

function checkStudentSession() {
    if (!isset($_SESSION['u_id'])) {
        header('Location:index.php?msg=sesexpired');
        exit;
    } elseif (!(isset($_SESSION['u_uname'])  && isset($_SESSION['u_utype']) && $_SESSION['u_utype'] == 'student')) {
        header('Location:index.php?msg=invalid');
        exit;
    }
}

function plotLogoWithAddress() {
    $logoStr = <<<ABC
				<div style='margin:0 auto;width:500px;height:150px;'>
				<table border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td>
							<img src="../admin/images/logo.png"  style="float:none"  />
						</td>
						<td>
						<table>
							<tr><td>Tumba College Of Technology,</td></tr>
							<tr><td>P.O. BOX:6638,Rulindo,</td></tr>
							<tr><td>Tel:(250)7845015114 /5/6,</td></tr>
							<tr><td>Northern Province,</td></tr>
							<tr><td>Website:www.tct.ac.rw</td></tr>
							</tr>
						</table>
					</tr>
				</table>
					<div style = 'color:#fff;font-weight:bold;font-size:1.3em;border:1px;background-color:#096bad;width:600px;'>
						<center>WELCOME TO TCT STUDENTS COURSE EVALUATION</center>
					</div>
					<br>
					<div style = 'font-weight:bold;'>
						Thank you for taking your time to complete this evaluation form. Your ratings and comments will be very helpful to instructors, lecturers and the college in general
					</div>
				</div>
ABC;
    return $logoStr;
}

function __($code) {
    $Q = "UPDATE tbl_codes SET used = 1 WHERE code = '{$code}'";
    mysql_query($Q) or die(mysql_error());
}

function plotLogoWithAddress1() {
    $logoStr = <<<ABC
				<div style='margin:0 auto;width:500px;height:150px;'>
				<table border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td>
							<img src="../admin/images/logo.png"  style="float:none"  />
						</td>
						<td>
						<table>
							<tr><td>Tumba College Of Technology,</td></tr>
							<tr><td>P.O. BOX:6638,Rulindo,</td></tr>
							<tr><td>Tel:(250)7845015114 /5/6,</td></tr>
							<tr><td>Northern Province,</td></tr>
							<tr><td>Website:www.tct.ac.rw</td></tr>
							</tr>
						</table>
					</tr>
				</table>
					
				</div>
ABC;
    return $logoStr;
}

function extractQualitiesFromPost($post) {
    $qualitiesArr = array();
    foreach ($post as $k => $v) {
        if (preg_match("/_[0-9]+/", $k))
            $qualitiesArr [$k] = $v;
    }
    return $qualitiesArr;
}

function checkAlreadyExistFeedback($acc_year, $dept_id, $year, $semester, $group, $sub_id, $stud_id) {
    $checkAlreadyExistFeedbackQry = "SELECT * FROM tbl_feedback WHERE
feedback_acc_year = '{$acc_year}' AND
feedback_dept_id = {$dept_id} AND
feedback_year= '{$year}' AND
feedback_semester = '{$semester}' AND
feedback_group= '{$group}' AND
feedback_sub_id = {$sub_id} AND
feedback_stud_id = {$stud_id}";

    $checkAlreadyExistFeedbackRes = mysql_query($checkAlreadyExistFeedbackQry) OR die(mysql_error());
    $checkAlreadyExistRows = mysql_num_rows($checkAlreadyExistFeedbackRes);

    if ($checkAlreadyExistRows > 0)
        return true;
    return false;
}

function saveFeedBack($acc_year, $dept_id, $year, $semester,$group, $sub_id, $stud_id, $teacher_id, $arr_qualities, $comment) {

    $feedBackExist = checkAlreadyExistFeedback($acc_year, $dept_id, $year, $semester, $group, $sub_id, $stud_id);
    if ($feedBackExist)
        return;
    if (is_array($arr_qualities)) {
        foreach ($arr_qualities as $k => $v) {
            $k = substr($k, 1);
            $saveFeedbackQry = "INSERT INTO tbl_feedback set
feedback_acc_year = '{$acc_year}',
feedback_dept_id = {$dept_id},
feedback_year= '{$year}',
feedback_semester = '{$semester}',
feedback_sub_id = {$sub_id},
feedback_group = {$group},
feedback_quality_id= {$k},
feedback_answer_id = {$v},
feedback_stud_id = {$stud_id},
feedback_teacher_id = {$teacher_id}";


            mysql_query($saveFeedbackQry) or die(mysql_error());
            $_SESSION['has_made_fb'] = 'YES';
        }
    }

    if (isset($comment) && strlen(trim($comment)) > 0) {
        $comment = htmlspecialchars($comment);
        $comment = mysql_real_escape_string($comment);

        $saveCommentQry = "INSERT INTO tbl_comments set comment_value = '{$comment}',
       academic_year = '{$acc_year}', year = '{$year}', semester= '{$semester}', c_group='{$group}', department='{$dept_id}',subject= '{$sub_id}'";

        //die(stripslashes($saveCommentQry));
        mysql_query($saveCommentQry) or die(mysql_error());
        $_SESSION['hassavedsomething'] = "yes";
    }
}

function getFeedBack($acc_year, $sub_id) {

    $selectFeedbackQry = "SELECT * FROM tbl_feedback WHERE feedback_acc_year = {$acc_year} AND feedback_sub_id = {$sub_id}";
    $selectFeedbackRes = mysql_query($selectFeedbackQry) or die(mysql_error());

    //die($selectFeedbackQry);
}

function getAnswers() {
    $listAnsQuery = "SELECT * FROM `tbl_answer` ORDER BY `answer_score` ASC";
    $listAnsRes = mysql_query($listAnsQuery) OR die(mysql_error());
    $listAnsNumRows = mysql_num_rows($listAnsRes);

    if ($listAnsNumRows) {
        $listAnswerArr = array();
        while ($row = mysql_fetch_assoc($listAnsRes)) {
            $listAnswerArr[$row['answer_type']] = $row['answer_id'];
        }

        return $listAnswerArr;
    }
    return array();
}

function checkAllowedSubject($sid, $code) {
    $info = getInfoFromCode($code);
    $department = $info ['department'];
    $year = $info['year'];
    $semester = $info ['semester'];

    $Q = "SELECT * FROM tbl_subject WHERE subject_id = '{$sid}' AND subject_department_id = '{$department}' AND subject_year = '{$year}' AND subject_semester = '{$semester}'";

    $res = mysql_query($Q);

    if (mysql_num_rows($res) > 0)
        return true;
    return false;
}

function plotFeedBackFormx($sid, $group, $tid, $code) {

    $allowed = checkAllowedSubject($sid, $code);

    if ($allowed) {

        $answers = getAnswers();
        $qualities = getQualities();

        $s = "
        <form action ='{$_SERVER['PHP_SELF']}' method ='POST' onsubmit = 'return x()'>
   <div class='__ac__' id='_c'>
";
        $i = 0;
        foreach ($qualities as $k => $v) {
            $i++;
            $s .="
                    <div class='__ac__-group'>
                        <div class='__ac__-heading'>
                            <a class='__ac__-toggle' data-toggle='collapse' data-parent='#_c' href='#collapse{$i}'>
        {$k}
                            </a>
                        </div>
                        <div id='collapse{$i}' class='__ac__-body collapse' style='height: 0px;'>
                            <div class='__ac__-inner'>
                                <table class='table table-hover'>

                                    <tbody>
                        <tr class='info'><td class='xxx'>No</td><td colspan='6'>Quality</td>
                        ";
            foreach ($answers as $_kanswer => $_vanswer) {
                $s .= "<td>{$_kanswer}</td>";
            }


            $s .="</tr>";

            $_c = 0;
            foreach ($v as $_k => $_v) {
                $_c++;
                $s .="
                        <tr> <td>
                                        {$_c}.</td>
                                    <td colspan='6'>{$_v}</td>

                                    ";
                foreach ($answers as $_kanswer => $_vanswer) {
                    $s .= "<td><input value='{$_vanswer}' name='_$_k' type='radio'></td>";
                }
                $s .="</tr> ";
            }

            $s .="</tbody></table></div></div></div>
     ";
        }
        $s .="<br />
   
            <table width='80%'>
                <tbody> 
                <tr>
                </tr>
                <tr>
                    <td >
                         <strong>Comment (Maximum: 200 characters)</strong>
                    </td>
                </tr>

                <tr>
                    <td>
                        <!--<textarea name='comment' rows='2' cols='60' maxlength= '200' onkeyup='countRChars();'></textarea>-->
						<textarea name='comment' rows='2' cols='60' minlength='20' maxlength= '200' onkeyup='countRChars();'></textarea><?php //By Nepo ?>
                    </td>
                </tr>


            </tbody></table><br />
            <input type = 'hidden' name ='subject_id' value = '$sid' />
            <input type = 'hidden' name ='subject_teacher_id' value = '$tid' />
            <input type = 'hidden' name ='subject_group' value = '$group' />
               <input class='button' id='btnSave' name='btnSave' value='Submit Feedback' type='submit'>
                    
            </div></form>";
        return $s;
    }
    else
        return "";
}

function getQScore($acc_year, $sub_id, $qId) {
/*
Updated by Nepo San 
Start
*/
	$tstr = "SELECT MAX(answer_score) AS max_answer FROM tbl_answer";
	$qrst = mysql_query($tstr) or die(mysql_error());
	$max_answer = 0;
	if ($row = mysql_fetch_assoc($qrst)){
		$max_answer = $row['max_answer'];
	}

    define("MAX_ANSWER", $max_answer);
/*
End
*/
//    define("MAX_ANSWER", 3); Codes by Dieudone San
    $q = "SELECT feedback_answer_id, answer_score FROM `tbl_feedback` INNER JOIN tbl_answer ON feedback_answer_id=answer_id WHERE `feedback_sub_id` = {$sub_id} AND `feedback_acc_year` = {$acc_year} AND `feedback_quality_id` = {$qId}";


    $rslt = mysql_query($q) OR die(mysql_error());
    $rno = mysql_num_rows($rslt);

    $sum = 0;

    $count = 0;
    if ($rno) {

        while ($rec = mysql_fetch_assoc($rslt)) {
            $sum += $rec['answer_score'];
            $count++;
        }
    }

    return $count > 0 ? $sum / MAX_ANSWER / $count * 100 : 0;
}

function getFeedBackReport($acc_year, $sid) {

    //$qualities ["kwigisha"][$acc_year] = 91;
    //$qualities ["gukosora"][$acc_year] = 92;
    //$qualities ["gusobanura"][$acc_year] = 65;
    // $qualities ["gutegura"][$acc_year] = 88;

    $qualities = getQualities();
    $score = array();


    foreach ($qualities as $k => $v) {


        foreach ($v as $_k => $_v) {
            $a = ceil(getQScore($acc_year, $sid, $_k) * 10) / 10;
            $score [$k][$_v][$acc_year] = $a;

            //  if (++ $x == 5) return $score;
        }
    }


    return $score;

    //return $qualities;
}

function getFeedBackReport_x($acc_year, $sid) {

    $qualities = getQualities();
    $score = array();


    foreach ($qualities as $k => $v) {

        $a = 0;
        foreach ($v as $_k => $_v) {
            $a += ceil(getQScore($acc_year, $sid, $_k) * 10) / 10;
        }
        $a /= count($v);
        $score [$k][$acc_year] = $a;
    }




    return $score;
}

function plotFeedBackReport($acc_year, $sid) {

    $qualities = getQualities();
    $s = "
        <form action ='{$_SERVER['PHP_SELF']}' method ='POST'>
   <div class='__ac__' id='_c'>
";
    $i = 0;
    $gdTotal = 0;
    $gdCount = 0;
    foreach ($qualities as $k => $v) {
        $i++;
        $s .="
                    <div class='__ac__-group'>
                        <div class='__ac__-heading'>
                            <a class='__ac__-toggle' data-toggle='collapse' data-parent='#_c' href='#collapse{$i}'>
        {$k}
                            </a>
                        </div>
                        <div id='collapse{$i}' class='__ac__-body collapse' style='height: 0px;'>
                            <div class='__ac__-inner'>
                                <table class='table table-hover'>

                                    <tbody>
                        <tr class='info'><td class='xxx'>No</td><td>Quality</td>

         <td>Total</td>
         </tr>";

        $_c = 0;

        $sbTotal = 0;

        foreach ($v as $_k => $_v) {
            $a = ceil(getQScore($acc_year, $sid, $_k) * 10) / 10;
            $sbTotal += $a;
            $_c++;
            $s .="<tr><td>{$_c}.</td><td>{$_v}</td><td>{$a}%</td></tr>";
        }
        $sbTotal = ceil(($_c > 0 ? $sbTotal / $_c : 0) * 10) / 10;
        $gdTotal += $sbTotal;
        $gdCount++;
        $s .="<tr><td colspan ='3'>Sub Total: {$sbTotal}%</td></tr>";
        $s .="</tbody></table></div></div></div>";
    }

    $comments = getComments($acc_year, $sid);

    
    
    $comment = "";

    
    if (sizeof ($comments) > 0 ) {
    
    foreach ($comments as $k => $c) {
            $name = htmlentities($c);

             $comment.="<p id='$k' class='edit_tr'>

<span id='one_$k' class='text'>$c</span>
<textarea value='$c' class='editbox' id='one_input_$k' cols='70'></textarea>
<br />
            <a href='#' class='delete' id=del_$k> X </a>  <a href='#' class='save' id=save_$k> Edit </a>
            </p>
 ";
        }
        
    }
    else {
        $comment = "No comment found";
    }
    $gdTotal = ceil(($gdCount > 0 ? $gdTotal / $gdCount : 0) * 10) / 10;
    $s .="<br />

            <table width='80%'>
                <tbody>
               
                 <tr>
                    <td >
                         Grand Total: {$gdTotal}
                    </td>
                </tr>
                 <tr>
                 <td>&nbsp;</td>
                </tr></tbody>
                         </table>
                </div>
                         
                         <input id='ccomments' class='ccomments' type='button' value='View Comments' />
                   <!--   <a class ='ccomments'  href='pr_1.php?acc_year={$acc_year}&sid={$sid}' style ='text-decoration: none; cursor: default'>Generate Report</a> -->
                      <a class ='ccomments' href='pr_2.php?acc_year={$acc_year}&sid={$sid}' style ='text-decoration: none; cursor: default'>Generate Report</a>
                         <div id='ccomment'>
                          <p>
                         {$comment}
                         </div> ";


    return $s;
}

function plotFeedBackReport1($acc_year, $sid) {
    $qualities = getQualities();

    $s = "
        <form action ='{$_SERVER['PHP_SELF']}' method ='POST'>
   <div class='__ac__' id='_c'> 
";
    $i = 0;
    $gdTotal = 0;
    $gdCount = 0;
    $x = 0;


    foreach ($qualities as $k => $v) {
        //  $i++;
        $s .="{$k} <table><tr valign=top><td><table class ='rpt_table'>
                                    <tbody>
                        <tr class='info'><td>Quality</td>

         <td>Score</td>
         </tr>";


        $sbTotal = 0;

        //  ++$x;

        foreach ($v as $_k => $_v) {
            //$a = ceil(getQScore($acc_year, $sid, $_k) * 10) / 10;
            $sc = getQScore($acc_year, $sid, $_k);
            $a = $sc > 0 ? ceil($sc * 10) / 10 : 0;
            $sbTotal += $a;

            $s .="<tr><td>{$_v}</td><td>{$a}%</td></tr>";
        }
        $sbTotal = ceil((count($v) > 0 ? $sbTotal / count($v) : 0) * 10) / 10;
        $gdTotal += $sbTotal;
        $gdCount++;
        $x++;
        $s .="<tr><td colspan ='2'>Sub Total: {$sbTotal}%</td></tr>";
        $s .="</tbody></table></td><td><div id='chart{$x}' style='width: 250px;margin-left: 30px; margin-right: 30px; height: 300px'></div>
        </td></tr></table>";
    }


    $comments = getComments($acc_year, $sid);

    $comment = "";
    foreach ($comments as $c) {
        $comment .= "<p class ='pcomment'>" . $c . "</p>";
    }
    $gdTotal = ceil(($gdCount > 0 ? $gdTotal / $gdCount : 0) * 10) / 10;
    $s .="<br />

            <table width='80%'>
                <tbody>

                 <tr>
                    <td >
                         Grand Total: {$gdTotal}
                    </td>
                </tr>
                 <tr>
                 <td>&nbsp;</td>
                </tr></tbody>
                         </table></div>
                 ";


    return $s;
}

function plotFeedBackReport1_x($acc_year, $sid) {
    $qualities = getQualities();

    $s = "
        <form action ='{$_SERVER['PHP_SELF']}' method ='POST'>
   <div class='__ac__' id='_c'>
";
    $i = 0;
    $gdTotal = 0;
    $gdCount = 0;
    $x = 0;

    $s .= "<table><tr><td valign=top><table class ='rpt_table' style='width:220px;'><tr><td><strong>Quality</strong></td><td><strong>Score</strong></td></tr>";
    $c_array = range ('a','f');
    foreach ($qualities as $k => $v) {
          
       // $s .="<tr valign=top><td>({$c_array[$i++]}) {$k}</td>";
        $s .="<tr valign=top><td>{$k}</td>";

        $sbTotal = 0;
        foreach ($v as $_k => $_v) {
            //$a = ceil(getQScore($acc_year, $sid, $_k) * 10) / 10;
            $sc = getQScore($acc_year, $sid, $_k);
            $a = $sc > 0 ? ceil($sc * 10) / 10 : 0;
            $sbTotal += $a;
        }
        $sbTotal = ceil((count($v) > 0 ? $sbTotal / count($v) : 0) * 10) / 10;
        $gdTotal += $sbTotal;
        $gdCount++;
        $x++;
        $s .="<td>{$sbTotal}%</td></tr>";
    }

    $gdTotal = ceil(($gdCount > 0 ? $gdTotal / $gdCount : 0) * 10) / 10;

    $s .="<tr><td colspan='2'><strong>Total: {$gdTotal}%</strong></tr></table></td><td><div id='chart1' style='width: 300px;margin-left: 30px; margin-right: 30px; height: 400px'></div>
       </td></tr></table>";
    $s .="<br /></div> ";


    return $s;
}

function getComments($acc_year, $sid) {


    $_Q = "SELECT *  FROM `tbl_comments` WHERE `academic_year` = {$acc_year} AND `subject` = {$sid} ";

    $rslt = mysql_query($_Q) OR die(mysql_error());
    $rno = mysql_num_rows($rslt);

    $comments = array();

    while ($rec = mysql_fetch_assoc($rslt)) {
        $comments[$rec['comment_id']] = chunk_split($rec['comment_value'], 85, '<br />');
    }

    return $comments;
}

function plotComments($acc_year, $sid) {

    $qualities = getQualities();
    $s = "
        <form action ='{$_SERVER['PHP_SELF']}' method ='POST'>
   <div class='__ac__' id='_c'>
";
    $i = 0;
    $gdTotal = 0;
    $gdCount = 0;
    foreach ($qualities as $k => $v) {
        $i++;
        $s .="
                    <div class='__ac__-group'>
                        <div class='__ac__-heading'>
                            <a class='__ac__-toggle' data-toggle='collapse' data-parent='#_c' href='#collapse{$i}'>
        {$k}
                            </a>
                        </div>
                        <div id='collapse{$i}' class='__ac__-body collapse' style='height: 0px;'>
                            <div class='__ac__-inner'>
                                <table class='table table-hover'>

                                    <tbody>
                        <tr class='info'><td class='xxx'>No</td><td>Quality</td>

         <td>Total</td>
         </tr>";

        $_c = 0;

        $sbTotal = 0;

        foreach ($v as $_k => $_v) {
            $a = ceil(getQScore($acc_year, $sid, $_k) * 10) / 10;
            $sbTotal += $a;
            $_c++;
            $s .="<tr><td>{$_c}.</td><td>{$_v}</td><td>{$a}%</td></tr>";
        }
        $sbTotal = ceil(($_c > 0 ? $sbTotal / $_c : 0) * 10) / 10;
        $gdTotal += $sbTotal;
        $gdCount++;
        $s .="<tr><td colspan ='3'>Sub Total: {$sbTotal}%</td></tr>";
        $s .="</tbody></table></div></div></div>";
    }
    $s .="

            </div></form>";


    return $s;
}

function plotTextArea($name = 'comments', $rows='5', $cols='8') {
    return "<textarea class='typeproforms' name = {$name} id = 'qualities' rows = '{$rows}' cols='{$cols}'></textarea>";
}

function plotFeedbackForm($subYear, $subSem, $subGroup, $subDeptId) {
    $subjListQry = "SELECT *  FROM `tbl_subject` WHERE `subject_year` LIKE '{$subYear}' AND `subject_semester` = {$subSem} AND `subject_group` = {$subGroup} AND `subject_department_id`={$subDeptId}";
    $subjListRes = mysql_query($subjListQry) or die(mysql_error());
    $subjListNumRows = mysql_num_rows($subjListRes);
    $subjListRes1 = mysql_query($subjListQry) or die(mysql_error());
    $subjListNumRows1 = mysql_num_rows($subjListRes1);
    $subjListRes2 = mysql_query($subjListQry) or die(mysql_error());
    $subjListNumRows2 = mysql_num_rows($subjListRes2);
    $subjListRes3 = mysql_query($subjListQry) or die(mysql_error());
    $subjListNumRows3 = mysql_num_rows($subjListRes3);
    $subjListRes4 = mysql_query($subjListQry) or die(mysql_error());
    $subjListNumRows4 = mysql_num_rows($subjListRes4);
    if ($subjListNumRows) {
        echo "<form method='post' action='feedback.php'>";
        echo "<div style = 'color:red;font-weight:bold;font-size:1.3em;border:1px;background-color:#;width:600px;'>";
        echo "A. Module/course Content And organisation ratings";

        echo "</div>";
        echo "<br>";
        echo '<table id ="listEntries" width = "100%" border="1">';
        echo '<tr>';
        $AnswerCounter = 0;
        echo "<th>Qualities</th>";
        while ($subjListArr = mysql_fetch_assoc($subjListRes)) {
            $answerAll['subjectIdArr'] = $subjListArr['subject_id'];
            echo "<th>{$subjListArr['subject_name']}</th>";
            echo "<input type ='hidden' name='subject_id[]' value = '{$subjListArr['subject_id']}' />";
            $AnswerCounter++;
        }
        echo '</tr>';
        $qualListQry = "SELECT * FROM  `tbl_quality`";
        $qualListRes = mysql_query($qualListQry) or die(mysql_error());
        $qualListNumRows = mysql_num_rows($qualListRes);
        $qualListQry1 = "SELECT * FROM  `tbl_quality` where Title = 'Student contribution and ratings'";
        $qualListRes1 = mysql_query($qualListQry1) or die(mysql_error());
        $qualListNumRows1 = mysql_num_rows($qualListRes1);
        $qualListQry2 = "SELECT * FROM  `tbl_quality` where Title = 'Learning environment and teaching'";
        $qualListRes2 = mysql_query($qualListQry2) or die(mysql_error());
        $qualListNumRows2 = mysql_num_rows($qualListRes2);
        $qualListQry3 = "SELECT * FROM  `tbl_quality` where Title = 'Overall Experience'";
        $qualListRes3 = mysql_query($qualListQry3) or die(mysql_error());
        $qualListNumRows3 = mysql_num_rows($qualListRes3);
        $qualListQry4 = "SELECT * FROM  `tbl_quality` where Title = 'Teacher ratings'";
        $qualListRes4 = mysql_query($qualListQry4) or die(mysql_error());
        $qualListNumRows4 = mysql_num_rows($qualListRes4);

        if ($qualListNumRows) {
            while ($qualListArr = mysql_fetch_assoc($qualListRes)) {

                echo "<tr>";
                echo "<td>{$qualListArr['quality']}</td>";
                echo "<input type ='hidden' name='quality_id[]' value = '{$qualListArr['quality_id']}' />";
                for ($i = 1; $i <= $AnswerCounter; $i++) {
                    echo "<td id='center'>", plotAnswerDropdown(), "</td>";
                }

                echo "</tr>";
            }



            /* Comment Text Area starts here */
            echo "<tr>";
            echo "<td>Comment About the Course</td>";
            for ($i = 1; $i <= $AnswerCounter; $i++) {
                echo "<td id='center'>", plotTextArea($name = 'comments[]', $rows = '4', $cols = '5'), "</td>";
            }
            echo "</td>";
            echo "</tr>";
            /* Comment Text Area ends here */
            /*  onclick=\"javascript: return confirm('Sure! Do you want to Submit the Feedback?');\"
             */
        }
        echo "<tr>";
        echo "<td colspan='" . ++$AnswerCounter . "' align='center'>";


        echo "<input type = 'submit' name = 'submit' class='button' value = 'Submit Feedback'";

        echo "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "<p align='center'><span class = 'error'>Questions Not Prepared Yet!</span></p>";
    }
}

function plotAnswerDropdown() {
    $answerListQry = "SELECT * FROM  `tbl_answer`";
    $answerListRes = mysql_query($answerListQry) or die(mysql_error());
    $answerListNumRows = mysql_num_rows($answerListRes);
    if ($answerListNumRows) {
        $answerDropdownStr = "<select name='answer[]' id = 'answer' class='typeproforms' style='color:red;'>";
//$answerDropdownStr  .=	"<option>Select here</option>";
        echo "<font style='color:red;'>";
        $k = "--Select Here--";
        echo "</font>";
        $answerDropdownStr .= "<option value=\"Select\" style='color:red;'>$k</option>";

        if ($answerListNumRows) {
            while ($answerListArr = mysql_fetch_assoc($answerListRes)) {

                $answerDropdownStr .= "<option value=\"{$answerListArr['answer_id']}\"  style='color:black;'>
											{$answerListArr['answer_type']}</option>";
            }
        }
        $answerDropdownStr .= "</select>";
        return $answerDropdownStr;
    }
}

function plotSearchDiv($searchActionFile) {
    $searchVal = (isset($_GET['keyword'])) ? $_GET['keyword'] : 'Search';
    if (isset($_GET['keyword']) && $_GET['keyword'] == '') {
        $searchVal = 'Search';
        ;
    }
    $searchDiv = <<<ABC
				<div class="search" id='searchEffect'>
					<form action="{$searchActionFile}" method="get" name="search" onsubmit="if (document.search.keyword.value == 'Search'){ document.search.keyword.value = '';}"">
					<input name="keyword" type="text"  class="keywords" value="$searchVal" 
						onfocus="if (document.search.keyword.value == 'Search'){ document.search.keyword.value = '';}" 
						onblur="if (document.search.keyword.value == ''){ document.search.keyword.value = 'Search';}" />
					<input name="Search" type="image" src="images/search.gif" value="Search"  />
					</form>
				</div><!-- End of Search Div-->
ABC;
    return $searchDiv;
}

function plotDropdownFromHistoryTable($dropdownQry, $dropdownName, $selValue = '', $subjectAjaxEnabled = 'no') {
    $dropdownRes = mysql_query($dropdownQry) or die(mysql_error());
    $dropdownRows = mysql_num_rows($dropdownRes);

    if ($subjectAjaxEnabled == 'yes') {
        $dropdownStr = "<select name='{$dropdownName}' id='{$dropdownName}' class='typeproforms'
								onchange='javascript:plotSubjectByDeptForHistory(\"\")'>";
        $dropdownStr .= "<option value=''>--select--</option>";
    } else {
        $dropdownStr = "<select name='{$dropdownName}' id='{$dropdownName}' class='typeproforms'>";
        $dropdownStr .= "<option value=''>--select--</option>";
    }
    if ($dropdownRows) {
        while ($dropdownArr = mysql_fetch_row($dropdownRes)) {
            if ($dropdownArr[0] == $selValue) {
                $dropdownStr .= "<option value='{$dropdownArr[0]}' selected='selected'>{$dropdownArr[0]}</option>";
            } else {
                $dropdownStr .= "<option value='{$dropdownArr[0]}'>{$dropdownArr[0]}</option>";
            }
        }
        $dropdownStr .= "</select>";
        return $dropdownStr;
    }
    return $dropdownStr;
}

function plotAcademicYear($startYear, $endYear, $selValue = '') {
    echo "<select name='academic_year' id = 'academic_year' class='typeproforms'>";
    echo "<option value=''>--select--</option>";
    for ($i = $startYear; $i <= $endYear; $i++) {
        if ($i == $selValue) {
            echo "<option value='{$i}' selected='selected'>{$selValue}</option>";
        } else {
            echo "<option value='{$i}'>{$i}</option>";
        }
    }
    echo "</select>";
}

function getQualities() {

    $criteria = getCriteria();
    $qualities = NULL;
    foreach ($criteria as $_vcriteria) {
        $sql = "SELECT * FROM  `tbl_quality` where Title = '{$_vcriteria}' ";
        $_rcriteria = mysql_query($sql);

        while ($row = mysql_fetch_assoc($_rcriteria)) {
            $qualities[$row['Title']][$row['quality_id']] = $row['quality'];
        }
    }
    return $qualities;
}

function getCriteria() {
    // $listQualityQuery = "SELECT distinct(Title) FROM `tbl_quality`";
    //$listQualityRes = mysql_query($listQualityQuery) OR die(mysql_error());
    // while ($listDeptArr = mysql_fetch_assoc($listQualityRes)) {
    //  $criteria [] = $listDeptArr['Title'];
    //}

    $criteria = array(
        "Module/Course Content and organisation",
        "Student contribution",
        "Teaching and Learning",
        "Overall Experience",
        "Teacher ratings"
    );

    return $criteria;
}

function hasEvaluationData($sub_id, $acc_year) {
    $Q = "SELECT * FROM `tbl_feedback` WHERE `feedback_sub_id` = {$sub_id} AND `feedback_acc_year` = {$acc_year}";


    $rslt = mysql_query($Q) OR die(mysql_error());
    $rno = mysql_num_rows($rslt);


    return $rno > 0 ? TRUE : FALSE;
}
   
?>