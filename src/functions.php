<?php
	include('includes/conn.php');
	function user_details($userid)
	{
		$qry = "select * from users where UserID= $userid";
		$res = mysql_query($qry) or die(mysql_error());
		$row = mysql_fetch_array($res);
		return $row;
	}
	function chef_details($chefid)
	{
		$qry = "select * from users,cooks where users.UserID= $chefid";
		$res = mysql_query($qry) or die(mysql_error());
		if(mysql_num_rows($res)==0)
		{
			return -1;
		}
		$row = mysql_fetch_array($res);
		return $row;
	}
	function mailbox_details($userid)
	{
		$qry = "select * from mailbox where UserID= $userid";
		$res = mysql_query($qry) or die(mysql_error());
		if(mysql_num_rows($res)==0)
		{
			return -1;
		}
		$result = array();
		while($row = mysql_fetch_array($res))
		{
			array_push($result,$row);
		}
		return $result;
	}
?>