<?php
	date_default_timezone_set(@date_default_timezone_get());
	require 'dbinfo.php';
	mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
	mysql_select_db("$db") or die("Unable to select database");
	
	$action=$_REQUEST['action'];
	$data=$_REQUEST['data'];
	$timePerMatch=$_REQUEST['timePerMatch'];
	$startTime=date_create(trim($_REQUEST['startTime']));
	$matchType=$_REQUEST['matchType'];
?>
<html>
<head><title>Add Matches</title></head>
<body>
<h1>Add matches</h1>
<form method="post">
<!--Preview-->

<?
if($action=="preview")
{
	echo "<table border='1' style='text-align: center'><caption>Proposed Match List</caption><tr><th>Match #</th><th>Match Time</th>";
	$lines=explode("\r\n",$data);
	$curline=explode(" ",$lines[0]);
	$maxI=(count($curline)-1)/2;
	for($i=0;$i<$maxI;$i++)
	{
		if($i<($maxI/2))
			echo "<th style='background-color:red; color:white;'>Red ".($i+1)."</th>";
		else
			echo "<th style='background-color:blue; color:white;'>Blue ".($i-($maxI/2)+1)."</th>";
	}
	echo "</tr>";
	$icount=(count($lines));
	for ($i=0;$i<$icount;$i++)
	{
		$curline=explode(" ",$lines[$i]);
		$jcount=(count($curline)-1);
		echo "<tr><td>$curline[0]</td>";
		$matchOffset=DateInterval::createFromDateString(($timePerMatch*($curline[0]-1)*60)."seconds");
		$matchTime[$i]=date_add(new DateTime($startTime->format("g:ia"),new DateTimeZone(date_default_timezone_get())),$matchOffset);

		echo "<td>".date_format($matchTime[$i],"g:ia")."</td>";
		
		for ($j=1;$j<$jcount;$j+=2)
		{
			echo "<td>$curline[$j]</td>";
		}
		echo "</tr>";
	}

	echo "</table>
	<input type='hidden' name='startTime' value='".$startTime->format('g:ia')."'>
	<input type='hidden' name='timePerMatch' value='$timePerMatch'>
	<input type='hidden' name='matchType' value='$matchType'>
	<input type='hidden' name='data' value='$data'><br>
	<input type='hidden' name='action' value='add'><input type='submit' value='Save'>";
}
else if($action=="add")
{
	
	$qry="START TRANSACTION";
	$sqlerror=false;
	$res=mysql_query($qry);
	if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';$sqlerror=true;}
	
	$lines=explode("\r\n",$data);
	$icount=(count($lines)-1);
	for ($i=0;$i<=$icount;$i++)
	{
		$curline=explode(" ",$lines[$i]);
		$jcount=(count($curline)-1);
		
		$matchOffset=DateInterval::createFromDateString(($timePerMatch*($curline[0]-1)*60)."seconds");
		$matchTime=date_add(new DateTime($startTime->format("g:ia"),new DateTimeZone(date_default_timezone_get())),$matchOffset)->format("c");
		
		$qry="INSERT INTO matchtime (matchTime,matchNum,matchType) VALUES ('$matchTime','$curline[0]','$matchType')";
		$res=mysql_query($qry);
		if(!$res){echo "<font color='red'><b>".mysql_error().": $qry</b></font><br>";$sqlerror=true;}
		
		for ($j=1;$j<=$jcount;$j+=2)
		{
			
			if($j<($jcount/2))
			{
				$teamColor="R";
				$teamPosition=($j+1)/2;
			}
			else
			{
				$teamColor="B";
				$teamPosition=($j+1-($jcount/2))/2;
			}
			
			
			$qry="INSERT INTO teamresult (matchType,matchNum,teamNum,teamColor,teamPosition) VALUES ('$matchType','$curline[0]','$curline[$j]','$teamColor','$teamPosition')";
			$res=mysql_query($qry);
			if(!$res){echo "<font color='red'><b>".mysql_error().": $qry</b></font><br>";$sqlerror=true;}
		}
		echo "</tr>";
	}
	if($sqlerror==true)
	{
		$qry="ROLLBACK";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
		echo "<a href='javascript:history.back()'>Back</a>";
	}
	else
	{
		$qry="COMMIT";
		$res=mysql_query($qry);
		if(!$res){echo '<font color="red"><b>'.mysql_error().'</b></font><br>';}
		echo "<a href='viewMatchList.php'>View Match List</a>";
	}
	
}
else
{
?>
	Start time: <input type="text" name="startTime">
	Assume <input type="text" name="timePerMatch" value="10"> minutes per match <br>
	Match Type: 
	<select name="matchType">
		<option value="P">Practice</option>
		<option value="Q">Qualifying</option>
		<option value="E">Elimination</option>
	</select>
	Output from matchmaker (sparse): <br><textarea name='data'>1 1675 0 4174 0 2194 0 4095 0 1714 0 2506 0
2 967 0 4371 0 93 0 706 0 868 0 2115 0
3 269 0 3184 0 1091 0 4247 0 171 0 1736 0</textarea> <br>
	<input type='hidden' name='action' value='preview'><input type='submit' value='Preview'>
<?
}
?>
</form>
<?php include 'footer.php';?>
</body>
</html>
