<?
// ������� ��������� �������,
// ������������� ������ ������
// ����� �������� ������
$query = mysql_query("SELECT * FROM jic_item as A, jic_category as B WHERE A.id_category = B.cat_id AND A.id_category = ".$_GET['id_cat']." ORDER by A.id DESC");
// ������ ���������� ������� � ���� ���������	
$num = mysql_num_rows ($query);
// ���� ������ ����, �� �������

if ($num)
{
	$cat_title = mysql_fetch_assoc ($query);

	$user_title = htmlspecialchars($cat_title['name_cat']);
	$user_keywords = htmlspecialchars($cat_title['name_cat']." | ".$cat_title['descr']);
	$user_description = htmlspecialchars($cat_title['descr']);
	// ���������� �����
	require_once ("head.inc.php"); 
	?>
	<!-- start content -->
	<div id="content">
	  <div class="post">
		<div class="title">
		  <h2>
			<?=$cat_title['name_cat']?>
		  </h2>
		</div>
		<div class="entry">
      <?
	// ���������� �-�� ��� ������������ ���������
	@$start = page_list ($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_CAT);
	// ����������� ������, �� ��� ����, � ������ ������ ��������,
	// ������� ���� �� ��������,
	// � ��� �� �����������
	$query2 = mysql_query("SELECT * FROM jic_item as A, jic_category as B WHERE A.id_category = B.cat_id AND A.id_category = ".$_GET['id_cat']." ORDER by title LIMIT $start, $COUNT_SHOW_ITEMS_IN_CAT");
	echo "<table width=100% align=center cellpadding=10 cellspacing=10><tr valign=top>";
	// ����� ������ � �����
	while($list = mysql_fetch_assoc($query2))
	{	
		// ������, ����� ������ �������� �� �������� � ������� ������
		if 		($list['money_type'] == "D") $money_type = "$";
		elseif 	($list['money_type'] == "E") $money_type = "&euro;";
		elseif 	($list['money_type'] == "G") $money_type = "������";
		else $money_type = "���.";
	
		// ������� ������
		echo "<tr valign=top bgColor=\"#FAFAFA\" onMouseOut=bgColor=\"#FAFAFA\" onMouseOver=bgColor=\"#FFFFFF\"><td>
		<div align=right><small>�������: #0000".$list['id']."</small></div>
		<center><h4><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$list['id_category']."/".$list['id']."/\">
		".$list['title']."</a></h4></center>";
		$list['description'] = substr($list['description'], 0, 130);
		echo nl2br($list['description'])."...";
		echo "<br /><strong>����: ".$list['price']." ".$money_type."</strong></td>";
		//echo "<td>����������: ".$list['hits']."</td>";
		echo "</tr>";
	}
	echo "</table>";
	// ������� ������������ ���������
	$path_to_page = "cat";
	@show_page_list($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_CAT, $_GET['id_cat'], $path_to_page);
	echo "<strong><center>���������� ������� � ������ ���������: ".$num."</strong></center>";
}
// ����� ������ ����������
else
{
	// ���������� �����
	require_once ("head.inc.php"); 
	?>
	<!-- start content -->
	<div id="content">
	  <div class="post">
		<div class="entry">
<strong><center>� ������ ��������� ���� ��� ��� �������!</center></strong>
<?
}
?>
    </div>
  </div>
</div>
<!-- end content -->
