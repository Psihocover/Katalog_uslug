<?
if (!defined('SITE')) die();
// ���������� ������� ��������


    ###############    �������� ���������    ###############    
if(@$_GET['op'] == "drop_category")
{
	// ��������� �� ������� �������� ���������. ���� ��� ����, �� �������� ����� ������ ��������
	if (mysql_num_rows(mysql_query("SELECT * FROM jic_category WHERE root_cat = '".$_GET['id_cat']."'")))
	die("<br /><br /><center><strong>������ ������� ���������-��������. ������� ������ �������� ������������!!!</strong></center>");
	// ������� ������ �� ���� ���������
	$delete_item_to_category = mysql_query("DELETE FROM jic_item WHERE id_category = ". $_GET['id_cat']);
	if (!$delete_item_to_category) die("<br /><br /><center><strong>�� ������� ������� ������ �� ���� ���������</strong></center>");
	//������� ���������
	$delete_category = mysql_query("DELETE FROM jic_category WHERE cat_id = ". $_GET['id_cat']);
	//���� �������, ��...
	if ($delete_category) echo "<br /><br /><center><strong>��������� ������� ������� � ������ �� ���� ��������� ����</strong></center>";
	else echo "<br /><br /><center><strong>�� ������� ������� ���������!</strong></center>";
}
    ###############    ����� ����� �������� ���������    ###############    

    ###############    �������������� ���������    ###############    
elseif (@$_GET['op'] == "edit_category")
{
	// ���� ���������� ��������
	if(@$_POST['name_cat'])
	{
		// ��������������� ����������������� � HTML �������
		$name_cat = htmlspecialchars($_POST['name_cat']);
		$descr  = htmlspecialchars($_POST['descr']);
		// ���������� �������
		if (!get_magic_quotes_gpc())
		{
			 $name_cat = mysql_escape_string($name_cat);
			 $descr = mysql_escape_string($descr);
		}
		else
		{
			$name_cat = str_replace("'","`",$name_cat);
			$descr = str_replace("'","`",$descr);
		}
		//  ��������� ��
		$query = mysql_query("
		UPDATE jic_category 
		SET root_cat = '".$_POST['id_category']."', 
		name_cat = '".$name_cat."', 
		descr = '".$descr."' 
		WHERE cat_id = '".intval($_GET['id_cat'])."'
		");
		// ���� �������, ��...
		if ($query) echo "<br /><br /><center><strong>���������� ������ ������� ���������</strong></center>";
		else echo "<br /><br /><center><strong>�� ������� �������� ������</strong></center>";
	}
	// ����� ������� ����� � ������� ��� ��������������
	else
	{
		// ������� ������ �� ��
		$query = mysql_query("SELECT * FROM jic_category WHERE cat_id = '".intval($_GET['id_cat'])."'");
		// ����� ������������� ������
		$line = mysql_fetch_assoc($query);
		echo "<table align=center cellspacing=2 cellpadding=2 border=0 width=60%><tr><td><br />
		<FORM ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/$id_cat/edit_category/\" METHOD=POST>
		��������:<br /><input type=text size=60 name=name_cat value=\"".$line['name_cat']."\"><br /><br />
		��������: <input type=text size=60  name=descr value=\"".$line['descr']."\"><br /><br />
		�������� ���������:<br /><select name=id_category>";
		echo "<option value=0>--------  �������� ����������  -----</option>";
		// �-�� ������ ������ ���������
		function subcategory_cat($id, $sub)
		{
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{	
				echo "<option value=".$category['cat_id']."".( $category['cat_id'] == @$line['root_cat'] ? " selected " : "" ).">";
				for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat']." ";
				subcategory_cat($category['cat_id'], $sub+1);
			}
		}
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<option value=".$category['cat_id']."".( $category['cat_id'] == @$line['root_cat'] ? " selected " : "" ).">>>>".$category['name_cat']." ";
			subcategory_cat($category['cat_id'], $sub);
		}
		echo "</select><br /><br /><input type=submit value=��������></form></td></tr></table>";
	}
}
    ###############    ����� ����� �������������� ���������    ###############    

    ###############    �������� ���������    ###############    
elseif (@$_GET['op'] == "add_category")
{
	// ���� ���������� ��������
	if(@$_POST['name_cat'])
	{
		// ��������������� ����������������� � HTML �������
		$name_cat = htmlspecialchars($_POST['name_cat']);
		$descr  = htmlspecialchars($_POST['descr']);
		// ���������� �������
		if (!get_magic_quotes_gpc())
		{
			 $name_cat = mysql_escape_string($name_cat);
			 $descr = mysql_escape_string($descr);
		}
		else
		{
			$name_cat = str_replace("'","`",$name_cat);
			$descr = str_replace("'","`",$descr);
		}
		// ��������� ������ � ��
		$query = mysql_query("
		INSERT jic_category 
		SET root_cat = '".$_POST['id_category']."', 
		name_cat = '".$name_cat."', 
		descr = '".$descr."'
		");
		// ���� ������, ��...
		if($query) echo "<br /><br /><center><strong>��������� ���������</strong></center>";
		// ���� �� ������, ��
		else echo "<br /><br /><center><strong>������ ��� ���������� ���������</strong></center>";
	}
	// ����� ������� ����� ��� ����������
	else
	{
		echo "<table cellspacing=2 cellpadding=2 border=0 width=70%><tr><td>
		<FORM ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\" METHOD=POST>
		��������: <input type=text size=60  name=name_cat><br /><br />
		��������: <input type=text size=60  name=descr><br /><br />
		�������� ���������: <select name=id_category>";
		// �-�� ������ ������ ���������
		echo "<option value=0>----------- �������� ���������� -----------</option>";
		function subcategory_sub($id, $sub)
		{
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{	
				echo "<option value=\"".$category['cat_id']."\">";
				for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat']." ";
				subcategory_sub($category['cat_id'], $sub+1);
			}
		}
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<option value=\"".$category['cat_id']."\">>>>".$category['name_cat']."<br>";
			subcategory_sub($category['cat_id'], $sub);
		}
		echo "</select><br /><br /><input type=submit value=�������� ���������></form></td></tr></table>";
	}
}
    ###############    ����� ����� �������� ���������    ###############    

    ###############    ���� ���������� �������    ###############    
elseif (@$_GET['op'] == "add_item")
{
	if (@$_POST['title']) // ���� POST ������ �� ����
	{
		// ��������������� ����������������� � HTML �������
		// � ������������� ��� ���������� � �����
		$id_category = intval			($_POST['id_category']);
		$price		 = intval			($_POST['price']);
		$title		 = htmlspecialchars	($_POST['title']);
		$description = htmlspecialchars	($_POST['description']);

		// �������� ������� ���������
		if (!$price) die ("<br /><br /><center><strong>
		������� ���������� ���������, ��� ����� � �������!</strong></center>");

		// ����������
		if (!get_magic_quotes_gpc())
		{
			 $title = mysql_escape_string($title);
			 $description = mysql_escape_string($description);
		}
		else
		{
			$title = str_replace("'","`",$title);
			$description = str_replace("'","`",$description);
		}

		// ��������� ������ � ��
		$query = mysql_query("
		INSERT jic_item  
		SET id_category = '$id_category', 
		title = '$title', 
		description = '$description', 
		price = '$price', 
		money_type = '".$_POST['money_type']."', 
		print_to_index = '".$_POST['print_to_index']."'
		");
		// ���� ������, ��...
		if($query) echo "<br /><br /><center><strong>����� ��������</strong></center>";
		// ���� �� ������, ��
		else echo "<br /><br /><center><strong>������ ��� ���������� ������</strong></center>";
	}
	// ����� ������� ����� ��� ����������
	else
	{
		echo "<table align=center cellspacing=2 cellpadding=2 border=0>
		<tr><td align=right><br /><FORM METHOD=POST
		 ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\">
		��������: <input type=text size=60 maxlength=250 name=title><br /><br />
		��������: <textarea rows=6 cols=60 name=description></textarea><br /><br />
		�������� ���������: <select name=id_category>";
		// �-�� ������ ������ ���������
		function subcategory_it($id, $sub)
		{
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{	
				echo "<option value=\"".$category['cat_id']."\">";
				for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat']." ";
				subcategory_it($category['cat_id'], $sub+1);
			}
		}
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<optgroup label=\"".$category['name_cat']."\">";
			subcategory_it($category['cat_id'], $sub);
		}
		echo "</select><br /><br />
		���������: <input type=text size=60 maxlength=9 name=price><br /><br />
		��� ������: <select name=money_type>
		<option value=\"R\" selected>�����</option>
		<option value=\"D\">������� ���</option>
		<option value=\"E\">����</option>
		<option value=\"G\">������</option>
		</select><br /><br />
		���������� �� ������� ��������? <select name=print_to_index>
		<option value=\"yes\" selected>��, ����������</option>
		<option value=\"no\">���</option>
		</select><br /><br />
		<input type=submit value=�������� ������������ ������>
		</form></td></tr></table>";
	}
}
    ###############    ����� ����� ���������� �������    ###############    

    ###############    ���� �������� �������    ###############    
elseif (@$_GET['op'] == "drop_item")
{
	// ������ ������ �� ��������
	$delete = mysql_query("DELETE FROM jic_item 
	WHERE id = '".intval($_GET['id_item'])."' LIMIT 1");
	//���� �������, ��...
	if ($delete) echo "<br /><br /><center><strong>
	������������ ������� �������</strong></center>";
	//�����...
	else echo "<br /><br /><center><strong>
	�� ������� ������� ������������!</strong></center>";
}
    ###############    ����� ����� �������� �������    ###############    

    ###############    ���� �������������� �������    ###############    
elseif (@$_GET['op'] == "edit_item")
{
	if (@$_POST['title']) // ���� POST ������ �� ����
	{
		// ��������������� ����������������� � HTML �������
		// � ������������� ��� ���������� � �����
		$id_category = intval			($_POST['id_category']);
		$price		 = intval			($_POST['price']);
		$title		 = htmlspecialchars	($_POST['title']);
		$description = htmlspecialchars	($_POST['description']);

		// �������� ������� ���������
		if (!$price) die ("<br /><br /><center><strong>
		������� ���������� ���������, ��� ����� � �������!</strong></center>");

		// ����������
		if (!get_magic_quotes_gpc())
		{
			 $title = mysql_escape_string($title);
			 $description = mysql_escape_string($description);
		}
		else
		{
			$title = str_replace("'","`",$title);
			$description = str_replace("'","`",$description);
		}

		// ��������� ������ � ��
		$query = mysql_query("
		UPDATE jic_item  
		SET id_category = '$id_category', 
		title = '$title', 
		description = '$description', 
		price = '$price', 
		money_type = '".$_POST['money_type']."', 
		print_to_index = '".$_POST['print_to_index']."' 
		WHERE  id = '".intval($_GET['id_item'])."' 
		LIMIT 1 
		");
		// ���� ������, ��...
		if($query) echo "<br /><br /><center><strong>
		����� �������</strong></center>";
		// ���� �� ������, ��
		else echo "<br /><br /><center><strong>
		������ ��� ��������� ������</strong></center>";
	}
	// ����� ������� ����� ��� ����������
	else
	{
		// ������� ������ ��� ����� ������
		$query_edit = mysql_query ("SELECT * FROM jic_item
		 WHERE id = '".intval($_GET['id_item'])."'");
		if (mysql_num_rows ($query_edit))
		{
			// ����� ������������� ������
			$items = mysql_fetch_assoc ($query_edit);
			// ������� �����
			echo "<table align=center cellspacing=2 cellpadding=2 border=0>
			<tr><td align=right><FORM METHOD=POST
			 ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$items['id_category']."/".$items['id']."/edit_item/\">
			��������: 
			<input type=text size=60 maxlength=250 name=title value=\"".$items['title']."\">
			<br /><br />
			��������: 
			<textarea name=description rows=6 cols=60>".$items['description']."
			</textarea><br /><br />
			�������� ���������: <select name=id_category>";
			// �-�� ������ ������ ���������
			function subcategory_edit($id, $sub)
			{
				$categories = mysql_query("SELECT * FROM jic_category
				 WHERE root_cat = $id ORDER by name_cat ASC");
				while($category = mysql_fetch_array($categories)) 
				{	
					echo "<option value=".$category['cat_id']."	".( $category['cat_id'] == $_GET['id_cat'] ?" selected":"" ).">";
					for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat'];
					subcategory_edit($category['cat_id'], $sub+1);
				}
			}
			$categories = mysql_query("SELECT * FROM jic_category
			 WHERE root_cat = 0 ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{
				$sub = 1;
				echo "<option value=".$category['cat_id']."
				".( $category['cat_id'] == $_GET['id_cat'] ? " selected " : "" ).">
				".$category['name_cat'];
				subcategory_edit($category['cat_id'], $sub);
			}
			echo "</select><br /><br />
			���������: 
			<input type=text maxlength=9 size=60 name=price value=\"".$items['price']."\">
			<br /><br />
			��� ������: <select name=money_type>
			<option value=R".($items['money_type']=="R"?" selected":"").">�����
			<option value=D".($items['money_type']=="D"?" selected":"").">������� ���
			<option value=E".($items['money_type']=="E"?" selected":"").">����
			<option value=G".($items['money_type']=="G"?" selected":"").">������
			</select><br /><br />
			���������� �� ������� ��������? <select name=print_to_index>
			<option value=yes".($items['print_to_index']=="yes"?" selected":"").">
			��, ����������</option>
			<option value=no".($items['print_to_index']=="no"?" selected":"").">���</option>
			</select><br /><br />
			<input type=submit value=�������� ������������ ������>
			</form></td></tr></table>";
		}
	}
}
    ###############    ����� ����� �������������� �������    ###############    

    ###############    ���� ������ ������� �����-���� �� ���������    ###############    
elseif (!@$_GET['op'] && intval(@$_GET['id_cat']) > 0 && !@$_GET['id_item'])
{
	// ����������� ��� ������ ��� ������ ���������
	$query = mysql_query("SELECT * FROM jic_item as A,
	 jic_category as B
	  WHERE A.id_category = B.cat_id AND
	   A.id_category = ".intval($_GET['id_cat'])."
	    ORDER by A.id DESC");
	// ������ ���������� ������� � ���� ���������	
	$num = mysql_num_rows ($query);
	echo "<br /><strong><center>���������� ������� � ������ ���������:
	 ".$num."</strong></center><br><br>";
	// ���������� �-�� ��� ������������ ���������
	@$start = page_list ($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_ADMINPAGE);
	// ����������� ������, �� ��� ����, � ������ ������ ��������,
	// ������� ���� �� ��������,
	// � ��� �� �����������
	$query2 = mysql_query("SELECT * FROM jic_item as A,
	 jic_category as B
	  WHERE A.id_category = B.cat_id AND
	   A.id_category = ".intval($_GET['id_cat'])."
	    ORDER by $SORT_FIELD_ITEMS_IN_ADMINPAGE $DESC_ASC_ITEMS_IN_ADMINPAGE
		 LIMIT $start, $COUNT_SHOW_ITEMS_IN_ADMINPAGE");
	
	echo "<table border=0 width=100% align=center cellspacing=3 cellpadding=10>
	<tr bgcolor=#FAFAFAv valign=top>
	<td width=90% align=center><strong>���������� � ������</strong></td>
	<td align=center><strong>������������� ������ ������</strong></td>
	<td align=center><strong>������� �����</strong></td>
	</tr>";
	// ����� ������ � �����
	while($list = mysql_fetch_assoc($query2))
	{	
		// ������, ����� ������ �������� �� �������� � ������� ������
		if 		($list['money_type'] == "D") $money_type = "$";
		elseif 	($list['money_type'] == "E") $money_type = "&euro;";
		elseif 	($list['money_type'] == "G") $money_type = "������";
		else $money_type = "���.";
		//
		if ($list['print_to_index'] == "yes")
		$print_to_index = "���������"; else $print_to_index = "<strong>��</strong> ���������";
		// ������� ������
		
		echo "<tr bgcolor=#FAFAFA valign=top><td>
		<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$list['id_category']."/".$list['id']."/\">
		<strong>".$list['title']."</strong></a><br />
		".nl2br($list['description'])."<br />
		<strong>����:</strong> ".$list['price']." ".$money_type."<br />
		<strong>����������:</strong> ".$list['hits']."<br />
		�� ������� <strong>".$print_to_index."</strong>
		</td>";
		
		echo "<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$list['id_category']."/".$list['id']."/edit_item/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/edit.gif\"></a></td>";
		echo "<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$list['id_category']."/".$list['id']."/drop_item/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/del.png\"></a></td></tr>";
	}
	echo "</table>";
	// ������� ������������ ���������
	$path_to_page = "admin/cat";
	@show_page_list($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_ADMINPAGE, $_GET['id_cat'], $path_to_page);
	echo "<br /><br /><center>
	<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> �������� ����� </a>
	</center>";
}
    ###############    ����� ����� ������ ������� �����-���� �� ���������    ###############    
    ###############    ����� ���������    ###############    
else
{
	echo " <table border=0 width=100% align=center cellspacing=3 cellpadding=10>
	<tr bgcolor=#FAFAFA>
	<td align=center><strong>�������� ���������</strong></td>
	<td align=center><strong>������ ��� ���������</strong></td>
	<td align=center><strong>������������� ������ ���������</strong></td>
	<td align=center><strong>������� ���������</strong></td>
	</tr>";
	// �-�� ������ ������ ���������
	$GLOBALS['dir'] = $dir;
	function subcategory_admin($id, $sub)
	{
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id");
		while($category = mysql_fetch_array($categories)) 
		{	
			$count_it = mysql_num_rows(mysql_query("SELECT * FROM jic_item WHERE id_category = '".$category['cat_id']."'"));
			echo "<tr bgcolor=#FAFAFA><td width=100%>";
			for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;";
			echo "<strong>- ".$category['name_cat']."</strong> [".@$count_it."]</td>
			 <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."admin/cat/".$category['cat_id']."/\">";
			 if ($count_it) echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/items.gif\"></a>";
			 echo "</td>
			<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."admin/cat/".$category['cat_id']."/edit_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/edit.gif\"></a></td>
			 <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."admin/cat/".$category['cat_id']."/drop_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/del.png\"></a></td></tr>";
			subcategory_admin($category['cat_id'], $sub+1);
		}
	}
	$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0");
	while($category = mysql_fetch_array($categories)) 
	{
		$sub = 1;
		echo "<tr bgcolor=#CCCCCC><td align=center colspan=2><strong>".$category['name_cat']."</strong></td>
		<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$category['cat_id']."/edit_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/edit.gif\"></a></td>
		 <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$category['cat_id']."/drop_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/del.png\"></a></td></tr>";
		
		//</tr>";
		//echo "<tr bgcolor=#FAFAFA><td><strong>".$category['name_cat']."</strong> [".@$count_it."]</td>
		// <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$category['cat_id']."/\">";
		// if ($count_it) echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/".$dir."/images/items.gif\"></a>";
		// echo "</td>
		subcategory_admin($category['cat_id'], $sub);
	}
	echo "</table><br />";
	echo "<center>
	<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/addcat.gif\">
	<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\"> �������� ��������� </a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/additem.gif\"> 
	<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> �������� ������������ ������ </a></center>";
}
###############    ����� ������ ���������    ###############    
?>
