<?php 
include("assets/php/database.php"); 
include("assets/php/class.acl.php");

$userID = $_SESSION['userID'];
$userID = (isset($_GET['userID']) && $_GET['userID'] > 0 ? $_GET['userID'] : $userID);
$_SESSION['userID'] = $userID;
$myACL = new ACL();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ACL Test</title>
<link href="assets/css/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header"></div>
<div id="adminButton"><a href="admin/">Admin Page</a></div>
<div id="page">
	<?php if(isset($_GET['no_perm'])): ?>
	<div class="error">You don't have access to Admin Page</div>
	<?php endif; ?>
	
	<h2>Permissions for <?= $myACL->getUsername($userID); ?>:</h2>
	<? 
		$userACL = new ACL($userID);
		$aPerms = $userACL->getAllPerms('full');
		foreach ($aPerms as $k => $v)
		{
			echo "<strong>" . $v['Name'] . ": </strong>";
			echo "<img src=\"assets/img/";
			if ($userACL->hasPermission($v['Key']) === true)
			{
				echo "allow.png";
				$pVal = "Allow";
			} else {
				echo "deny.png";
				$pVal = "Deny";
			}
			echo "\" width=\"16\" height=\"16\" alt=\"$pVal\" /><br />";
		}
	?>
    <h3>Change User:</h3>
    <? 
		$strSQL = "SELECT * FROM `users` ORDER BY `Username` ASC";
		$data = mysql_query($strSQL);
		while ($row = mysql_fetch_assoc($data))
		{
			echo "<a href=\"?userID=" . $row['ID'] . "\">" . $row['username'] . "</a><br />";
		}
    ?>
</div>
</body>
</html>