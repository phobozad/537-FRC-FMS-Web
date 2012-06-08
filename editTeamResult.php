<?php
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$matchType=mysql_real_escape_string($_REQUEST['matchType']);
	$matchNum=mysql_real_escape_string($_REQUEST['matchNum']);
	$teamNum=mysql_real_escape_string($_REQUEST['teamNum']);
	
	if($_REQUEST['action']=='update')
	{
		$teamColor=mysql_real_escape_string($_REQUEST['teamColor']);
		$teamPosition=mysql_real_escape_string($_REQUEST['teamPosition']);
		$teamPenalty=mysql_real_escape_string($_REQUEST['teamPenalty']);
		$teamRedCard=mysql_real_escape_string($_REQUEST['teamRedCard']);
		$teamYellowCard=mysql_real_escape_string($_REQUEST['teamYellowCard']);
		$qry="UPDATE teamresult SET teamColor='$teamColor', teamPosition='$teamPosition', teamPenalty='$teamPenalty', teamRedCard='$teamRedCard', teamYellowCard='$teamYellowCard' WHERE matchType='$matchType' AND matchNum='$matchNum' AND teamNum='$teamNum'";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	}
		
	$qry="SELECT teamColor, teamPosition, teamPenalty, teamRedCard, teamYellowCard,matchNum,matchType,teamNum FROM teamresult WHERE matchType='$matchType' AND matchNum='$matchNum' AND teamNum='$teamNum'";
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
<link rel="stylesheet" type="text/css" href="style.css">
<title>Edit Team Result</title>
</head>
<body>
<?include 'header.php'?>
<div id='title'>Edit Team Result for Team <?php echo "$teamNum in $matchTypeLong Match $matchNum";?></div>
<h3><a href="editMatchResult.php?matchNum=<?=$matchNum?>&matchType=<?=$matchType?>">Back to Match Result</a></h3>
<form method="post">
Color: <select name="teamColor">
<option class="blueteam" value="B" <?php if($teamColor=="B") echo "selected";?>>Blue</option>
<option class="redteam" value="R" <?php if($teamColor=="R") echo "selected";?>>Red</option>
</select> <br>
Position: <input name="teamPosition" type="text" value="<?=$teamPosition?>"> <br>
Penalty: <input name="teamPenalty" type="text" value="<?=$teamPenalty?>"> <br>
Red Card: <input name="teamRedCard" type="text" value="<?=$teamRedCard?>"> <br> 
Yellow Card: <input name="teamYellowCard" type="text" value="<?=$teamYellowCard?>">
<input type="hidden" name="action" value="update">
<br><br><input type="submit" value="Save">
</form>
<?include 'footer.php';?>
</body>
</html>