<?php
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$id=mysql_real_escape_string($_REQUEST['id']);

	if($_REQUEST['action']=='update')
	{
		$teamColor=mysql_real_escape_string($_REQUEST['teamColor']);
		$teamPosition=mysql_real_escape_string($_REQUEST['teamPosition']);
		$teamPenalty=mysql_real_escape_string($_REQUEST['teamPenalty']);
		$teamRedCard=mysql_real_escape_string($_REQUEST['teamRedCard']);
		$teamYellowCard=mysql_real_escape_string($_REQUEST['teamYellowCard']);
		$qry="UPDATE teamresult SET teamColor='$teamColor', teamPosition='$teamPosition', teamPenalty='$teamPenalty', teamRedCard='$teamRedCard', teamYellowCard='$teamYellowCard' WHERE ID='$id'";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	}
		
	$qry="SELECT teamColor, teamPosition, teamPenalty, teamRedCard, teamYellowCard,matchNum,matchType,teamNum FROM teamresult where id='$id'";
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	else
	{
		$data=mysql_fetch_array($res);
		$teamColor=$data['teamColor'];
		$teamPosition=$data['teamPosition'];
		$teamPenalty=$data['teamPenalty'];
		$teamRedCard=$data['teamRedCard'];
		$teamYellowCard=$data['teamYellowCard'];
		$matchNum=$data['matchNum'];
		$matchType=$data['matchType'];
		$teamNum=$data['teamNum'];
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
?>

<html>
<head>
<title>Edit Team Result</title>
</head>
<body>
<h1>Edit Team Result for Team <?php echo "$teamNum in $matchTypeLong Match $matchNum";?></h1>
<h4><a href="editMatchResult.php?matchNum=<?=$matchNum?>&matchType=<?=$matchType?>">Back to Match Result</a></h3>
<form method="post">
Color: <select name="teamColor">
<option value="B" <?php if($teamColor=="B") echo "selected";?>>Blue</option>
<option value="R" <?php if($teamColor=="R") echo "selected";?>>Red</option>
</select> <br>
Position: <input name="teamPosition" type="text" value="<?=$teamPosition?>"> <br>
Penalty: <input name="teamPenalty" type="text" value="<?=$teamPenalty?>"> <br>
Red Card: <input name="teamRedCard" type="text" value="<?=$teamRedCard?>"> <br> 
Yellow Card: <input name="teamYellowCard" type="text" value="<?=$teamYellowCard?>"> <br>
<input type="hidden" name="action" value="update">
<input type="submit" value="Save">
</form>
</body>
</html>