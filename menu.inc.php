<!-- start sidebar -->

<div id="sidebar">
  <ul>
    <li id="categories">
      <h2>���������</h2>
      <ul>
        <?
		// ������� ����
		$categories = mysql_query("SELECT * FROM jic_category 
		WHERE root_cat = 0");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<li><strong>".$category['name_cat']."</strong></li>";
			subcategory($category['cat_id'], $sub);
		}
		?>
      </ul>
    </li>
    <li>
      <h2>���� ��������</h2>
      <ul><center><h4>�����:<br />�����, ��.����, 58</h4>
	  <h4>���������� ��������:<br />+79061592589<br />+79061592558</h4></center>
      </ul>
    </li>
  </ul>
</div>
<!-- end sidebar -->
