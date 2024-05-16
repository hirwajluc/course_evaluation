<?php
	$adminUserName			=	'admin';
	$adminPassword			=	'adminsecretkey';
	$adminSecurePassword	=	md5($adminPassword);
	
	$adminInsertQry			=	"INSERT INTO `tbl_users` (`u_id`, `u_fname`, `u_lname`, `u_uname`, `u_pass`, `u_utype`, `stud_year`, `stud_dept`, `stud_sem`, `stud_regno`) VALUES (NULL, 'admin', 'admin', '{$adminUserName}', '{$adminSecurePassword}', 'admin', NULL, NULL, NULL, NULL)";
	echo $adminInsertQry;
?>