<?php
include("../assets/php/database.php");
include("../assets/php/class.acl.php");
$myACL = new ACL();
if ($myACL->hasPermission('access_admin') != true)
{
	header("location: ../index.php?no_perm");
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ACL Test</title>
<link href="../assets/css/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header"></div>
<div id="adminButton"><a href="../">Main Screen</a></div>
<div id="page">
    <h2>Select an Admin Function:</h2>
    <a href="users.php">Manage Users</a><br />
    <a href="roles.php">Manage Roles</a><br />
    <a href="perms.php">Manage Permissions</a><br />
</div>
</body>
</html>