<?
if (!defined('SITE')) die();
if(@$_GET['op'] == "edit")
{
	$profile = mysql_query("SELECT * FROM jic_admin");
	if(mysql_numrows($profile) == 1)
	{
		$profile = mysql_fetch_array($profile);
		echo "<center><form method=post action=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/profile/\">";
		echo "�����:<br />
<input name=login type=text value=\"".htmlspecialchars($profile['login'])."\"><br />
			������:<br />
<input name=password type=text><br><br>
			<input type=submit value=\"��������\"></center>
		</form>";
	}
	else echo "<br /><br /><center><strong>������ ��� ������ �������!</strong></center>";
}
else 
{
	if(trim(@$_POST['login']) != "" && trim(@$_POST['password']) != "")
	{
		$_POST['login']  = htmlspecialchars(stripslashes($_POST['login'])); 
		$_POST['password'] = htmlspecialchars(stripslashes($_POST['password']));
		$change = mysql_query("UPDATE jic_admin SET login = '". $_POST['login'] ."', password = '". md5($_POST['password']) ."'");
		if ($change)
		{
			$_SESSION['login'] = $_POST['login'];
			$_SESSION['password'] = $_POST['password'];
			echo "<br /><br /><center><strong>������� ������� �������</strong></center>";
		}
		else die("<br /><br /><center><strong>������ ��� ������ �������!</strong></center>");
	}
	else
	{
		$profile = mysql_query("SELECT * FROM jic_admin");
		if(mysql_numrows($profile) == 1)
		{
			$profile = mysql_fetch_array($profile);
			echo "<table cellpadding=1 cellspacing=1 align=center>";
			echo "<tr bgcolor=#FFFFFF><td>�����:</td><td>".htmlspecialchars($profile['login'])."</td></tr>
			<tr bgcolor=#FFFFFF><td colspan=2><a href=\"http://". $_SERVER['HTTP_HOST'] ."/".$dir."admin/profile/edit/\">�������� ������</a></td></tr></table>";
		}
		else echo "<br /><br /><center><strong>������ ��� ������ �������!</strong></center>";
	}
}
?>