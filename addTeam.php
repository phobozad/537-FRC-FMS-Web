<?php
require 'dbinfo.php';

if($_REQUEST['action']=='add')
{	
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$teamNum=mysql_real_escape_string($_REQUEST['teamNum']);
	$teamName=mysql_real_escape_string($_REQUEST['teamName']);
	$hometown=mysql_real_escape_string($_REQUEST['hometown']);
	$school=mysql_real_escape_string($_REQUEST['school']);
	$rookie=mysql_real_escape_string($_REQUEST['rookie']);
	$sponsers=mysql_real_escape_string($_REQUEST['sponsers']);
	
	$qry="INSERT INTO team (teamNum,teamName,teamHometown,teamSchool,teamRookieYear,teamSponsers) VALUES ('$teamNum','$teamName','$hometown','$school','$rookie','$sponsers')";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
}
?>
<html>
<head>
<title>Add Team</title>
</head>
<body>
<h1>Add Team</h1>
<form method="post">
Team Number: <input name="teamNum" type="text"><br>
Team Name: <input name="teamName" type="text"><br>
Hometown: <input name="hometown" type="text"><br>
School: <input name="school" type="text"><br>
Rookie Year: <input name="rookie" type="text"><br>
Sponsers: <input name="sponsers" type="text"><br>
<input type="hidden" name="action" value="add">
<input type="submit" value="Add Team">
</form>
<table border="1">
<caption>Teams in DB</caption>
<tr><th>Team #</th><th>Name</th>
<?php
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$qry="SELECT teamNum,teamName FROM team";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	else
	{
		while(($curRow=mysql_fetch_array($res)))
		{
			echo "<tr><td>$curRow[teamNum]</td><td>$curRow[teamName]</td><td><a href='editTeam.php?teamNum=$curRow[teamNum]'>edit</a></tr>";
		}
	}
?>
</table>
</body>
</html>
