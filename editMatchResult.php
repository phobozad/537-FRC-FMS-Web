<?php
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$matchNum=mysql_real_escape_string($_REQUEST['matchNum']);
	$matchType=mysql_real_escape_string($_REQUEST['matchType']);

	if($_REQUEST['action']=='update')
	{
		$redRawScore=mysql_real_escape_string($_REQUEST['redRawScore']);
		$blueRawScore=mysql_real_escape_string($_REQUEST['blueRawScore']);
		$redBonus=mysql_real_escape_string($_REQUEST['redBonus']);
		$blueBonus=mysql_real_escape_string($_REQUEST['blueBonus']);
		$matchComplete=mysql_real_escape_string($_REQUEST['matchComplete']);

		$qry="INSERT INTO matchresult 
		(matchNum, matchType, redRawScore, blueRawScore, redBonus, blueBonus) VALUES 
		('$matchNum', '$matchType', '$redRawScore', '$blueRawScore', '$redBonus', '$blueBonus') ON DUPLICATE KEY UPDATE 
		redRawScore='$redRawScore', blueRawScore='$blueRawScore', redBonus='$redBonus', blueBonus='$blueBonus';";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
		
		$qry="UPDATE matchtime SET matchComplete='$matchComplete' WHERE matchNum='$matchNum' AND matchType='$matchType'";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	}
		
	switch($matchType)
	{
		case "P":
			$matchTypeLong="Practice";
			break;
		case "Q":
			$matchTypeLong="Qualifying";
			break;
		case "E":
			$matchTypeLong="Elimination";
			break;
	}
	
	$qry="SELECT redRawScore, blueRawScore, redBonus, blueBonus, matchtime.matchComplete
	FROM matchtime,matchresult
	where matchresult.matchNum='$matchNum' AND matchresult.matchType='$matchType'";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	else
	{
		$data=mysql_fetch_array($res);
		$redRawScore=$data['redRawScore'];
		$blueRawScore=$data['blueRawScore'];
		$redBonus=$data['redBonus'];
		$blueBonus=$data['blueBonus'];
		$matchComplete=$data['matchComplete'];
	}
?>
<html>
<head>
<title>Edit Match Result</title>
</head>
<body>
<h1>Edit Match Result for <?php echo "$matchTypeLong Match $matchNum";?></h1>
<h4><a href="viewMatchList.php">Back to Match List</a></h3>
<form method="post">

Red Raw Score: <input name="redRawScore" type="text" value="<?=$redRawScore?>"><br>
Red Bonus: <input name="redBonus" type="text" value="<?=$redBonus?>"><br>

Blue Raw Score: <input name="blueRawScore" type="text" value="<?=$blueRawScore?>"><br>
Blue Bonus: <input name="blueBonus" type="text" value="<?=$blueBonus?>"><br>
Completed: <input name="matchComplete" type="checkbox" value="1" <?if($matchComplete==1)echo 'checked';?>><br><br>
<input type="hidden" name="action" value="update">
<input type="hidden" name="matchType" value="<?=$matchType?>">
<input type="hidden" name="matchNum" value="<?=$matchNum?>">
<input type="submit" value="Save">
<table border="1" style="text-align: center">
<caption>Teams in <?=$matchTypeLong?> Match <?=$matchNum?></caption>
<tr><th>Team #</th><th>Color</th><th>Positon</th><th>Penalty</th><th>Red Card</th><th>Yellow Card</th></tr>
<?php
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$qry="SELECT * FROM teamresult WHERE matchType='$matchType' AND matchNum='$matchNum' ORDER BY teamColor,teamPosition";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	else
	{
		while(($curRow=mysql_fetch_array($res)))
		{
			if($curRow['teamColor']=="B") $color="blue";
			else	$color="red";
			echo "<tr><td>$curRow[teamNum]</td><td style='background-color: $color'></td><td>$curRow[teamPosition]</td><td>$curRow[teamPenalty]</td><td>$curRow[teamRedCard]</td><td>$curRow[teamYellowCard]</td><td><a href='editTeamResult.php?matchType=$curRow[matchType]&matchNum=$curRow[matchNum]&teamNum=$curRow[teamNum]'>edit</a></tr>";
		}
	}
?>
</table>
</form>
<?php include 'footer.php';?>
</body>
</html>