
<?
// ������� �����,
// ������������� �������� ������
// ����� �������� ������
$query = mysql_query ("SELECT * FROM jic_item as A, jic_category as B WHERE A.id_category = B.cat_id AND A.id = '".intval($_GET['id_item'])."'");

// ���� ������ ������ ������
if ($query)
{
	// ���� ���� ����� � ������ ���������������
	if (mysql_num_rows($query))
	{
		// ����� ������������� ������
		$list_cat = mysql_fetch_assoc ($query);

		$user_title = htmlspecialchars($list_cat['title']);
		$user_keywords = htmlspecialchars($list_cat['title']." | ".$list_cat['description']);
		$user_description = htmlspecialchars($list_cat['description']);
		// ���������� �����
		require_once ("head.inc.php"); 
		?>
	  
	  
	<!-- start content -->
	<div id="content">
	  <div class="post">
		<div class="title">
		  <h2><a href="http://<?=$_SERVER['HTTP_HOST']."/".$dir."cat/".$list_cat['cat_id']?>/"><?=$list_cat['name_cat']?></a></h2>
		</div>
		<div class="entry">
		<?
		// �������
		echo "<div align=right><small>�������: #0000".$list_cat['id']."</small></div><h4>".$list_cat['title']."</h4>";	
		echo "�������� ������: ".nl2br($list_cat['description'])."<br />";
		// �������� ��� ������ � ����������� ���
		if ($list_cat['money_type'] == "D") $money_type = "$";
		elseif ($list_cat['money_type'] == "E") $money_type = "&euro;";
		elseif ($list_cat['money_type'] == "G") $money_type = "��.";
		else $money_type = "���.";
		echo "<strong>����: ".$list_cat['price']." ".$money_type."</strong>";
	}
	// ����� ��������
	else
	{
		// ���������� �����
		require_once ("head.inc.php"); 
		echo "<strong><center>��� ������ ������</center></strong>";
	}
}
?>
    </div>
  </div>
</div>
<!-- end content -->
