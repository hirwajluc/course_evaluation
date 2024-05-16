<?php

/* This function will return the header string with menu information */

function plotHeaderMenuInfo($activemenuName) {

    if ($_SESSION['u_utype'] == "admin") {
        $menuNamesArr = array(
            "Departs" => "department_t_new.php",
            "Progs" => "department_new.php",
            "Tchr" => "teachers_new.php",
            "Course" => "subjects_new.php",
            "Qualties" => "qualities_new.php",
            "Stud" => "student_new.php",
            "Usr" => "users_new.php",
            "Reprt" => "current_class_wise.php",
            "Ansrs" => "answers_new.php"
        );
    } elseif ($_SESSION['u_utype'] == "coladmin") {
        $menuNamesArr = array(
            "Departments" => "department_t_new.php",
            "Programs" => "department_new.php",
            "Teachers" => "teachers_new.php",
            "Subjects" => "subjects_new.php",
            "Students" => "student_new.php",
            "Users" => "users_new.php",
            "Report" => "current_class_wise.php"
        );
    } else{
        $menuNamesArr = array(
            "Teachers" => "teachers_new.php",
            "Subjects" => "subjects_new.php",
            "Students" => "student_new.php",
            "Report" => "current_class_wise.php"
        );
    }
    
    echo "<div class='header'>
			<div class='header_text'>
			  <p align='right'>Welcome 
				<strong>{$_SESSION['u_utype']}</strong><br/>
				<a href='change_password.php'>Change Password</a> | <a href='../admin/logout.php'>Logout</a>
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
    global $connPDO;
    $selectResource = $connPDO->query($selectQuery);
    $selNumRows = $selectResource -> rowCount();
    if ($selNumRows > 0) {
        return true;
    } else {
        return false;
    }
}

/* General Function to Insert Record into the table */

function insertOrUpdateRecord($query, $redirectFileName, $id = '') {
    global $connPDO;
    $resResource = $connPDO->query($query);
    $affectedRows = $resResource -> rowCount();
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
    global $connPDO;
    $dep_filter = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
    //$ac_filter = array(1, 2); // By Muganga
    $ac_filter = array(1, 2, 3); // By Dieudonne
    $sem_filter = array(1, 2); // By Serge
    $gr_filter = array(1, 2, 3, 4); // By Nepo
    
    /** Blaise Logic */
    if(strlen($code) == 9):
        $dep = $code[0];
        $year = $code[1];
        $sem = $code [2];
        $group= $code [3];
    elseif(strlen($code) == 10):
        $dep = $code[0].$code[1];
        $year = $code[2];
        $sem = $code [3];
        $group= $code [4];
    endif;

        

    $getAcYr = $connPDO->query("SELECT * FROM `tbl_codes` WHERE code = '{$code}'");
    $acyearArr = $getAcYr->fetch(PDO::FETCH_ASSOC) ;
    $academicYear = $acyearArr['academic_year'];
    // if (in_array($dep, $dep_filter) && in_array($year, $ac_filter) && in_array($sem, $ac_filter) && in_array($group, $ac_filter)) { // By Muganga
    if (in_array($dep, $dep_filter) && in_array($year, $ac_filter) && in_array($sem, $sem_filter) && in_array($group, $gr_filter)) { // By Serge
        return array("department" => $dep, "academic_year" => $academicYear, "year" => $year, "semester" => $sem, "group" => $group);
    }
    return array();
}

/* This function is used to list the Departments names */

function listDepartments() {
    global $connPDO;
    $listDeptQuery = "SELECT * FROM `tbl_t_departments` ORDER BY dpt_id DESC LIMIT 0,3";
    $listDeptRes = $connPDO->query($listDeptQuery);
    $listDeptNumRows = $listDeptRes->rowCount();
    $listDeptStr = "<div class='side_body shadowEffect'>
							<h2>Latest Entries...</h2>";
    if ($listDeptNumRows) {
        while ($listDeptArr = $listDeptRes -> fetch(PDO::FETCH_ASSOC)) {
            $listDeptStr .= <<<ABC
				<div class='title'>
					<a href = "departments_t_edit.php?id={$listDeptArr['dpt_id']}">
					{$listDeptArr['dpt_name']}
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

/* This function is used to list the Programs names */

function listDepartment() {
    global $connPDO;
    $listDeptQuery = "SELECT * FROM `tbl_department` ORDER BY department_id DESC LIMIT 0,3";
    $listDeptRes = $connPDO->query($listDeptQuery);
    $listDeptNumRows = $listDeptRes->rowCount();
    $listDeptStr = "<div class='side_body shadowEffect'>
							<h2>Latest Entries...</h2>";
    if ($listDeptNumRows) {
        while ($listDeptArr = $listDeptRes -> fetch(PDO::FETCH_ASSOC)) {
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
        $listDeptStr .= "<p><span class= 'error'>No Program(s)</span></p>";
    }
    $listDeptStr .= '</div><!--End Of side body Div-->';
    return $listDeptStr;
}

/* This function is used to list the Teachers */

function listTeacher() {
    global $connPDO;
    $listTeacherQuery = "SELECT * FROM tbl_teacher INNER JOIN tbl_t_departments
									ON tbl_teacher.teacher_department_id = tbl_t_departments.dpt_id 
									ORDER BY tbl_teacher.teacher_id DESC LIMIT 0,3";
    $listTeacherRes = $connPDO->query($listTeacherQuery);
    $listTeacherNumRows = $listTeacherRes->rowCount();

    $listTeacherStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listTeacherNumRows > 0) {
        while ($listTeacherArr = $listTeacherRes->fetch(PDO::FETCH_ASSOC)) {

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
    global $connPDO;
    $listSubjectQuery = "SELECT * FROM tbl_subject INNER JOIN tbl_department
								ON tbl_subject.subject_department_id = tbl_department.department_id
								ORDER BY subject_id  DESC LIMIT 0,3";
    $listSubjectRes = $connPDO->query($listSubjectQuery);
    $listSubjectNumRows = $listSubjectRes->rowCount();

    $listSubjectStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listSubjectNumRows > 0) {
        while ($listSubjectArr = $listSubjectRes->fetch(PDO::FETCH_ASSOC)) {

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
    global $connPDO;
    $listSubjectQuery = "SELECT * FROM tbl_subject INNER JOIN tbl_department
						ON tbl_subject.subject_department_id = tbl_department.department_id
                        WHERE tbl_subject.subject_id = $id AND tbl_subject.subject_department_id = {$_SESSION['stud_dept']} AND tbl_subject.subject_year={$_SESSION['stud_year']} AND tbl_subject.subject_semester={$_SESSION['stud_sem']}";
    $listSubjectRes = $connPDO->query($listSubjectQuery);
    $listSubjectNumRows = $listSubjectRes->rowCount();

    if ($listSubjectNumRows > 0) {
        while ($listSubjectArr = $listSubjectRes->fetch(PDO::FETCH_ASSOC)) {
            return $listSubjectArr['subject_name'];
        }
    }
    return "";
}

function getSubjectDetails($id) {
    global $connPDO;
    $getSubjectQuery = "SELECT s.subject_id, s.subject_ac_year, s.subject_name,s.subject_code, s.subject_year, s.subject_teacher_id, s.subject_semester, s.subject_group, d.department_name, t.teacher_first_name, t.teacher_last_name FROM tbl_subject s LEFT OUTER JOIN tbl_department d ON s.subject_department_id = d.department_id LEFT OUTER JOIN tbl_teacher t ON s.subject_teacher_id = t.teacher_id  WHERE s.subject_id = $id ";
    $getSubjectRes = $connPDO->query($getSubjectQuery);
    $nRows = $getSubjectRes->rowCount();


    if ($nRows) {
        while ($subjectArr = $getSubjectRes->fetch(PDO::FETCH_ASSOC)) {
            $sDetail['subject_id'] = $subjectArr['subject_id'];
            $sDetail['subject_ac_year'] = $subjectArr['subject_ac_year'];
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
    global $connPDO;
    $listSubjectQuery = "SELECT feedback_id FROM tbl_feedback WHERE  feedback_stud_id = {$_SESSION['u_id']} AND feedback_sub_id = {$sid} AND feedback_dept_id = {$_SESSION['stud_dept']} AND feedback_year ={$_SESSION['stud_year']} AND feedback_semester={$_SESSION['stud_sem']}";

    $listSubjectRes = $connPDO->query($listSubjectQuery);
    $listSubjectNumRows = $listSubjectRes->rowCount();

    if ($listSubjectNumRows > 0)
        return true;
    return false;
}

function listStudentSubjects($code, $acad_year) {
    global $connPDO;
    $info = getInfoFromCode($code);
    $department = $info['department'];
    $year = $info['year'];
    $semester = $info['semester'];
    $group = $info['group'];

    $listSubjectQuery = "SELECT * FROM (SELECT s.subject_id, s.subject_ac_year, s.subject_name, s.subject_group, t.teacher_first_name, t.teacher_last_name FROM tbl_subject s INNER JOIN tbl_department d
		ON s.subject_department_id = d.department_id LEFT OUTER JOIN tbl_teacher t ON s.subject_teacher_id = t.teacher_id 
		WHERE s.subject_department_id = {$department} AND s.subject_year={$year} AND s.subject_ac_year = '{$acad_year}' AND s.subject_semester = {$semester}
        AND s.subject_group & {$group} > 0) AS s WHERE s.subject_group = {$group}
        ORDER BY s.subject_id  DESC";

    $listSubjectRes = $connPDO->query($listSubjectQuery);
    $listSubjectNumRows = $listSubjectRes->rowCount();

    $listSubjectStr = "";
    if ($listSubjectNumRows) {
        while ($listSubjectArr = $listSubjectRes->fetch(PDO::FETCH_ASSOC)) {
            if (alreadyEvaluated($listSubjectArr['subject_id']))
                continue;
            $_SESSION['has_made_fb'] = 'OK';
            $listSubjectStr .= <<<ABC
				<div class='title'>
					<a href = "{$_SERVER['PHP_SELF']}?id={$listSubjectArr['subject_id']}" title ="{$listSubjectArr['teacher_first_name']} {$listSubjectArr['teacher_last_name']} ">
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
    global $connPDO;
    $listQualQuery = "SELECT * FROM `tbl_quality` ORDER BY `tbl_quality`.`quality_id`  DESC LIMIT 0,3";
    $listQualRes = $connPDO->query($listQualQuery);
    $listQualNumRows = $listQualRes -> rowCount();
    $listQualStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listQualNumRows) {
        while ($listQualArr = $listQualRes->fetch(PDO::FETCH_ASSOC)) {
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
    global $connPDO;
    $listAnsQuery = "SELECT * FROM `tbl_answer` ORDER BY `answer_id` DESC LIMIT 0,3";
    $listAnsRes = $connPDO->query($listAnsQuery);
    $listAnsNumRows = $listAnsRes -> rowCount();
    $listAnsStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listAnsNumRows) {
        while ($listAnswerArr = $listAnsRes->fetch(PDO::FETCH_ASSOC)) {
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
    global $connPDO;
    $listStudentQuery = "SELECT *  FROM `tbl_users` WHERE `u_utype` LIKE 'student' ORDER BY u_id DESC LIMIT 0,3";
    $listStudentRes = $connPDO->query($listStudentQuery);
    $listStudentNumRows = $listStudentRes -> rowCount();
    $listStudentStr = "<div class='side_body shadowEffect'>
								<h2>Latest Entries...</h2>";
    if ($listStudentNumRows > 0) {
        while ($listStudentArr = $listStudentRes->fetch(PDO::FETCH_ASSOC)) {
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
		Copyright &copy IPRC Tumba - Quality Assurance Office. <br />
		<a href="http://iprctumba.rp.ac.rw/">Home</a> | <a href="http://iprctumba.rp.ac.rw/">Contact</a> 
	</div><!--End of Footer div-->
	<div class="clr"></div>
ABC;
    return $footerDivStr;
}

/* This function will plot the  department dropdown */

function plotMainDepartmentDropdown($deptSelVal = '', $ajaxEnabled='no', $subjectAjaxEnabled='no', $except = 0) {
    global $connPDO;
    $deptDropdownStr = '';
    if($except == 0){
        $deptQry = "SELECT * FROM `tbl_t_departments` ORDER BY `dpt_name`";
    } else{
        $deptQry = "SELECT * FROM `tbl_t_departments` WHERE `dpt_id` NOT LIKE '{$except}' ORDER BY `dpt_name`";
    }
    $deptRes = $connPDO->query($deptQry);
    $deptNumRows = $deptRes -> rowCount();
    if ($deptNumRows > 0) {
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
        while ($deptArr = $deptRes->fetch(PDO::FETCH_ASSOC)) {

            if ($deptArr['dpt_id'] == $deptSelVal) {
                $deptDropdownStr .= "<option value={$deptArr['dpt_id']} selected='selected'>{$deptArr['dpt_name']}</option>";
            } else {
                $deptDropdownStr .= "<option value={$deptArr['dpt_id']}>{$deptArr['dpt_name']}</option>";
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

/* This function will plot the Programs dropdown */

function plotDepartmentDropdown($deptSelVal = '', $ajaxEnabled='no', $subjectAjaxEnabled='no', $except = 0) {
    global $connPDO;
    $deptDropdownStr = '';
    if($except == 0){
        $deptQry = "SELECT * FROM `tbl_department` ORDER BY `department_name`";
    } else{
        $deptQry = "SELECT * FROM `tbl_department` WHERE `department_id` NOT LIKE '{$except}' ORDER BY `department_name`";
    }
    $deptRes = $connPDO->query($deptQry);
    $deptNumRows = $deptRes -> rowCount();
    if ($deptNumRows > 0) {
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
        while ($deptArr = $deptRes->fetch(PDO::FETCH_ASSOC)) {

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
    global $leveYyearArr; /* year array from config.php file */
    if (is_array($leveYyearArr)) {
        $yearStr = "<select name='year' id='year' class='typeproforms'>";
        $yearStr .= "<option value=''>--select--</option>";
        foreach ($leveYyearArr as $year => $levelYear) {
            if ($yearSelValue == $year) {
                $yearStr .= "<option value='{$yearSelValue}' selected='selected'>{$levelYear}</option>";
            } else {
                $yearStr .= "<option value='{$year}'>{$levelYear}</option>";
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
                $semesterStr .= "<option value='{$semester}' selected='selected'>Semester {$semester}</option>";
            } else {
                $semesterStr .= "<option value='{$semester}'>Semester {$semester}</option>";
            }
        }
        $semesterStr .= "</select>";
        return $semesterStr;
    }
}

/* This function will plot Class Group dropdown */

function plotGroupDropdown($groupSelValue = 0) {
    global $groupArr; /* year array from config.php file */
    global $groupArrValue; /* year array from config.php file */
    if (is_array($groupArr)) {
        $groupStr = "<select name='group' id='group' class='typeproforms'>";
        $groupStr .= "<option value=''>--select--</option>";
        foreach ($groupArr as $group) {
            if ($groupSelValue == $group) {
                $groupStr .= "<option value='{$groupSelValue}' selected='selected'>{$groupArrValue[$group-1]}</option>";
            } else {
                $groupStr .= "<option value='{$group}'>{$groupArrValue[$group-1]}</option>";
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
        $genderG .= "<option value=''>--Select Gender--</option>";
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
    } elseif (!(isset($_SESSION['u_uname']) && isset($_SESSION['u_pass']) && isset($_SESSION['u_utype']) /*&& $_SESSION['u_utype'] == 'admin'*/)) {
        header('Location:index.php?msg=invalid');
        exit;
    }
}

/* This function will check the dependency with the tables before delete the record */

function checkDependency($qryArr) {
    global $connPDO;
    if (is_array($qryArr)) {
        foreach ($qryArr as $name => $query) {
            $dependentSearchRes = $connPDO->query($query);
            $dependentSearchNumRows = $dependentSearchRes -> rowCount();
            if ($dependentSearchNumRows > 0) {
                $dependentArr = $dependentSearchRes->fetch(PDO::FETCH_ASSOC);
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
							<tr><td>Rwanda Polythechnic-IPRC Tumba,</td></tr>
							<tr><td>P.O. BOX:6638,Rulindo,</td></tr>
							<tr><td>Tel:(250)7845015114 /5/6,</td></tr>
							<tr><td>Northern Province,</td></tr>
							<tr><td>Website:www.iprctumba.rp.ac.rw</td></tr>
							</tr>
						</table>
					</tr>
				</table>
					<div style = 'color:#fff;font-weight:bold;font-size:1.3em;border:1px;background-color:#096bad;width:600px;'>
						<center>WELCOME TO IPRC TUMBA STUDENTS COURSE EVALUATION</center>
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
    global $connPDO;
    $Q = "UPDATE tbl_codes SET used = 1 WHERE code = '{$code}'";
    $connPDO->exec($Q);
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
							<tr><td>Rwanda Polytechnic-IPRC Tumba,</td></tr>
							<tr><td>P.O. BOX:6638,Rulindo,</td></tr>
							<tr><td>Tel:(250)7845015114 /5/6,</td></tr>
							<tr><td>Northern Province,</td></tr>
							<tr><td>Website:www.iprctumba.rp.ac.rw</td></tr>
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
    global $connPDO;
    $checkAlreadyExistFeedbackQry = "SELECT * FROM tbl_feedback WHERE
feedback_acc_year = '{$acc_year}' AND
feedback_dept_id = {$dept_id} AND
feedback_year= '{$year}' AND
feedback_semester = '{$semester}' AND
feedback_group= '{$group}' AND
feedback_sub_id = {$sub_id} AND
feedback_stud_id = {$stud_id}";

    $checkAlreadyExistFeedbackRes = $connPDO->query($checkAlreadyExistFeedbackQry);
    $checkAlreadyExistRows = $checkAlreadyExistFeedbackRes -> rowCount();

    if ($checkAlreadyExistRows > 0)
        return true;
    return false;
}

function saveFeedBack($acc_year, $dept_id, $year, $semester,$group, $sub_id, $stud_id, $teacher_id, $arr_qualities, $comment) {
    global $connPDO;
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


            $connPDO->exec($saveFeedbackQry);
            $_SESSION['has_made_fb'] = 'YES';
        }
    }

    if (isset($comment) && strlen($comment) > 0) {
        //$comment = htmlspecialchars($comment);
        //$comment = $connPDO->quote($comment);

        $saveCommentQry = "INSERT INTO tbl_comments set comment_value = '{$comment}',
        academic_year = '{$acc_year}', year = '{$year}', semester= '{$semester}', c_group='{$group}', department='{$dept_id}',subject= '{$sub_id}'";

        //die(stripslashes($saveCommentQry));
        $connPDO->exec($saveCommentQry);
        $_SESSION['hassavedsomething'] = "yes";
    }
}

function getFeedBack($acc_year, $sub_id) {
    global $connPDO;
    $selectFeedbackQry = "SELECT * FROM tbl_feedback WHERE feedback_acc_year = '{$acc_year}' AND feedback_sub_id = {$sub_id}";
    $selectFeedbackRes = $connPDO->query($selectFeedbackQry);

    //die($selectFeedbackQry);
}

function getAnswers() {
    global $connPDO;
    $listAnsQuery = "SELECT * FROM `tbl_answer` ORDER BY `answer_score` ASC";
    $listAnsRes = $connPDO->query($listAnsQuery);
    $listAnsNumRows = $listAnsRes->rowCount();

    if ($listAnsNumRows) {
        $listAnswerArr = array();
        while ($row = $listAnsRes->fetch(PDO::FETCH_ASSOC)) {
            $listAnswerArr[$row['answer_type']] = $row['answer_id'];
        }

        return $listAnswerArr;
    }
    return array();
}

function checkAllowedSubject($sid, $code) {
    global $connPDO;
    $info = getInfoFromCode($code);
    $department = $info ['department'];
    $year = $info['year'];
    $semester = $info ['semester'];

    $Q = "SELECT * FROM tbl_subject WHERE subject_id = '{$sid}' AND subject_department_id = '{$department}' AND subject_year = '{$year}' AND subject_semester = '{$semester}'";

    $res = $connPDO->query($Q);

    if ($res -> rowCount() > 0)
        return true;
    return false;
}

function plotFeedBackFormx($sid, $group, $tid, $code, $SubjAcYear) {

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
						<textarea name='comment' rows='2' cols='60' minlength='50' maxlength= '200' onkeyup='countRChars();'></textarea><?php //By Nepo ?>
                    </td>
                </tr>


            </tbody></table><br />
            <input type = 'hidden' name ='subject_id' value = '$sid' />
            <input type = 'hidden' name ='subject_teacher_id' value = '$tid' />
            <input type = 'hidden' name ='subject_group' value = '$group' />
            <input type = 'hidden' name ='subject_ac_year' value = '$SubjAcYear' />
               <input class='button' id='btnSave' name='btnSave' value='Submit Feedback' type='submit'>
                    
            </div></form>";
        return $s;
    }
    else
        return "";
}

function getQScore($acc_year, $sub_id, $qId) {
/*
Updated by Blaise Yuo-B 
Start
*/
    global $connPDO;
	$tstr = "SELECT MAX(answer_score) AS max_answer FROM tbl_answer";
	$qrst = $connPDO->query($tstr);
	$max_answer = 0;
	if ($row = $qrst->fetch(PDO::FETCH_ASSOC)){
		$max_answer = $row['max_answer'];
	}

    define("MAX_ANSWER", $max_answer);
/*
End
*/
//    define("MAX_ANSWER", 3); Codes by Dieudone San
    $q = "SELECT feedback_answer_id, answer_score FROM `tbl_feedback` INNER JOIN tbl_answer ON feedback_answer_id=answer_id WHERE `feedback_sub_id` = {$sub_id} AND `feedback_acc_year` LIKE '{$acc_year}%' AND `feedback_quality_id` = {$qId}";


    $rslt = $connPDO->query($q);
    $rno = $rslt->rowCount();

    $sum = 0;

    $count = 0;
    if ($rno) {

        while ($rec = $rslt->fetch(PDO::FETCH_ASSOC)) {
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
            $a = ceil(getQScore((int)$acc_year, $sid, $_k) * 10) / 10;
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
                         Grand Total: {$gdTotal}%
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
    global $connPDO;

    $_Q = "SELECT *  FROM `tbl_comments` WHERE `academic_year` = '{$acc_year}' AND `subject` = {$sid} ";

    $rslt = $connPDO->query($_Q);
    $rno = $rslt->rowCount();

    $comments = array();

    while ($rec = $rslt->fetch(PDO::FETCH_ASSOC)) {
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
    global $connPDO;
    $subjListRes = $connPDO->query($subjListQry);
    $subjListNumRows = $subjListRes -> rowCount();
    $subjListRes1 = $connPDO->query($subjListQry);
    $subjListNumRows1 = $subjListRes1 -> rowCount();
    $subjListRes2 = $connPDO->query($subjListQry);
    $subjListNumRows2 = $subjListRes2 -> rowCount();
    $subjListRes3 = $connPDO->query($subjListQry);
    $subjListNumRows3 = $subjListRes3 -> rowCount();
    $subjListRes4 = $connPDO->query($subjListQry);
    $subjListNumRows4 = $subjListRes4 -> rowCount();
    if ($subjListNumRows > 0) {
        echo "<form method='post' action='feedback.php'>";
        echo "<div style = 'color:red;font-weight:bold;font-size:1.3em;border:1px;background-color:#;width:600px;'>";
        echo "A. Module/course Content And organisation ratings";

        echo "</div>";
        echo "<br>";
        echo '<table id ="listEntries" width = "100%" border="1">';
        echo '<tr>';
        $AnswerCounter = 0;
        echo "<th>Qualities</th>";
        while ($subjListArr = $subjListRes->fetch(PDO::FETCH_ASSOC)) {
            $answerAll['subjectIdArr'] = $subjListArr['subject_id'];
            echo "<th>{$subjListArr['subject_name']}</th>";
            echo "<input type ='hidden' name='subject_id[]' value = '{$subjListArr['subject_id']}' />";
            $AnswerCounter++;
        }
        echo '</tr>';
        $qualListQry = "SELECT * FROM  `tbl_quality`";
        $qualListRes = $connPDO->query($qualListQry);
        $qualListNumRows = $qualListRes -> rowCount();
        $qualListQry1 = "SELECT * FROM  `tbl_quality` where Title = 'Student contribution and ratings'";
        $qualListRes1 = $connPDO->query($qualListQry1);
        $qualListNumRows1 = $qualListRes1 -> rowCount();
        $qualListQry2 = "SELECT * FROM  `tbl_quality` where Title = 'Learning environment and teaching'";
        $qualListRes2 = $connPDO->query($qualListQry2);
        $qualListNumRows2 = $qualListRes2 -> rowCount();
        $qualListQry3 = "SELECT * FROM  `tbl_quality` where Title = 'Overall Experience'";
        $qualListRes3 = $connPDO->query($qualListQry3);
        $qualListNumRows3 = $qualListRes3 -> rowCount();
        $qualListQry4 = "SELECT * FROM  `tbl_quality` where Title = 'Teacher ratings'";
        $qualListRes4 = $connPDO->query($qualListQry4);
        $qualListNumRows4 = $qualListRes4 -> rowCount();

        if ($qualListNumRows) {
            while ($qualListArr = $qualListRes->fetch(PDO::FETCH_ASSOC)) {

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
    global $connPDO;
    $answerListQry = "SELECT * FROM  `tbl_answer`";
    $answerListRes = $connPDO->query($answerListQry);
    $answerListNumRows = $answerListRes->rowCount();
    if ($answerListNumRows > 0) {
        $answerDropdownStr = "<select name='answer[]' id = 'answer' class='typeproforms' style='color:red;'>";
//$answerDropdownStr  .=	"<option>Select here</option>";
        echo "<font style='color:red;'>";
        $k = "--Select Here--";
        echo "</font>";
        $answerDropdownStr .= "<option value=\"Select\" style='color:red;'>$k</option>";

        if ($answerListNumRows) {
            while ($answerListArr = $answerListRes->fetch(PDO::FETCH_ASSOC)) {

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
    global $connPDO;
    $dropdownRes = $connPDO->query($dropdownQry);
    $dropdownRows = $dropdownRes -> rowCount();

    if ($subjectAjaxEnabled == 'yes') {
        $dropdownStr = "<select name='{$dropdownName}' id='{$dropdownName}' class='typeproforms'
								onchange='javascript:plotSubjectByDeptForHistory(\"\")'>";
        $dropdownStr .= "<option value=''>--select--</option>";
    } else {
        $dropdownStr = "<select name='{$dropdownName}' id='{$dropdownName}' class='typeproforms'>";
        $dropdownStr .= "<option value=''>--select--</option>";
    }
    if ($dropdownRows > 0) {
        while ($dropdownArr = $dropdownRes -> fetch(PDO::FETCH_NUM)) {
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
    global $connPDO;
    $criteria = getCriteria();
    $qualities = NULL;
    foreach ($criteria as $_vcriteria) {
        $sql = "SELECT * FROM  `tbl_quality` where Title = '{$_vcriteria}' ";
        $_rcriteria = $connPDO->query($sql);

        while ($row = $_rcriteria -> fetch(PDO::FETCH_ASSOC)) {
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
        "Learning environment preparation",
        "Module/Course content organization",
        "Students contribution",
        "Teaching and learning",
        "Assessment",
        "Quality of Delivery",
        "Student Overall experience",
        "Teacher ratings"
    );

    return $criteria;
}

function hasEvaluationData($sub_id, $acc_year) {
    global $connPDO;
    $Q = "SELECT * FROM `tbl_feedback` WHERE `feedback_sub_id` = {$sub_id} AND `feedback_acc_year` LIKE '{$acc_year}%' ";


    $rslt = $connPDO->query($Q);
    $rno = $rslt -> rowCount();


    return $rno > 0 ? TRUE : FALSE;
}
/**
 * New updated functions @Blaise
 * --------------------------------
 */
function getDepartmentRow($sem, $year){
    global $connPDO;
    $sql = $connPDO->query("SELECT * FROM tbl_department WHERE department_id NOT LIKE 1 ORDER BY department_id");
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        $dep_id = $row['department_id'];
        ?>
        <tr>
            <td><?php echo $row['department_name'];?></td>
            <?php
            plotLevelFiels($dep_id, $sem, $year);
            ?>
        </tr>
        <?php
    }
}
function plotLevelFiels($dpt, $sem, $year){ 
    global $connPDO;
    for ($nu=1; $nu <= 3 ; $nu++) { 
        $prep = $connPDO->query("SELECT * FROM tbl_levels WHERE level_department = {$dpt} AND level_year = {$nu}");
        $levData = $prep -> fetch(PDO::FETCH_ASSOC);
        if ($prep -> rowCount() == 0) {
            ?>
            <td>No Class</td>
            <?php
        }else{
            $lv = $levData['level_id'];
            $yr = $levData['level_year'];
            ?>
            <td><?php plotClassGroups($lv, $nu, $dpt, $sem, $year); ?></td>
            <?php
        }
    }
}
function plotClassGroups($level, $numb, $depart, $sem, $year){
    global $connPDO;
    $groupArrValue = array("A", "B", "C", "D");
    $preQuery = $connPDO->query("SELECT `tbl_department`.`department_id`, `tbl_department`.`department_code` dpcode, `tbl_department`.`department_name`, `tbl_classes`.`class_id`, `tbl_classes`.`class_group` gp, `tbl_classes`.`class_level`, `tbl_levels`.`level_id`, `tbl_levels`.`level_year`, `tbl_levels`.`level_department` FROM `tbl_classes`, `tbl_levels`, `tbl_department` WHERE `tbl_classes`.`class_level` = {$level} AND `tbl_classes`.`class_level` = `tbl_levels`.`level_id` AND `tbl_levels`.`level_department` = `tbl_department`.`department_id`");
    if ($preQuery -> rowCount() == 0) {
        echo "No Class";
    } else {
        while ($grpData = $preQuery -> fetch(PDO::FETCH_ASSOC)) {
            $gp  = $grpData['gp'];
            $dpcode = strtolower($grpData['dpcode']);
            echo "<a target='blank' href='generate-pdf-from-mysql-database-using-php-master/?dep=$depart&level=$level&group=$gp&sem=$sem&yr=$year'>Class ".$groupArrValue[$gp-1]."</a><br>";
        }
    }
    
}
function selectDepartment() {
    global $connPDO;
    $listDeptQuery = "SELECT * FROM `tbl_department` ORDER BY department_name ASC";
    $listDeptRes = $connPDO->query($listDeptQuery);
    $listDeptNumRows = $listDeptRes->rowCount();
    ?>
    <select name="department">
        <option selected hidden disabled>--Department--</option>
    <?php
        while ($listDeptArr = $listDeptRes -> fetch(PDO::FETCH_ASSOC)) {
            ?>
            <option value="<?php echo $listDeptArr['department_id'];?>"><?php echo $listDeptArr['department_name'];?></option>
            <?php
        }
    ?>
    </select>
    <?php
}
function selectLevelOption() {
    global $connPDO;
    $listLvlQuery = "SELECT  `tbl_department`.`department_id`, `tbl_department`.`department_name` departName, department_code departCode, `tbl_levels`.`level_id` l_id,`tbl_levels`.`level_year` year, `tbl_levels`.`level_department` FROM `tbl_department`, `tbl_levels` WHERE `tbl_levels`.`level_department` = `tbl_department`.`department_id` ORDER BY `tbl_department`.`department_name`,`tbl_levels`.`level_year` ASC";
    $listLvlRes = $connPDO->query($listLvlQuery);
    $listLvlNumRows = $listLvlRes -> rowCount();
    ?>
    <select name="level">
        <option selected hidden disabled>--Select Level--</option>
    <?php
        while ($listLvlArr = $listLvlRes -> fetch(PDO::FETCH_ASSOC)) {
            ?>
            <option value="<?php echo $listLvlArr['l_id'];?>"><?php echo $listLvlArr['departCode']. " Level ".$listLvlArr['year'];?></option>
            <?php
        }
    ?>
    </select>
    <?php
}
function selectLevel(){
    $levels = array("L6 Year 1", "L6 Year 2", "L7 Year 3");
    for ($arrData=0; $arrData < sizeof($levels); $arrData++) {
        $nm =  $arrData+1;
        echo "<option value ='$nm'>$levels[$arrData]</option>";
    }
}
function selectGroup(){
    $groups = array("A", "B", "C", "D");
    for ($arrData=0; $arrData < sizeof($groups); $arrData++) {
        $nm =  $arrData+1;
        echo "<option value ='$nm'>$groups[$arrData]</option>";
    }
}
function listLevel() {
    global $connPDO;
    $listLevelQuery = "SELECT  `tbl_department`.`department_id`, `tbl_department`.`department_name` departName, `tbl_levels`.`level_id` l_id,`tbl_levels`.`level_year` year, `tbl_levels`.`level_department` FROM `tbl_department`, `tbl_levels` WHERE `tbl_levels`.`level_department` = `tbl_department`.`department_id` ORDER BY `tbl_levels`.`level_id` DESC LIMIT 0,3";
    $listLevelRes = $connPDO->query($listLevelQuery);
    $listLevelNumRows = $listLevelRes -> rowCount();
    $listLevelStr = "<div class='side_body shadowEffect'>
                            <h2>Latest Entries...</h2>";
    if ($listLevelNumRows) {
        while ($listLevelArr = $listLevelRes -> fetch(PDO::FETCH_ASSOC)) {
            $listLevelStr .= <<<ABC
                <div class='title'>
                    <a href = "#.php?id={$listLevelArr['l_id']}">
                    {$listLevelArr['departName']} Year {$listLevelArr['year']}
                    </a>
                </div>
                <div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
                </div>
                <br/>
ABC;
        }
    } else {
        $listLevelStr .= "<p><span class= 'error'>No Level(s)</span></p>";
    }
    $listLevelStr .= '</div><!--End Of side body Div-->';
    return $listLevelStr;
}
function listGroup() {
    global $connPDO;
    $listGroupQuery = "SELECT  `tbl_department`.`department_id`, `tbl_department`.`department_code` departCode, `tbl_levels`.`level_id` l_id,`tbl_levels`.`level_year` year, `tbl_levels`.`level_department`, `tbl_classes`.`class_id` g_id, `tbl_classes`.`class_group` groupe, `tbl_classes`.`class_level` FROM `tbl_department`, `tbl_levels`, `tbl_classes` WHERE `tbl_classes`.`class_level` = `tbl_levels`.`level_id` AND `tbl_levels`.`level_department` = `tbl_department`.`department_id` ORDER BY `tbl_classes`.`class_id` DESC LIMIT 0,3";
    $listGroupRes = $connPDO->query($listGroupQuery);
    $listGroupNumRows = $listGroupRes->rowCount();
    $listGroupStr = "<div class='side_body shadowEffect'>
                             <h2>Latest Entries...</h2>";
    if ($listGroupNumRows) {
        $groupArrValue = array("A", "B", "C", "D");
        while ($listGroupArr = $listGroupRes->fetch(PDO::FETCH_ASSOC)) {
            $groupLetter = $groupArrValue[$listGroupArr['groupe']-1];
            $listGroupStr .= <<<ABC
                <div class='title'>
                    <a href = "#?id={$listGroupArr['g_id']}">
                    {$listGroupArr['departCode']} Level {$listGroupArr['year']} {$groupLetter}
                    </a>
                </div>
                <div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
                </div>
                <br/>
ABC;
        }
    } else {
        $listGroupStr .= "<p><span class= 'error'>No Group(s)</span></p>";
    }
    $listGroupStr .= '</div><!--End Of side body Div-->';
    return $listGroupStr;
}

function levelGroupLinks(){
    echo "<br><div>";
    echo "<a href='level_new.php'>Manage Levels</a>&nbsp|&nbsp";
    echo "<a href='group_new.php'>Manage Groups</a>";
    echo "</div>";
}   

/* This function will plot the department dropdown */

function plotTeacherDepartmentDropdown($deptSelVal = '', $ajaxEnabled='no', $subjectAjaxEnabled='no', $tchrID=0) {
    global $connPDO;
    $deptDropdownStr = '';
    $deptQry = "SELECT * FROM `tbl_t_departments` ORDER BY `dpt_name`";
    $deptRes = $connPDO->query($deptQry);
    $deptNumRows = $deptRes -> rowCount();
    if ($deptNumRows) {
        if ($subjectAjaxEnabled == 'yes') {
            $deptDropdownStr = "<select name='teacherdepartment' id='department' class='typeproforms'
                                onchange='javascript:plotSubjectByDept(\"\")'>";
        } elseif ($ajaxEnabled == 'yes') {
            $deptDropdownStr = "<select name='teacherdepartment' id='department' class='typeproforms'
                                onchange='javascript:plotTeacherByDept(this.value, {$tchrID})'>";
        } else {
            $deptDropdownStr = "<select name='teacherdepartment' id='department' class='typeproforms'>";
        }
        $deptDropdownStr .= "<option value=''>--select Department--</option>";
        while ($deptArr = $deptRes -> fetch(PDO::FETCH_ASSOC)) {

            if ($deptArr['dpt_id'] == $deptSelVal) {
                $deptDropdownStr .= "<option value={$deptArr['dpt_id']} selected='selected'>{$deptArr['dpt_name']}</option>";
            } else {
                $deptDropdownStr .= "<option value={$deptArr['dpt_id']}>{$deptArr['dpt_name']}</option>";
            }
        }
        $deptDropdownStr .='</select>';
    } else {
        $deptDropdownStr = "<select name='teacherdepartment' id='department' class='typeproforms'>
                                <option value = ''>--select--</option>
                                </select>";
    }
    return $deptDropdownStr;
}

// Function do displaye users
function listUsers() {
    global $connPDO;
    $listUsersQuery = "SELECT *  FROM `tbl_users` WHERE `u_utype` NOT LIKE 'admin' ORDER BY u_id DESC LIMIT 0,3";
    $listUsersRes = $connPDO->query($listUsersQuery);
    $listUsersNumRows = $listUsersRes -> rowCount();
    $listUsersStr = "<div class='side_body shadowEffect'>
                                <h2>Latest Entries...</h2>";
    if ($listUsersNumRows) {
        while ($listUsersArr = $listUsersRes -> fetch(PDO::FETCH_ASSOC)) {
            $listUsersStr .= <<<ABC
                <div class='title'>
                    <a href = "users_edit.php?id={$listUsersArr['u_id']}">
                        {$listUsersArr['u_lname']}  {$listUsersArr['u_fname']}
                    </a>
                </div>
                <div class='clr' style='border-bottom:1px solid #CCCCCC;margin-bottom:5px;'>
                </div>
                <br/>   
                
ABC;
        }
    } else {
        $listUsersStr .= "<p><span class= 'error'>No Student(s)</span></p>";
    }
    $listUsersStr .= '</div><!--End Of side body Div-->';
    return $listUsersStr;
}
function plotUserTypeDropdown($userTypeSelValue = '') {
    global $userTpyeArr; /* year array from config.php file */
    global $colUserTpyeArr; /* year array from config.php file */
    if ($_SESSION['u_utype'] == "admin") {
        if (is_array($userTpyeArr)) {
            $typeG = "<select name='u_utype' id='u_utype' class='typeproforms'>";
            $typeG .= "<option value='' selected disabled hidden>--Select Type--</option>";
            foreach ($userTpyeArr as $utype) {
                if ($userTypeSelValue == $utype) {
                    $typeG .= "<option value='{$userTypeSelValue}' selected='selected'>{$utype}</option>";
                } else {
                    $typeG .= "<option value='{$utype}'>{$utype}</option>";
                }
            }
            $typeG .= "</select>";
            return $typeG;
        }
    } else{
        if (is_array($colUserTpyeArr)) {
            $typeG = "<select name='u_utype' id='u_utype' class='typeproforms'>";
            $typeG .= "<option value='' selected disabled hidden>--Select Type--</option>";
            foreach ($colUserTpyeArr as $utypename => $utype) {
                if ($userTypeSelValue == $utype) {
                    $typeG .= "<option value='{$userTypeSelValue}' selected='selected'>{$utypename}</option>";
                } else {
                    $typeG .= "<option value='{$utype}'>{$utypename}</option>";
                }
            }
            $typeG .= "</select>";
            return $typeG;
        }
    }
}
?>