<?php 
include("assets/php/database.php"); 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ACL Test</title>
<link href="assets/css/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header"></div>
<div id="page">
	<h2>Options:</h2>
    <form action="install.php" method="post">
        <label for="samples">Install Sample Data:</label><input type="checkbox" name="samples" id="samples" value="1" /><br />
        <input type="submit" name="go" value="Go!" />
    </form>
</div>
</body>
</html>
<?php
function parse_mysql_dump($url){
	$file_content = file($url);
	foreach($file_content as $sql_line){
		if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
			//echo $sql_line . '<br>';
			mysql_query($sql_line);
		}
	}
}
if (isset($_POST['go']))
{
	parse_mysql_dump("install.sql");
	if ($_POST['samples'] == '1')
	{
		parse_mysql_dump("sampleData.sql");
	}
	header("location: index.php");
}
?>