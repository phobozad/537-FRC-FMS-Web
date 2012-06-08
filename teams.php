<?php
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$teamNum=mysql_real_escape_string($_REQUEST['teamNum']);
	$teamName=mysql_real_escape_string($_REQUEST['teamName']);
	$hometown=mysql_real_escape_string($_REQUEST['hometown']);
	$school=mysql_real_escape_string($_REQUEST['school']);
	$rookie=mysql_real_escape_string($_REQUEST['rookie']);
	$sponsers=mysql_real_escape_string($_REQUEST['sponsers']);
	$action=$_REQUEST['action'];
	
	if($action=='update')
	{			
		$qry="INSERT INTO team (teamNum,teamName,teamHometown,teamSchool,teamRookieYear,teamSponsers)
		VALUES ('$teamNum','$teamName','$hometown','$school','$rookie','$sponsers') ON DUPLICATE KEY UPDATE 
		teamNum='$teamNum',teamName='$teamName',teamHometown='$teamHometown',teamSchool='$teamSchool',teamRookieYear='$teamRookieYear',teamSponsers='$teamSponsers';";
		$res=mysql_query($qry);
		if(!$res){$loadErrors.=mysql_error().'<br>';}
	}
	elseif($action=='delete')
	{
		$qry="DELETE FROM team where teamNum='$teamNum'";
		$res=mysql_query($qry);
		if(!$res){$loadErrors.=mysql_error().'<br>';}
		$teamNum="";
	}
	if($teamNum<>"")
	{
		$qry="SELECT teamName, teamHometown, teamRookieYear, teamSponsers, teamSchool FROM team WHERE teamNum='$teamNum'";
		$res=mysql_query($qry);
		if(!$res){$loadErrors.=mysql_error().'<br>';}
		else
		{
			$data=mysql_fetch_array($res);
			$teamName=$data['teamName'];
			$teamHometown=$data['teamHometown'];
			$teamRookieYear=$data['teamRookieYear'];
			$teamSponsers=$data['teamSponsers'];
			$teamSchool=$data['teamSchool'];
		}
	}
?>
<html>
<head>
<script type='text/javascript'>
function del(teamnum)
{
	if(confirm("Really delete team " + teamnum))
		window.location.replace(window.location.pathname+'?action=delete&teamNum='+teamnum);	
}
function edit(teamnum)
{
	window.location.replace(window.location.pathname+'?action=edit&teamNum='+teamnum);	
}
</script>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Add Team</title>
</head>
<body>
<?include 'header.php'?>
<div id='title'>Teams</div>
<form method="post">
Team Number: <input name="teamNum" type="text" value="<?=$teamNum?>"><br>
Team Name: <input name="teamName" type="text" value="<?=$teamName?>"><br>
Hometown: <input name="teamHometown" type="text" value="<?=$teamHometown?>"><br>
School: <input name="teamSchool" type="text" value="<?=$teamSchool?>"><br>
Rookie Year: <input name="teamRookieYear" type="text" value="<?=$teamRookieYear?>"><br>
Sponsers: <input name="teamSponsers" type="text" value="<?=$teamSponsers?>">
<input type="hidden" name="action" value="update">
<br><br><input type="submit" value="Add/Save"><input type="button" value="New" onclick="edit('')">
</form>
<table border="1" id='teamtable'>
<caption>Teams in DB</caption>
<tr><th>Team #</th><th>Name</th>
<?php
	$qry="SELECT teamNum,teamName FROM team";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	else
	{
		while(($curRow=mysql_fetch_array($res)))
		{
			echo "<tr><td>$curRow[teamNum]</td><td>$curRow[teamName]</td><td><a href='javascript:edit($curRow[teamNum])'>edit</a><td><a href='javascript:del($curRow[teamNum])'>x</a></tr>";
		}
	}
?>
</table>
<?include 'footer.php';?>
</body>
</html>
