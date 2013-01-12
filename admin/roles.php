<?php 
include("../assets/php/database.php"); 
include("../assets/php/class.acl.php");
$myACL = new ACL();
if (isset($_POST['action']))
{
	switch($_POST['action'])
	{
		case 'saveRole':
			$strSQL = sprintf("REPLACE INTO `roles` SET `ID` = %u, `roleName` = '%s'",$_POST['roleID'],$_POST['roleName']);
			mysql_query($strSQL);
			if (mysql_affected_rows() > 1)
			{
				$roleID = $_POST['roleID'];
			} else {
				$roleID = mysql_insert_id();
			}
			foreach ($_POST as $k => $v)
			{
				if (substr($k,0,5) == "perm_")
				{
					$permID = str_replace("perm_","",$k);
					if ($v == 'X')
					{
						$strSQL = sprintf("DELETE FROM `role_perms` WHERE `roleID` = %u AND `permID` = %u",$roleID,$permID);
						mysql_query($strSQL);
						continue;
					}
					$strSQL = sprintf("REPLACE INTO `role_perms` SET `roleID` = %u, `permID` = %u, `value` = %u, `addDate` = '%s'",$roleID,$permID,$v,date ("Y-m-d H:i:s"));
					mysql_query($strSQL);
				}
			}
			header("location: roles.php");
		break;
		case 'delRole':
			$strSQL = sprintf("DELETE FROM `roles` WHERE `ID` = %u LIMIT 1",$_POST['roleID']);
			mysql_query($strSQL);
			$strSQL = sprintf("DELETE FROM `user_roles` WHERE `roleID` = %u",$_POST['roleID']);
			mysql_query($strSQL);
			$strSQL = sprintf("DELETE FROM `role_perms` WHERE `roleID` = %u",$_POST['roleID']);
			mysql_query($strSQL);
			header("location: roles.php");
		break;
	}
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
    	<h2>Select a Role to Manage:</h2>
        <? 
		$roles = $myACL->getAllRoles('full');
		foreach ($roles as $k => $v)
		{
			echo "<a href=\"?action=role&roleID=" . $v['ID'] . "\">" . $v['Name'] . "</a><br />";
		}
		if (count($roles) < 1)
		{
			echo "No roles yet.<br />";
		} ?>
        <input type="button" name="New" value="New Role" onclick="window.location='?action=role'" />
    <? } 
    if ($_GET['action'] == 'role') { 
		if ($_GET['roleID'] == '') { 
		?>
    	<h2>New Role:</h2>
        <? } else { ?>
    	<h2>Manage Role: (<?= $myACL->getRoleNameFromID($_GET['roleID']); ?>)</h2><? } ?>
        <form action="roles.php" method="post">
        	<label for="roleName">Name:</label><input type="text" name="roleName" id="roleName" value="<?= $myACL->getRoleNameFromID($_GET['roleID']); ?>" />
            <table border="0" cellpadding="5" cellspacing="0">
            <tr><th></th><th>Allow</th><th>Deny</th><th>Ignore</th></tr>
            <? 
            $rPerms = $myACL->getRolePerms($_GET['roleID']);
            $aPerms = $myACL->getAllPerms('full');
            foreach ($aPerms as $k => $v)
            {
                echo "<tr><td><label>" . $v['Name'] . "</label></td>";
                echo "<td><input type=\"radio\" name=\"perm_" . $v['ID'] . "\" id=\"perm_" . $v['ID'] . "_1\" value=\"1\"";
                if ($rPerms[$v['Key']]['value'] === true && $_GET['roleID'] != '') { echo " checked=\"checked\""; }
                echo " /></td>";
                echo "<td><input type=\"radio\" name=\"perm_" . $v['ID'] . "\" id=\"perm_" . $v['ID'] . "_0\" value=\"0\"";
                if ($rPerms[$v['Key']]['value'] != true && $_GET['roleID'] != '') { echo " checked=\"checked\""; }
                echo " /></td>";
				echo "<td><input type=\"radio\" name=\"perm_" . $v['ID'] . "\" id=\"perm_" . $v['ID'] . "_X\" value=\"X\"";
                if ($_GET['roleID'] == '' || !array_key_exists($v['Key'],$rPerms)) { echo " checked=\"checked\""; }
                echo " /></td>";
                echo "</tr>";
            }
        ?>
    	</table>
    	<input type="hidden" name="action" value="saveRole" />
        <input type="hidden" name="roleID" value="<?= $_GET['roleID']; ?>" />
    	<input type="submit" name="Submit" value="Submit" />
    </form>
    <form action="roles.php" method="post">
         <input type="hidden" name="action" value="delRole" />
         <input type="hidden" name="roleID" value="<?= $_GET['roleID']; ?>" />
    	<input type="submit" name="Delete" value="Delete" />
    </form>
    <form action="roles.php" method="post">
    	<input type="submit" name="Cancel" value="Cancel" />
    </form>
    <? } ?>
</div>
</body>
</html>