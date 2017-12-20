<?
	// подключаем шапку
	require_once ("head.inc.php"); 
?>
<!-- start content -->

<div id="content">
  <div class="post">
    <div class="title">Выберите категорию!
      <h2>Рекомендуемые товары</h2>
    </div>
    <div class="entry">
      <?
	$td = 0;
	// выводим случайные товары
	$query = mysql_query ("SELECT * FROM jic_item WHERE print_to_index = 'yes' ORDER by RAND() LIMIT $COUNT_SHOW_ITEMS");
	if ($query)
	{
		echo "<table width=100% align=center cellpadding=10 cellspacing=10><tr valign=top>";
		while ($list_cat = mysql_fetch_assoc ($query))
		{
			echo "<td bgColor=\"#FAFAFA\" onMouseOut=bgColor=\"#FAFAFA\" onMouseOver=bgColor=\"#FFFFFF\">";
			if ($list_cat['money_type'] == "D") $money_type = "$";
			elseif ($list_cat['money_type'] == "E") $money_type = "&euro;";
			elseif ($list_cat['money_type'] == "G") $money_type = "гр.";
			else $money_type = "руб.";
			
			

			echo "<h4><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$list_cat['id_category']."/".$list_cat['id']."/\">".$list_cat['title']."</a></h4><strong>".$list_cat['price']." ".$money_type."</strong><br />";	
			
			echo substr(nl2br($list_cat['description']), 0, 100);
			echo "</td>";
			$td++; if ($td % $COUNT_SHOW_IN_LINE == 0) echo "</tr><tr valign=top>";
		}
		echo "</tr></table>";
	}
	?>
    </div>
  </div>
</div>
<!-- end content -->
