<?
setlocale(LC_ALL,'ru_RU.CP1251');
if($_POST['search_word']) $search = $_POST['search_word'];
$search = strip_tags($search);
$search = trim($search);
$search = htmlspecialchars($search);
if ($search == "" || strlen($search) < 3 || strlen($search) > 64) die ("<br>��������� <a href='javascript:history.back(1)'>�����</a> � ������� ������� ���������, � ����������� �������� �� ����� 3 � �� ����� 64");
else
{
	include("mysql.php");
	include("config.php");
	// ���������� �����
	require_once ("head.inc.php"); 
	?>
	<!-- start content -->
	<div id="content">
	  <div class="post">
		<div class="title">
		  <center>
			<h2>����� ������� �� ��������</h2>
		  </center>
		</div>
		<div class="entry">
	<?
	if (!get_magic_quotes_gpc()) $search = mysql_escape_string($search);
	else $search = str_replace("'","`",$search);
	$query = mysql_query("SELECT * FROM jic_item WHERE title LIKE '%". $search. "%' OR description LIKE '%".$search."%' OR price = '".$search."'");
	if ($query) $count_rows = mysql_num_rows ($query);
	if (@$count_rows)
	{	
		echo "<h4>�� ������ <strong>".$search."</strong>. ������� �������: [<strong>".$count_rows."</strong>]</h4>";
		while($message = mysql_fetch_assoc($query))
		{	
			$message['title'] = eregi_replace($search, "<font color=\"red\"><strong>$search</strong></font>", $message['title']);
			echo "<h4><a href =\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$message['id_category']."/".$message['id']."/\">".$message['title']."</a></h4>";
		}
	}
	else echo "<h4>�� ������ <strong>".$search."</strong>. �� ������ ������� ������ �� �������.</h4>";
    ?>
	</div>
  </div>
</div>
<!-- end content -->
<?
// ���������� ����
include ("menu.inc.php");

// ���������� ������ �����
include ("foot.inc.php"); 
}
?>