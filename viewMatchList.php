<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Match List</title>
</head>
<body>
<?include 'header.php'?>
<div id='title'>Match List</div>
<h3><a href="addMatches.php">Add Matches</a></h3>
<table border="1" style="text-align: center">
<caption>Match List</caption>
<tr><th>Match Type</th><th>Match #</th><th>Match Time</th><th>Blue Score</th><th>Red Score</th><th>Teams</th></tr>
<?php
	date_default_timezone_set(@date_default_timezone_get());
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	/*
	select matchtime.matchType,matchtime.matchNum,redRawScore+redBonus-(select SUM(teamPenalty) from fms.teamresult
	WHERE
	teamresult.matchType=matchtime.matchType AND
	teamresult.matchNum=matchtime.matchNum AND
	teamColor='R') as redScore,
	blueRawScore+blueBonus-(select SUM(teamPenalty) from fms.teamresult
	WHERE
	teamresult.matchType=matchtime.matchType AND
	teamresult.matchNum=matchtime.matchNum AND
	teamColor='B') as blueScore
	from teamresult,matchtime,matchresult
	group by matchtime.matchType,matchtime.matchNum;
	*/
	$qry="select matchtime.matchType,matchtime.matchNum,matchtime.matchTime
			from matchtime
			group by matchtime.matchType,matchtime.matchNum;";
	/*
	select matchtime.matchType,matchtime.matchNum,redRawScore+redBonus-(select SUM(teamPenalty) from fms.teamresult
	WHERE
	teamresult.matchType=matchtime.matchType AND
	teamresult.matchNum=matchtime.matchNum AND
	teamColor='R') as redScore,
	blueRawScore+blueBonus-(select SUM(teamPenalty) from fms.teamresult
	WHERE
	teamresult.matchType=matchtime.matchType AND
	teamresult.matchNum=matchtime.matchNum AND
	teamColor='B') as blueScore
	from matchtime,matchresult
	group by matchtime.matchType,matchtime.matchNum;
	*/
	$matchdata=mysql_query($qry);
	if(!$matchdata){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
	while(($curRow=mysql_fetch_array($matchdata)))
	{
		// Write out match type, match number, match time
		echo "<tr><td>$curRow[matchType]</td><td>$curRow[matchNum]</td><td>".date_create($curRow['matchTime'])->format("g:ia")."</td>";
		
		//Get the data for this match
		$qry="select matchtime.matchType,matchtime.matchNum,redRawScore+redBonus-IFNULL(
			(select SUM(teamPenalty) from teamresult
				WHERE
				teamresult.matchType=matchtime.matchType AND
				teamresult.matchNum=matchtime.matchNum AND
				teamColor='R')
		,0)
		as redScore,
		blueRawScore+blueBonus-IFNULL(
			(select SUM(teamPenalty) from teamresult
				WHERE
				teamresult.matchType=matchtime.matchType AND
				teamresult.matchNum=matchtime.matchNum AND
				teamColor='B')
		,0)
		AS blueScore
		from matchtime,matchresult
		where matchresult.matchType='$curRow[matchType]' AND matchresult.matchNum='$curRow[matchNum]';";
		
		
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
		$scoreData=mysql_fetch_array($res);
		
		echo "<td>$scoreData[blueScore]</td><td>$scoreData[redScore]</td>";
		
		$qry="SELECT teamNum,teamColor FROM teamresult where matchNum='$curRow[matchNum]' AND matchType='$curRow[matchType]' order by teamColor,teamPosition ASC";
		$teamdata=mysql_query($qry);
		if(!$teamdata){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
		while(($curRow2=mysql_fetch_array($teamdata)))
		{
			if($curRow2['teamColor']=="B")	echo "<td class='blueteam'>";
			else	echo "<td class='redteam'>";
			echo "$curRow2[teamNum]</td>";
		}
		echo "<td><a href='editMatchResult.php?matchNum=$curRow[matchNum]&matchType=$curRow[matchType]'>view</a></tr>";	
	}
	
?>
</table>
<?include 'footer.php';?>
</body>
</html>