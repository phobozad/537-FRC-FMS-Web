<?php
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$teamNum=mysql_real_escape_string($_REQUEST['teamNum']);
	if($_REQUEST['action']=='update')
	{
		$teamName=mysql_real_escape_string($_REQUEST['teamName']);
		$teamHometown=mysql_real_escape_string($_REQUEST['teamHometown']);
		$teamRookieYear=mysql_real_escape_string($_REQUEST['teamRookieYear']);
		$teamSponsers=mysql_real_escape_string($_REQUEST['teamSponsers']);
		$teamSchool=mysql_real_escape_string($_REQUEST['teamSchool']);
		$qry="UPDATE team SET teamName='$teamName', teamHometown='$teamHometown', teamRookieYear='$teamRookieYear', teamSponsers='$teamSponsers', teamSchool='$teamSchool' WHERE teamNum='$teamNum'";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	}
	
	$qry="SELECT teamName, teamHometown, teamRookieYear, teamSponsers, teamSchool FROM team WHERE teamNum='$teamNum'";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	else
	{
		$data=mysql_fetch_array($res);
		$teamName=$data['teamName'];
		$teamHometown=$data['teamHometown'];
		$teamRookieYear=$data['teamRookieYear'];
		$teamSponsers=$data['teamSponsers'];
		$teamSchool=$data['teamSchool'];
	}
	
?>
<html>
<head>
<title>Edit Team</title>
</head>
<body>
<h1>Edit Team <?=$teamNum?></h1>
<form method="post">
Team Number: <input name="teamNum" type="text" value="<?=$teamNum?>"><br>
Team Name: <input name="teamName" type="text" value="<?=$teamName?>"><br>
Hometown: <input name="teamHometown" type="text" value="<?=$teamHometown?>"><br>
School: <input name="teamSchool" type="text" value="<?=$teamSchool?>"><br>
Rookie Year: <input name="teamRookieYear" type="text" value="<?=$teamRookieYear?>"><br>
Sponsers: <input name="teamSponsers" type="text" value="<?=$teamSponsers?>"><br>
<input type="hidden" name="action" value="update">
<input type="submit" value="Save">
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
<?php include 'footer.php';?>
</body>
</html>