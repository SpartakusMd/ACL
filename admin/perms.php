<?php 
include("../assets/php/database.php"); 
include("../assets/php/class.acl.php");
$myACL = new ACL();
if (isset($_POST['action']))
{
	switch($_POST['action'])
	{
		case 'savePerm':
			$strSQL = sprintf("REPLACE INTO `permissions` SET `ID` = %u, `permName` = '%s', `permKey` = '%s'",$_POST['permID'],$_POST['permName'],$_POST['permKey']);
			mysql_query($strSQL);
		break;
		case 'delPerm':
			$strSQL = sprintf("DELETE FROM `permissions` WHERE `ID` = %u LIMIT 1",$_POST['permID']);
			mysql_query($strSQL);
		break;
	}
	header("location: perms.php");
}
if ($myACL->hasPermission('access_admin') != true)
{
	header("location: ../index.php");
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
<div id="adminButton"><a href="../">Main Screen</a> | <a href="index.php">Admin Home</a></div>
<div id="page">
	<? if ($_GET['action'] == '') { ?>
    	<h2>Select a Permission to Manage:</h2>
        <? 
		$roles = $myACL->getAllPerms('full');
		foreach ($roles as $k => $v)
		{
			echo "<a href=\"?action=perm&permID=" . $v['ID'] . "\">" . $v['Name'] . "</a><br />";
		}
		if (count($roles) < 1)
		{
			echo "No permissions yet.<br />";
		} ?>
        <input type="button" name="New" value="New Permission" onclick="window.location='?action=perm'" />
    <? } 
    if ($_GET['action'] == 'perm') { 
		if ($_GET['permID'] == '') { 
		?>
    	<h2>New Permission:</h2>
        <? } else { ?>
    	<h2>Manage Permission: (<?= $myACL->getPermNameFromID($_GET['permID']); ?>)</h2><? } ?>
        <form action="perms.php" method="post">
        	<label for="permName">Name:</label><input type="text" name="permName" id="permName" value="<?= $myACL->getPermNameFromID($_GET['permID']); ?>" maxlength="30" /><br />
            <label for="permKey">Key:</label><input type="text" name="permKey" id="permKey" value="<?= $myACL->getPermKeyFromID($_GET['permID']); ?>" maxlength="30" /><br />
    	<input type="hidden" name="action" value="savePerm" />
        <input type="hidden" name="permID" value="<?= $_GET['permID']; ?>" />
    	<input type="submit" name="Submit" value="Submit" />
    </form>
    <form action="perms.php" method="post">
         <input type="hidden" name="action" value="delPerm" />
         <input type="hidden" name="permID" value="<?= $_GET['permID']; ?>" />
    	<input type="submit" name="Delete" value="Delete" />
    </form>
    <form action="perms.php" method="post">
    	<input type="submit" name="Cancel" value="Cancel" />
    </form>
    <? } ?>
</div>
</body>
</html>