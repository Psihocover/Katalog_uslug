<?
// выводим категорию товаров,
// идентификатор которй пришел
// через адресную строку
$query = mysql_query("SELECT * FROM jic_item as A, jic_category as B WHERE A.id_category = B.cat_id AND A.id_category = ".$_GET['id_cat']." ORDER by A.id DESC");
// узнаем количество товаров в этой категории	
$num = mysql_num_rows ($query);
// если товары есть, то выводим

if ($num)
{
	$cat_title = mysql_fetch_assoc ($query);

	$user_title = htmlspecialchars($cat_title['name_cat']);
	$user_keywords = htmlspecialchars($cat_title['name_cat']." | ".$cat_title['descr']);
	$user_description = htmlspecialchars($cat_title['descr']);
	// подключаем шапку
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
	// используем ф-ию для постраничной навигации
	@$start = page_list ($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_CAT);
	// запрашиваем товары, но уже зная, с какого товара выводить,
	// сколько штук на странице,
	// и как их сортировать
	$query2 = mysql_query("SELECT * FROM jic_item as A, jic_category as B WHERE A.id_category = B.cat_id AND A.id_category = ".$_GET['id_cat']." ORDER by title LIMIT $start, $COUNT_SHOW_ITEMS_IN_CAT");
	echo "<table width=100% align=center cellpadding=10 cellspacing=10><tr valign=top>";
	// лепим массив в цикле
	while($list = mysql_fetch_assoc($query2))
	{	
		// узнаем, какую валюту печатать на странице у каждого товара
		if 		($list['money_type'] == "D") $money_type = "$";
		elseif 	($list['money_type'] == "E") $money_type = "&euro;";
		elseif 	($list['money_type'] == "G") $money_type = "гривен";
		else $money_type = "руб.";
	
		// выводим данные
		echo "<tr valign=top bgColor=\"#FAFAFA\" onMouseOut=bgColor=\"#FAFAFA\" onMouseOver=bgColor=\"#FFFFFF\"><td>
		<div align=right><small>Артикул: #0000".$list['id']."</small></div>
		<center><h4><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$list['id_category']."/".$list['id']."/\">
		".$list['title']."</a></h4></center>";
		$list['description'] = substr($list['description'], 0, 130);
		echo nl2br($list['description'])."...";
		echo "<br /><strong>Цена: ".$list['price']." ".$money_type."</strong></td>";
		//echo "<td>Просмотров: ".$list['hits']."</td>";
		echo "</tr>";
	}
	echo "</table>";
	// выводим постраничную навигацию
	$path_to_page = "cat";
	@show_page_list($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_CAT, $_GET['id_cat'], $path_to_page);
	echo "<strong><center>Количество товаров в данной категории: ".$num."</strong></center>";
}
// иначе грязно выругаемся
else
{
	// подключаем шапку
	require_once ("head.inc.php"); 
	?>
	<!-- start content -->
	<div id="content">
	  <div class="post">
		<div class="entry">
<strong><center>В данной категории пока что нет товаров!</center></strong>
<?
}
?>
    </div>
  </div>
</div>
<!-- end content -->
